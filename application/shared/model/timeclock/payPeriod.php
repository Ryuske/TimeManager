<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/18/13
 * @Date Modified: 11/27/13
 * @Purpose: Used to complete various pay period functions
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      --- EXAMPLE 1 ---
 *      $pay_period = $this->load_model('timeclock_payPeriod');
 *      $pay_period->get_pay_period('current');
 *          This will return the current pay period that you're in.
 *          Returns with 2 arrays (start date and end date), that are in http://us2.php.net/getdate format
 *      $pay_period->get_pay_period('11/26/13');
 *          This will return the start date and end dates of the pay period that date falls within
 *          The two arrays are in http://us2.php.net/getdate format
 *
 *      --- EXAMPLE 2 ---
 *      $pay_period = $this->load_model('timeclock_payPeriod');
 *      $total_hours = $pay_period->total_hours_for_pay_period(1, 'current');
 *          $total_hours has the total hours worked by the employee with id 1 for the current pay period
 *          Parameter 1 is the ID of you the employee you want the total hours of
 *          Parameter 2 is the pay period you want the total hours of
 *
 *      --- EXAMPLE 3 ---
 *      $pay_period = $this->load_model('timeclock_payPeriod');
 *      $pay_period_table = $pay_period->generate_pay_period_table(1, 'current');
 *          $pay_period_table contains table rows for you can echo out.
 *          It only contains the <tr>'s that go within the <tbody> but you have to write the rest of the table
 *          It will display (6 <td>'s):
 *              The date
 *              3 in/out pairs
 *              Total Hours
 *
 *      --- EXAMPLE 4 ---
 *      $pay_period = $this->load_model('timeclock_payPeriod');
 *      $previous_pay_periods = $pay_period->generate_previous_pay_periods_table(1, 'current');
 *          $previous_pay_periods is a list of all the previous paeriods.
 *          Parameter 1 is the employee ID. It is used to determin which pay periods are bold (indicating the have time associated with them)
 *          Parameter 2 will be the pay period that it italizes (which indicates the one you're currently viewing)
 *
 *          It returns 4 arrays, so it goes like this:
 *          [0] = array(1, 5, 9);
 *          [1] = array(2, 6, 10);
 *          [2] = array(3, 7, 11);
 *          [3] = array(4, 8, 11);
 *
 *          This was done so you can display 4 side-by-side tables and display more pay periods within a smaller place.
 *          Which https://github.com/Ryuske/TimeClock/blob/master/screenshots/screen_timecard_pay_periods.jpg
 *             for an example of 4 side-by-side tables.
 *
 *          When you echo out a table, it will automatically loop through and add the <tr>'s that go within <tbody>, though you still
 *          have to write the <tbody> and everything else. Each <tr> has 2 <td>s, start date and end date.
 */

global $sys;
$sys->router->load_traits('payPeriod', 'timeclock');
 
class model_timeclock_payPeriod {
    use payPeriod_views, payPeriod_actions;
    
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    protected $_add_date_response = '';
    
    /**
     * @Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $sys;
        $this->sys = $sys;
        
        if (array_key_exists('update_time', $_POST)) {
            $this->update_time();
        }
        if (array_key_exists('add_date', $_POST)) {
            $this->add_date();
        }
    }
    
    /**
     * @Purpose: Used to return a pay period in date format
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
                ':monday' => (int) $monday[0],
                ':sunday' => (int) $sunday[0]
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
                ':monday' => (int) $monday[0],
                ':sunday' => (int) $sunday[0]
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
     * @Purpose: Used to get the hours of the requested pay period
     */
    public function get_hours($employee_id, $pay_period) {
        $pay_period = $this->get_pay_period($pay_period);
        
        $hours = $this->sys->db->query("SELECT `date`, `time`, `operation` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id ORDER BY `date`, `employee_punch_id` ASC", array(
            ':pay_period_id' => $pay_period[2],
            ':employee_id' => (int) $employee_id
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
     * @Purpose: Used to calculate how many hours an employee has worked on a specified pay period
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
     * @Purpose: Used to update time punches
     */
    protected function update_time() {
        $pay_period = $this->get_pay_period(strtotime($_POST['date']));
        $operation = ('in' == $_POST['time_operation']) ? 'in' : 'out';
        $time_index = (int) $_POST['time_index'];
        $time = strtotime($_POST['time']);
        
        $time_to_update = $this->sys->db->query("SELECT `employee_punch_id` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id AND `date`=:date AND `operation`=:operation ORDER BY `employee_punch_id` LIMIT $time_index,1", array(
            ':pay_period_id' => (int) $pay_period[2],
            ':employee_id' => (int) $_POST['employee_id'],
            ':date' => $_POST['date'],
            ':operation' => $operation,
        ));
        
        if (empty($time_to_update)) {
            $add_time = $this->sys->db->query("INSERT INTO `employee_punch` (`employee_punch_id`, `pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES ('', :pay_period_id, :employee_id, :time, :date, :operation)", array(
                ':pay_period_id' => $pay_period[2],
                ':employee_id' => (int) $_POST['employee_id'],
                ':time' => $time,
                ':date' => $_POST['date'],
                ':operation' => $operation
            ));
            
            return True;
        }
        
        if (empty($time)) {
            $remove_time = $this->sys->db->query("DELETE FROM `employee_punch` WHERE `employee_punch_id`=:employee_punch_id", array(
                ':employee_punch_id' => (int) $time_to_update[0]['employee_punch_id']
            ));
            
            return True;
        }

        $update_time = $this->sys->db->query("UPDATE `employee_punch` SET `time`=:time WHERE `employee_punch_id`=:employee_punch_id", array(
            ':time' => $time,
            ':employee_punch_id' => $time_to_update[0]['employee_punch_id']
        ));
        
        return True;
    }
} //End model_timeclock_payPeriod

//End file
