<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/18/13
 * Date Modified: 12/31/13
 * Purpose: Used to complete various pay period functions
 */

global $sys;
$sys->router->load_helpers('traits', 'payPeriod', 'timemanager');
 
class model_timemanager_payPeriod {
    use payPeriod_views, payPeriod_actions;
    
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    protected $_add_date_response = '';
    
    /**
     * Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $sys;
        $this->sys = $sys;
        
        $is_admin = (array_key_exists('user', $this->sys->session)) ? $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($this->sys->session['user'], 0, 6)
        )) : '';
        
        if (!empty($is_admin)) {
            $is_admin = ('admin' === $is_admin[0]['employee_role']) ? true : false;
        } else {
            $is_admin = false;
        }
        
        if (array_key_exists('update_time', $_POST) && $is_admin) {
            $this->update_time();
        }
        if (array_key_exists('add_date', $_POST) && $is_admin) {
            $this->add_date();
        }
    }
    
    /**
     * Purpose: Used to return a pay period in date format
     */
    public function get_pay_period($date='current', $paginate=False) {
        $date = (is_string($date)) ? strtolower($date) : $date;
        
        if ('current' === $date) {
            $current_date = getdate();
            $monday = getdate(strtotime('last Monday'));
            $sunday = getdate(strtotime('next Sunday'));
        
            if ($current_date['weekday'] === 'Monday') {
                $monday = $current_date;
            }
            if ($current_date['weekday'] === 'Sunday') {
                $sunday = $current_date;
            }
            
            $pay_period_id = $this->sys->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
                ':monday' => (int) substr($monday[0], 0, 20),
                ':sunday' => (int) substr($sunday[0], 0, 20)
            ));
            
