<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/5/13
 * Date Modified: 12/31/13
 * Purpose: A trait for general payperiod actions
 */

trait payPeriod_actions {
    /**
     * Purpose: Used to add a new pay period to the database
     */
    protected function add_pay_period() {
        $current_date = getdate();
        $monday = getdate(strtotime('last Monday'));
        $sunday = getdate(strtotime('next Sunday'));
    
        if ($current_date['weekday'] === 'Monday') {
            $monday = getdate(strtotime('Today'));
        }
        if ($current_date['weekday'] === 'Sunday') {
            $sunday = getdate(strtotime('Today'));;
        }
        
        $check_pay_period = $this->sys->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
            ':monday' => (int) substr($monday[0], 0, 20),
            ':sunday' => (int) substr($sunday[0], 0, 20)
        ));
        
        if (!empty($check_pay_period)) {
            return $check_pay_period;
        }
        
        $add_pay_period = $this->sys->db->query("INSERT INTO `pay_periods` (`pay_period_id`, `pay_period_monday`, `pay_period_sunday`) VALUES ('', :monday, :sunday)", array(
            ':monday' => (int) substr($monday[0], 0, 20),
            ':sunday' => (int) substr($sunday[0], 0, 20)
        ));
        
        $check_pay_period = $this->sys->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
            ':monday' => (int) substr($monday[0], 0, 20),
            ':sunday' => (int) substr($sunday[0], 0, 20)
        ));
        
        return $check_pay_period;
    }
    
    /**
     * Purpose: Used to add a date to the timecard
     */
    protected function add_date() {
        $pay_period = $this->get_pay_period($_POST['date']);

        if ((int) $pay_period[2] === (int) $_POST['id']) {
            $get_dates = $this->sys->db->query("SELECT `date` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id AND `date`=:date", array(
                ':pay_period_id'    => (int) substr($pay_period[2], 0, 4),
                ':employee_id'      => (int) substr($_POST['employee_id'], 0, 6),
                ':date'             => substr($_POST['date'], 0, 8)
            ));
            
            //Used to determin if all the previous in/out slots have been filled before adding a new date
            if (is_int(count($get_dates)/6)) {
                $add_date = $this->sys->db->query("INSERT INTO `employee_punch` (`employee_punch_id`, `pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES ('', :pay_period_id, :employee_id, '', :date, 'in')", array(
                    ':pay_period_id'    => (int) substr($pay_period[2], 0, 4),
                    ':employee_id'      => (int) substr($_POST['employee_id'], 0, 6),
                    ':date'             => substr($_POST['date'], 0, 8)
                ));
                
                return true;
            }
            
            $this->_add_date_response = '<script type="text/javascript">jQuery(document).ready(function () {add_date_response();});</script>';
            return false;
        } else {
            return false;
        }
    }
    
    /**
     * Purpose: Used to figure out if employee should punch in or out, then performs that operation
     */
    public function employee_punch($employee_id) {
        $employee_id = (int) $employee_id;
        $check_punch = $this->check_punch($employee_id);
        $return = 'Error';
        
        switch ($check_punch['last_operation']) {
            case 'in':
                $return = $this->punch_out($employee_id, $check_punch['pay_period'][0]['pay_period_id']);
                break;
            case 'out':
                $return = $this->punch_in($employee_id, $check_punch['pay_period'][0]['pay_period_id']);
                break;
            default:
                break;
        }
        
        return $return;
    }
    
    /**
     * Purpose: Used to check what the last punch an employee made is (in or out)
     */
    protected function check_punch($employee_id) {
        $employee_id = (int) $employee_id;
        $current_pay_period = $this->get_pay_period();
        
        $check_pay_period = $this->sys->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
            ':monday' => (int) substr($current_pay_period[0][0], 0, 20),
            ':sunday' => (int) substr($current_pay_period[1][0], 0, 20)
        ));
        
        if (empty($check_pay_period)) {
            $check_pay_period = $this->add_pay_period();
        }
        
        $last_operation = $this->sys->db->query("SELECT `operation` FROM `employee_punch` WHERE `employee_id`=:id AND `pay_period_id`=:pay_period_id ORDER BY `employee_punch_id` DESC LIMIT 0,1", array(
            ':id'               => (int) substr($employee_id, 0, 6),
            ':pay_period_id'    => (int) substr($check_pay_period[0]['pay_period_id'], 0, 4)
        ));
        
        if (empty($last_operation)) {
            $last_operation[0]['operation'] = 'out';
        }
        
        return array('last_operation' => $last_operation[0]['operation'], 'pay_period' => $check_pay_period);
    }
    
    /**
     * Purpose: Used to punch an employee in
     */
    protected function punch_in($employee_id, $pay_period_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        
        $punch_in = $this->sys->db->query("INSERT INTO `employee_punch` (`pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES (:pay_period_id, :employee_id, :time, :date, 'in')", array(
            ':pay_period_id'    => (int) substr($pay_period_id, 0, 4),
            ':employee_id'      => (int) substr($employee_id, 0, 6),
            ':time'             => (int) substr($date_time['time'], 0, 20),
            ':date'             => substr($date_time['date'], 0, 8)
        ));
        
        return array('in', $date_time);
    }
    
    /**
     * Purpose: Used to punch an employee out
     */
    protected function punch_out($employee_id, $pay_period_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        
        $punch_out = $this->sys->db->query("INSERT INTO `employee_punch` (`pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES (:pay_period_id, :employee_id, :time, :date, 'out')", array(
            ':pay_period_id'    => (int) substr($pay_period_id, 0, 4),
            ':employee_id'      => (int) substr($employee_id, 0, 6),
            ':time'             => (int) substr($date_time['time'], 0, 20),
            ':date'             => substr($date_time['date'], 0, 8)
        ));
        
        return array('out', $date_time);
    }
} //End payPeriod_actions

//End file
