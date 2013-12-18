<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/10/13
 * @Date Modified: 12/17/13
 * @Purpose: A trait for general job operations
 * @Version: 2.0
 */

trait job_timecard {
    /**
     * Purpose: Used to display the total hours assigned to a job
     */
    public function total_hours($job_id, $by_category=false) {
        $return_hours = 0;
        $hours = $this->get_hours($job_id);
        
        if ($by_category) {
            $return_hours = array();
            
            foreach ($hours as $hour) {
                if (!array_key_exists('category_name', $hour)) {
                    $hour['category_name'] = 'Unassigned';
                }
                if (!array_key_exists('total_hours', $hour)) {
                    $hour['total_hours'] = 0;
                }
                
                if (!array_key_exists($hour['category_id'], $return_hours)) {
                    $return_hours[$hour['category_id']] = 0;
                }
                
                $return_hours[$hour['category_id']] += $hour['total_hours'];
            }
        } else {
            foreach ($hours as $hour) {
                if (!array_key_exists('total_hours', $hour)) {
                    $hour['total_hours'] = 0;
                }
                
                $return_hours += $hour['total_hours'];
            }
        }
        
        return $return_hours;
    }
    
    /**
     * Purpose: Calculate the total quoted hours of a job
     */
    public function quoted_hours($job_id) {
        $job = $this->sys->db->query("SELECT `quoted_time` FROM `jobs` WHERE `job_id`=:id", array(
            ':id' => (int) $job_id
        ));
        
        if (!empty($job)) {
            $times = json_decode($job[0]['quoted_time'], true);
            $total_time = 0;
            
            foreach ($times as $time) {
                $total_time += $time;
            }
            
            return $total_time;
        }
        
        return false;
    }
    
    /**
     * Purpose: Get the hours worked for a job
     */
    protected function get_hours($job_id) {
        $hours = $this->sys->db->query("
            SELECT *
            FROM `job_punch` AS jobs JOIN `categories` AS categories on categories.category_id=jobs.category_id
            WHERE `job_id`=:job_id
            ORDER BY `date`, `punch_id` ASC
            ", array(
            ':job_id' => $job_id,
        ));
        
        $return_hours = array();
        
        array_walk($hours, function($row) use(&$return_hours) {
            if ('in' === $row['operation']) {
                $return_hours[$row['punch_id']]['in'] = $row['time'];
            } elseif ('out' === $row['operation']) {
                $return_hours[$row['punch_id']]['out'] = $row['time'];
            }
            
            $return_hours[$row['punch_id']]['total_hours'] = 0;
            $return_hours[$row['punch_id']]['employee'] = $row['employee_id'];
            $return_hours[$row['punch_id']]['job_id'] = $row['job_id'];
            $return_hours[$row['punch_id']]['date'] = $row['date'];
            $return_hours[$row['punch_id']]['category_id'] = $row['category_id'];
            $return_hours[$row['punch_id']]['category_name'] = $row['category_name'];
        });
        $return_hours = array_reverse($return_hours, true);