            if (empty($pay_period_id)) {
                $pay_period_id = $this->add_pay_period();
                return array($monday, $sunday, $pay_period_id[0]['pay_period_id']);
            } else {
                return array($monday, $sunday, $pay_period_id[0]['pay_period_id']);
            }
        } elseif ('all' === $date) {
            if (True === $paginate) {
                $start = ((1 >= $this->sys->template->page_id)) ? 0 : (int) ($this->sys->template->page_id-1) * ($this->sys->template->paginate_by * 4);
                $end = $this->sys->template->paginate_by * 4;
                $limit = 'LIMIT ' . $start . ',' . $end;
            } else {
                $limit = '';
            }

            $pay_periods = $this->sys->db->query("SELECT * FROM `pay_periods` ORDER BY `pay_period_monday` DESC $limit");
            
            return $pay_periods;
        } else {
            if (is_array($date)) {
                if (array_key_exists('seconds', $date)) {
                    $given_date =  $date;
                } elseif (array_key_exists('seconds', $date[0])) {
                    $given_date = $date[0];
                }
            } elseif (!is_numeric($date)) {
                $given_date = getdate(strtotime($date));
            } else {
                $given_date = getdate($date);
            }

            $monday = getdate(strtotime('last Monday', $given_date[0]));
            $sunday = getdate(strtotime('next Sunday', $given_date[0]));
            
            if ($given_date['weekday'] === 'Monday') {
                $monday = $given_date;
            }
            if ($given_date['weekday'] === 'Sunday') {
                $sunday = $given_date;
            }
            
            $pay_period_id = $this->sys->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
                ':monday' => (int) substr($monday[0], 0, 20),
                ':sunday' => (int) substr($sunday[0], 0, 20)
            ));
            
            if (empty($pay_period_id)) {
                $pay_period_id = $this->add_pay_period();
                return array($monday, $sunday, $pay_period_id[0]['pay_period_id']);
            } else {
                return array($monday, $sunday, $pay_period_id[0]['pay_period_id']);
            }
        }
        
        return False;
    } //End get_pay_period
    
    /**
     * Purpose: Used to get the hours of the requested pay period
     */
    public function get_hours($employee_id, $pay_period) {
        $pay_period = $this->get_pay_period($pay_period);
        
        $hours = $this->sys->db->query("SELECT `date`, `time`, `operation` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id ORDER BY convert(date, date), `employee_punch_id` ASC", array(
            ':pay_period_id'    => (int) substr($pay_period[2], 0, 4),
            ':employee_id'      => (int) substr($employee_id, 0, 6)
        ));
        
        $return_hours = array();
        
        array_walk($hours, function($row) use(&$return_hours) {
            if ('in' === $row['operation']) {
                $return_hours[$row['date']]['in'][] = $row['time'];
            } elseif ('out' === $row['operation']) {
                $return_hours[$row['date']]['out'][] = $row['time'];
            }
            
            $return_hours[$row['date']]['total_hours'] = 0;
        });
        
        $sys = $this->sys;
        array_walk($return_hours, function($hour, $date) use(&$return_hours, $sys) {
            $count = (array_key_exists('in', $hour)) ? count($hour['in']) : 0;
            
            if (!array_key_exists('in', $hour)) {
                $return_hours[$date]['in'] = array();
                $hour['in'] = array();
            }
            if (!array_key_exists('out', $hour)) {
                $return_hours[$date]['out'] = array();
                $hour['out'] = array();
            }
            if ($count > count($hour['out'])) {
               $count--;
            }
            
            if (empty($hour['in']) || empty($hour['out'])) {
                return 0;
            }
            
            for ($i=0; $i<$count; $i++) {
                $in = (array_key_exists('in', $hour) && array_key_exists($i, $hour['in'])) ? strtotime(date('g:ia', $hour['in'][$i])) : 0;
                $out = (array_key_exists('out', $hour) && array_key_exists($i, $hour['out'])) ? strtotime(date('g:ia', $hour['out'][$i])) : 0;

                //Used to find the difference of two timestamps in hours that are rounded to the nearest 15 minutes (.25 of an hour)
                if (0 < $out) {
                    switch ($sys->template->model_settings->round_time_by) {
                        case '1':
                            $round_by = 0.16;
                            $places = 0;
                            break;
                        case '15':
                            $round_by = 0.25;
                            $places = 0;
                            break;
                        case '30':
                            $round_by = 0.5;
                            $places = 0;
                            break;
                        default:
                            $round_by = 1;
                            $places = 2;
                    }
                    
                    $return_hours[$date]['total_hours'] += round((($out/60 - $in/60)/60)/$round_by, $places)*$round_by;
                }
            }
            
            if (!array_key_exists('total_hours', $return_hours[$date])) {
                $return_hours[$date]['total_hours'] = 0;
            }
        });
        
        return $return_hours;
    } //End get_hours
    
    /**
     * Purpose: Used to calculate how many hours an employee has worked on a specified pay period
     */
    public function total_hours_for_pay_period($employee_id, $pay_period) {
        $pay_period = $this->get_pay_period($pay_period);
        $hours = $this->get_hours((int) $employee_id, $pay_period);
        $total_hours = 0;
        
        for ($i=0; $i<7; $i++) {
            $date_in_loop = date($this->_dateFormat, strtotime('+' . $i . ' day', $pay_period[0][0]));
            
            if (!is_bool($hours) && array_key_exists($date_in_loop, $hours)) {
                $total_hours += $hours[$date_in_loop]['total_hours'];
            }
        }
        
        return $total_hours;
    }
    
    /**
     * Purpose: Used to update time punches
     */
    protected function update_time() {
        $pay_period = $this->get_pay_period(strtotime($_POST['date']));
        $operation = ('in' == $_POST['time_operation']) ? 'in' : 'out';
        $time_index = (int) $_POST['time_index'];
        $time = strtotime($_POST['time']);
        
        $time_to_update = $this->sys->db->query("SELECT `employee_punch_id` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id AND `date`=:date AND `operation`=:operation ORDER BY `employee_punch_id` LIMIT $time_index,1", array(
            ':pay_period_id'    => (int) substr($pay_period[2], 0, 4),
            ':employee_id'      => (int) substr($_POST['employee_id'], 0, 6),
            ':date'             => substr($_POST['date'], 0, 8),
            ':operation'        => $operation,
        ));
        
        if (empty($time_to_update)) {
            $add_time = $this->sys->db->query("INSERT INTO `employee_punch` (`employee_punch_id`, `pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES ('', :pay_period_id, :employee_id, :time, :date, :operation)", array(
                ':pay_period_id'    => (int) substr($pay_period[2], 0, 4),
                ':employee_id'      => (int) substr($_POST['employee_id'], 0, 6),
                ':time'             => (int) substr($time, 0, 20),
                ':date'             => substr($_POST['date'], 0, 8),
                ':operation'        => $operation
            ));
            
            return True;
        }
        
        if (empty($time)) {
            $remove_time = $this->sys->db->query("DELETE FROM `employee_punch` WHERE `employee_punch_id`=:employee_punch_id", array(
                ':employee_punch_id' => (int) substr($time_to_update[0]['employee_punch_id'], 0, 8)
            ));
            
            return True;
        }

        $update_time = $this->sys->db->query("UPDATE `employee_punch` SET `time`=:time WHERE `employee_punch_id`=:employee_punch_id", array(
            ':time'                 => (int) $time,
            ':employee_punch_id'    => (int) substr($time_to_update[0]['employee_punch_id'], 0, 8)
        ));
        
        return True;
    }
}

//End file
