<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/18/13
 * @Date Modified: 11/21/13
 * @Purpose: Used to complete various pay period functions
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $this->employees = $this->load_model('employees');
 *      $this->system_di->template->employees = $this->employees->getEmployees();
 */
class model_payPeriod {
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    
    /**
     * @Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $system_di;
        $this->system_di = $system_di;
        
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
    public function get_pay_period($date='current') {
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
            
            $pay_period_id = $this->system_di->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
                ':monday' => (int) $monday[0],
                ':sunday' => (int) $sunday[0]
            ));
            
            if (empty($pay_period_id)) {
                return False;
            } else {
                return array($monday, $sunday, $pay_period_id[0]['pay_period_id']);
            }
        } elseif ('all' === $date) {
            $pay_periods = $this->system_di->db->query("SELECT * FROM `pay_periods`");
            
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
            
            $pay_period_id = $this->system_di->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
                ':monday' => (int) $monday[0],
                ':sunday' => (int) $sunday[0]
            ));
            
            if (empty($pay_period_id)) {
                return False;
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
        
        $hours = $this->system_di->db->query("SELECT `date`, `time`, `operation` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id ORDER BY `date`, `employee_punch_id` ASC", array(
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
        
        array_walk($return_hours, function($hour, $date) use(&$return_hours) {
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
                $in = strtotime(date('g:ia', $hour['in'][$i]));
                $out = strtotime(date('g:ia', $hour['out'][$i]));
                
                //Used to find the difference of two timestamps in hours that are rounded to the nearest 15 minutes (.25 of an hour)
                $return_hours[$date]['total_hours'] += round((($out/60 - $in/60)/60)/0.25, 0)*0.25;
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
     * @Purpose: Used to figure out if employee should punch in or out, then performs that operation
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
     * @Purpose: Used to add a new pay period to the database
     */
    protected function add_pay_period() {
        $current_pay_period = $this->get_pay_period();
        
        $add_pay_period = $this->system_di->db->query("INSERT INTO `pay_periods` (`pay_period_id`, `pay_period_monday`, `pay_period_friday`) VALUES ('', :monday, :friday)", array(
            ':monday' => $current_pay_period[0],
            ':friday' => $current_pay_period[1]
        ));
        
        $check_pay_period = $this->system_di->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_friday`=:friday", array(
            ':monday' => $current_pay_period[0],
            ':friday' => $current_pay_period[1]
        ));
        
        return $check_pay_period;
    }
    
    /**
     * @Purpose: Used to check what the last punch an employee made is (in or out)
     */
    protected function check_punch($employee_id) {
        $employee_id = (int) $employee_id;
        $current_pay_period = $this->get_pay_period();
        
        $check_pay_period = $this->system_di->db->query("SELECT `pay_period_id` FROM `pay_periods` WHERE `pay_period_monday`=:monday AND `pay_period_sunday`=:sunday", array(
            ':monday' => $current_pay_period[0][0],
            ':sunday' => $current_pay_period[1][0]
        ));
        
        if (empty($check_pay_period)) {
            $check_pay_period = $this->add_pay_period();
        }
        
        $last_operation = $this->system_di->db->query("SELECT `operation` FROM `employee_punch` WHERE `employee_id`=:id AND `pay_period_id`=:pay_period_id ORDER BY `employee_punch_id` DESC LIMIT 0,1", array(
            ':id' => (int) $employee_id,
            ':pay_period_id' => (int) $check_pay_period[0]['pay_period_id']
        ));
        
        if (empty($last_operation)) {
            $last_operation[0]['operation'] = 'out';
        }
        
        return array('last_operation' => $last_operation[0]['operation'], 'pay_period' => $check_pay_period);
    }
    
    /**
     * @Purpose: Used to punch an employee in
     */
    protected function punch_in($employee_id, $pay_period_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        
        $punch_in = $this->system_di->db->query("INSERT INTO `employee_punch` (`pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES (:pay_period_id, :employee_id, :time, :date, 'in')", array(
            ':pay_period_id' => (int) $pay_period_id,
            ':employee_id' => (int) $employee_id,
            ':time' => $date_time['time'],
            ':date' => $date_time['date']
        ));
        
        return array('in', $date_time);
    }
    
    /**
     * @Purpose: Used to punch an employee out
     */
    protected function punch_out($employee_id, $pay_period_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        
        $punch_out = $this->system_di->db->query("INSERT INTO `employee_punch` (`pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES (:pay_period_id, :employee_id, :time, :date, 'out')", array(
            ':pay_period_id' => (int) $pay_period_id,
            ':employee_id' => (int) $employee_id,
            ':time' => $date_time['time'],
            ':date' => $date_time['date']
        ));
        
        return array('out', $date_time);
    }
    
    /**
     * @Purpose: Used to update time punches
     */
    protected function update_time() {
        $pay_period = $this->get_pay_period(strtotime($_POST['date']));
        $operation = ('in' == $_POST['time_operation']) ? 'in' : 'out';
        $time_index = (int) $_POST['time_index'];
        $time = strtotime($_POST['time']);
        
        $time_to_update = $this->system_di->db->query("SELECT `employee_punch_id` FROM `employee_punch` WHERE `pay_period_id`=:pay_period_id AND `employee_id`=:employee_id AND `date`=:date AND `operation`=:operation ORDER BY `employee_punch_id` LIMIT $time_index,1", array(
            ':pay_period_id' => (int) $pay_period[2],
            ':employee_id' => (int) $_POST['employee_id'],
            ':date' => $_POST['date'],
            ':operation' => $operation,
        ));
        
        if (empty($time_to_update)) {
            $add_time = $this->system_di->db->query("INSERT INTO `employee_punch` (`employee_punch_id`, `pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES ('', :pay_period_id, :employee_id, :time, :date, :operation)", array(
                ':pay_period_id' => $pay_period[2],
                ':employee_id' => (int) $_POST['employee_id'],
                ':time' => $time,
                ':date' => $_POST['date'],
                ':operation' => $operation
            ));
            
            return True;
        }
        
        if (empty($time)) {
            $remove_time = $this->system_di->db->query("DELETE FROM `employee_punch` WHERE `employee_punch_id`=:employee_punch_id", array(
                ':employee_punch_id' => (int) $time_to_update[0]['employee_punch_id']
            ));
            
            return True;
        }
        
        $update_time = $this->system_di->db->query("UPDATE `employee_punch` SET `time`=:time WHERE `employee_punch_id`=:employee_punch_id", array(
            ':time' => $time,
            ':employee_punch_id' => $time_to_update[0]['employee_punch_id']
        ));
        
        return True;
    }
    
    /**
     * @Purpose: Used to add a date to the timecard
     */
    protected function add_date() {
        $pay_period = $this->get_pay_period($_POST['date']);
        
        if ((int) $pay_period[2] === (int) $_POST['pay_period']) {
            $add_date = $this->system_di->db->query("INSERT INTO `employee_punch` (`employee_punch_id`, `pay_period_id`, `employee_id`, `time`, `date`, `operation`) VALUES ('', :pay_period_id, :employee_id, '', :date, 'in')", array(
                ':pay_period_id' => (int) $pay_period[2],
                ':employee_id' => (int) $_POST['employee_id'],
                ':date' => $_POST['date']
            ));
            
            return True;
        } else {
            return False;
        }
    }
    
    /**
     * @Purpose: Used to figure out which times should be editable, and have an onClick attribute
     */
    protected function is_time_editable($hour, $times_array, $time_index, $time_operation, $date) {
        $return = '';

        if (array_key_exists($time_index, $hour[$time_operation]) && 0 != $hour[$time_operation][$time_index]) {
            $return = '<td onclick="updateTime(\'' . $date . '\', ' . $time_index . ', \'' . $time_operation . '\')">' . date($this->_timeFormat, $hour[$time_operation][$time_index]) . '</td>';
        } elseif (array_key_exists(($time_index - 1), $hour[$time_operation]) || 0 === $time_index) { //Check if previous times are in
            $return = '<td onclick="updateTime(\'' . $date . '\', ' . $time_index . ', \'' . $time_operation . '\')"></td>';
        } else {
            $return = '<td></td>';
        }
        
        return $return;
    }
    
    /**
     * @Purpose: Used to create the table that is seen in the view.
     */
    public function generate_pay_period_table($employee_id, $pay_period) {
        $hours = $this->get_hours($employee_id, $pay_period);
        $return = '';
        
        if (!is_bool($hours)) {
            foreach ($hours as $date=>$hour) {
                $table = array();
                $iterations = (count($hour['in']) > count($hour['out'])) ? count($hour['in']) : count($hour['out']);
                $iterations = ($iterations >= 4) ? $iterations : 3;
                $i_tracker = 0;
                $multiplier = 0;
                
                for ($i=0; $i<$iterations; $i++) {
                    if ($i_tracker>2) {
                        $multiplier++;
                        $i_tracker = -1;
                    }
                    
                    $table[$multiplier]['in']['date'] = $date;
                    $table[$multiplier]['in'][] = $this->is_time_editable($hour, $table, $i, 'in', $date);
                    $table[$multiplier]['out']['date'] = $date;
                    $table[$multiplier]['out'][] = $this->is_time_editable($hour, $table, $i, 'out', $date);
                    
                    $i_tracker++;
                }
                
                foreach ($table as &$row) {
                    $in_count = count($row['in']);
                    $out_count = count($row['out']);
                    
                    for ($i=$in_count; $i<4; $i++) {
                        $row['in'][$i-1] = '<td></td>';
                    }
                    for ($i=$out_count; $i<4; $i++) {
                        $row['out'][$i-1] = '<td></td>';
                    }
                    
                    $return .= '<tr>';
                    $return .= '<td>' . $row['in']['date'] . '</td>';
                    
                    for ($i=0; $i<3; $i++) {
                        $return .= $row['in'][$i];
                        $return .= $row['out'][$i];
                    }
                    
                    $return .= '<td>' . $hour['total_hours'] . '</td>';
                    $return .= '</tr>';
                    
                    $hour['total_hours'] = 'Continuation of ' . $row['in']['date'];
                }
                
                //die(var_dump($return));
            }
        }
        
        return $return;
    } //End generate_current_pay_period_table
    
    /**
     * @Purpose: Used to create the table that displays all the previous pay periods
     */
    public function generate_previous_pay_periods_table($employee_id, $selected_pay_period) {
        $pay_periods = $this->get_pay_period('all');
        $employees_pay_periods = $this->system_di->db->query("SELECT `pay_period_id` FROM `employee_punch` WHERE `employee_id`=:employee_id", array(
            ':employee_id' => (int) $employee_id
        ));
        
        if (empty($employees_pay_periods)) {
            $employees_pay_periods = array('pay_periods' => array());    
        } else {
            foreach ($employees_pay_periods as $pay_period) {
                $employees_pay_periods['pay_periods'][] = $pay_period['pay_period_id'];
            }
        }

        $table_periods_index = array(0, 1, 2, 3);
        $tables = array(array(), array(), array(), array());
        $return_tables = array();
        $table_index = -1;
        $rows = 0;
        
        for ($i=0; $i<count($pay_periods); $i++) {
            $table_index = ($table_index < 3) ? $table_index+1: 0;
            
            $tables[$table_index][] = $pay_periods[$table_periods_index[$table_index]];
            $temp_rows = count($tables[$table_index]);
            
            if ($temp_rows > $rows) {
                $rows = $temp_rows;
            }
            
            $table_periods_index[$table_index] += 4;
        }
        
        foreach ($tables as $key=>$table) {
            $trs = 0;
            $return_tables[$key] = '<tbody>';
            
            foreach ($table as $dates) {
                $trs++;
                if (in_array($dates['pay_period_id'], $employees_pay_periods['pay_periods'])) {
                    $bold = 'bold';
                } else {
                    $bold = "";
                }
                
                if ($selected_pay_period == $dates['pay_period_id']) {
                    $italic = ' italic';
                } else {
                    $italic = '';
                }
                
                $return_tables[$key] .= '<tr"><td class="' . $bold . $italic . '" onClick="loadPayPeriod(' . (int) $employee_id . ', ' . $dates['pay_period_monday'] . ')">' . date($this->_dateFormat, $dates['pay_period_monday']) . '</td><td class="' . $bold . $italic . '" onClick="loadPayPeriod(' . (int) $employee_id . ', ' . $dates['pay_period_monday'] . ')">' . date($this->_dateFormat, $dates['pay_period_sunday']) . '</td></tr>';
            }
            
            for ($i=$trs; $i<$rows; $i++) {
                $return_tables[$key] .= '<tr><td><br /></td><td><br /></td></tr>';
            }
            
            $return_tables[$key] .= '</tbody>';
        }
        
        return $return_tables;
    } //End generate_previous_pay_period_table
} //End model_payPeriod