        $sys = $this->sys;
        array_walk($return_hours, function($hour, $punch_id) use(&$return_hours, $sys) {
            if (!is_integer($punch_id/2)) {
                $hour['in'] = $hour;

                if (!array_key_exists(($punch_id+1), $return_hours)) {
                    $return_hours[$punch_id+1]['out'] = '';
                    $hour['out'] = '';
                } else {
                    $hour['out'] = $return_hours[$punch_id+1];
                }
                
                if (empty($hour['in']) || empty($hour['out'])) {
                    return 0;
                }

                $in = (array_key_exists('in', $hour) && '' !== $hour['in'] && 0 != $hour['in']['in']) ? strtotime(date('g:ia', (float) $hour['in']['in'])) : 0;
                $out = (array_key_exists('out', $hour) && '' !== $hour['out'] && 0 != $hour['out']['out']) ? strtotime(date('g:ia', (float) $hour['out']['out'])) : 0;

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
                    
                    if (0 !== $in || 0 !== $out) {
                        $return_hours[$punch_id]['total_hours'] += round((($out/60 - $in/60)/60)/$round_by, $places)*$round_by;
                    }
                }
                
                if (!array_key_exists('total_hours', $return_hours[$punch_id])) {
                    $return_hours[$punch_id]['total_hours'] = 0;
                }
            }
        });
        
        return array_reverse($return_hours, true);
    } //End get_hours
    
    /**
     * Purpose: Used to punch in/out of a job
     */
    public function punch($job_id, $employee_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        $operation = '';
        $last_job = $this->sys->db->query("SELECT `punch_id`, `time`, `operation` FROM `job_punch` WHERE `job_id`=:job_id AND `employee_id`=:employee_id", array(
            ':job_id'       => $job_id,
            ':employee_id'  => (int) $employee_id
        ));
        $employee = $this->sys->db->query("SELECT `category_id` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $employee_id
        ));

        $temp_times = $last_job;
        $last_out = array_pop($temp_times);
        
        if (empty($last_job) || ('0' !== $last_out['time'])) {
            $operation = 'in';
            
            $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, :time, 'in')", array(
                ':job_id'       => $job_id,
                ':employee_id'  => (int) $employee_id,
                ':category_id'  => (int) $employee[0]['category_id'],
                ':date'         => $date_time['date'],
                ':time'         => $date_time['time']
            ));
            $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'out')", array(
                ':job_id'       => $job_id,
                ':employee_id'  => (int) $employee_id,
                ':category_id'  => (int) $employee[0]['category_id'],
                ':date'         => $date_time['date']
            ));
        } else {
            $operation = 'out';
            
            $this->sys->db->query("UPDATE `job_punch` SET `time`=:time WHERE `punch_id`=:id", array(
                ':id'       => (int) $last_out['punch_id'],
                ':time'         => $date_time['time']
            ));
        }
        
        $this->sys->db->query("UPDATE `jobs` SET `status`='wip' WHERE `job_uid`=:id", array(
            ':id' => $job_id
        ));
        
        return array($operation, $date_time);
    } //End punch
    
    /**
     * Purpose: Update the time for a specific job
     */
    protected function update_time() {
        if (array_key_exists('id', $_POST) && 'id' !== $_POST) {
            $operation = (array_key_exists('operation', $_POST) && 'in' == $_POST['operation']) ? 'in' : 'out';
            $time = (array_key_exists('time', $_POST)) ? $_POST['time'] : '';
            
            //Make sure punch_if exists
            $check_job = $this->sys->db->query("SELECT * FROM `job_punch` WHERE `punch_id`=:id", array(
                ':id' => (int) $_POST['id']
            ));
            
            if (!empty($check_job)) {
                if (!array_key_exists('time', $_POST) || (array_key_exists('time', $_POST) && '' === $_POST['time'])) {
                    $this->sys->db->query("DELETE FROM `job_punch` WHERE `punch_id`=:id", array(
                        ':id'           => (int) $check_job[0]['punch_id']
                    ));
                    
                    return true;
                }
                
                $this->sys->db->query("UPDATE `job_punch` SET `time`=:time WHERE `punch_id`=:id AND `operation`=:operation", array(
                    ':time'         => strtotime($time),
                    ':id'           => (int) $_POST['id'],
                    ':operation'    => $operation
                ));
                
                return true;
            } else {
                $id = (is_integer((int) $_POST['id']/2)) ? (int) $_POST['id']-1 : (int) $_POST['id']+1;
                $time = (array_key_exists('time', $_POST)) ? $_POST['time'] : '';
                $job_info = $this->sys->db->query("SELECT * FROM `job_punch` WHERE `punch_id`=:id", array(
                    ':id' => $id
                ));
                
                switch ($job_info[0]['operation']) {
                    case 'in':
                        $operation = 'out';
                        break;
                    default: //out
                        $operation = 'in';
                }
                
                $this->sys->db->query("
                    INSERT INTO `job_punch`
                        (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`)
                        VALUES (:punch_id, :job_id, :employee_id, :category_id, :date, :time, :operation)
                    ", array(
                    ':punch_id'     => (int) $_POST['id'],
                    ':job_id'       => $job_info[0]['job_id'],
                    ':employee_id'  => $job_info[0]['employee_id'],
                    ':category_id'  => $job_info[0]['category_id'],
                    ':date'         => $job_info[0]['date'],
                    ':time'         => strtotime($time),
                    ':operation'    => $operation
                ));
                
                return true;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Purpose: Used to update who worked that period, or which category worked that period
     */
    protected function update_time_info() {
        $id = array('in' => (int) $_POST['job_id'], 'out' => (int) $_POST['job_id']+1);
            
        foreach ($id as $job) {
            $check_job = $this->sys->db->query("SELECT `punch_id` FROM `job_punch` WHERE `punch_id`=:id", array(
                ':id' => $job
            ));
            
            if (!empty($check_job)) {
                $this->sys->db->query("UPDATE `job_punch` SET `employee_id`=:employee_id, `category_id`=:category_id WHERE `punch_id`=:punch_id", array(
                    ':employee_id'  => (int) $_POST['employee'],
                    ':category_id'  => (int) $_POST['category'],
                    ':punch_id'     => $job
                ));
            }
        }
        
        return true;
    }
    
    /**
     * Purpose: Used to add a worked date to a job
     */
    protected function add_date() {
        if ((array_key_exists('id', $_POST) && '' !== $_POST['id']) && (array_key_exists('employee', $_POST) && '' !== $_POST['employee'])) {
            //Check to see if job_id exists
            $check_job = $this->sys->db->query("SELECT `job_uid` FROM `jobs` WHERE `job_uid`=:id", array(
                ':id' => $_POST['id']
            ));
            
            //Check to see if employee exists
            $check_employee = $this->sys->db->query("SELECT `category_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => (int) $_POST['employee']
            ));

            if (!empty($check_job) && !empty($check_employee)) {
                $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'in')", array(
                    ':job_id'       => $_POST['id'],
                    ':employee_id'  => (int) $_POST['employee'],
                    ':category_id'  => (int) $check_employee[0]['category_id'],
                    ':date'         => $_POST['date']
                ));
                $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'out')", array(
                    ':job_id'       => $_POST['id'],
                    ':employee_id'  => (int) $_POST['employee'],
                    ':category_id'  => (int) $check_employee[0]['category_id'],
                    ':date'         => $_POST['date']
                ));
                $this->sys->db->query("UPDATE `jobs` SET `status`='wip' WHERE `job_uid`=:id", array(
                    ':id' => $_POST['id']
                ));
                
                return true;
            }
            
            $this->_add_date_response = '<script type="text/javascript">jQuery(document).ready(function () {add_date_response("Job ID doesn\'t exist.");});</script>';
            return false;
        } else {
            return false;
        }
    }
}

//End file
