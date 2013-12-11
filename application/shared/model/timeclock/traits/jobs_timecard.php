<?php
trait job_timecard {
    protected function total_hours() {
        
    }
    
    protected function get_hours($job_id) {
        $hours = $this->sys->db->query("SELECT `punch_id`, `job_id`, `employee_id`, `date`, `time`, `operation` FROM `job_punch` WHERE `job_id`=:job_id ORDER BY `date` ASC", array(
            ':job_id' => (int) $job_id,
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
        });
        $return_hours = array_reverse($return_hours, true);

        $sys = $this->sys;
        array_walk($return_hours, function($hour, $punch_id) use(&$return_hours, $sys) {
            if (!is_integer($punch_id/2)) {
                $hour['in'] = $hour;
                $hour['out'] = $return_hours[$punch_id+1];
                
                if (!array_key_exists('in', $hour)) {
                    $return_hours[$punch_id]['in'] = '';
                    $hour['in'] = '';
                }
                if (!array_key_exists('out', $hour)) {
                    $return_hours[$punch_id+1]['out'] = '';
                    $hour['out'] = '';
                }
                
                if (empty($hour['in']) || empty($hour['out'])) {
                    return 0;
                }
                
                $in = (array_key_exists('in', $hour) && '' !== $hour['in']) ? strtotime(date('g:ia', (float) $hour['in']['in'])) : 0;
                $out = (array_key_exists('out', $hour) && '' !== $hour['out']) ? strtotime(date('g:ia', (float) $hour['out']['out'])) : 0;
                
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
                    
                    $return_hours[$punch_id]['total_hours'] += round((($out/60 - $in/60)/60)/$round_by, $places)*$round_by;
                }
                
                if (!array_key_exists('total_hours', $return_hours[$punch_id])) {
                    $return_hours[$punch_id]['total_hours'] = 0;
                }
            }
        });
        
        return array_reverse($return_hours, true);
    } //End get_hours
    
    protected function punch($job_id, $employee_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        $operation = '';
        $last_job = $this->sys->db->query("SELECT `punch_id`, `time`, `operation` FROM `job_punch` WHERE `job_id`=:job_id AND `employee_id`=:employee_id", array(
            ':job_id'       => (int) $job_id,
            ':employee_id'  => (int) $employee_id
        ));
        $employee = $this->sys->db->query("SELECT `category_id` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $employee_id
        ));

        $temp_times = $last_job;
        $last_out = array_pop($temp_times);
        
        if (empty($last_job) || ('0' !== $last_out['time'])) {
            $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, :time, 'in')", array(
                ':job_id'       => (int) $job_id,
                ':employee_id'  => (int) $employee_id,
                ':category_id'  => (int) $employee[0]['category_id'],
                ':date'         => $date_time['date'],
                ':time'         => $date_time['time']
            ));
            $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'out')", array(
                ':job_id'       => (int) $job_id,
                ':employee_id'  => (int) $employee_id,
                ':category_id'  => (int) $employee[0]['category_id'],
                ':date'         => $date_time['date']
            ));
        } else {
            $this->sys->db->query("UPDATE `job_punch` SET `time`=:time WHERE `punch_id`=:id", array(
                ':id'       => (int) $last_out['punch_id'],
                ':time'         => $date_time['time']
            ));
        }
        
        $this->sys->db->query("UPDATE `jobs` SET `status`='wip' WHERE `job_id`=:id", array(
            ':id' => (int) $job_id
        ));
        
        return array($operation, $date_time);
    } //End punch
    
    protected function update_time() {
        if (array_key_exists('id', $_POST) && 'id' !== $_POST) {
            $operation = (array_key_exists('operation', $_POST) && 'in' == $_POST['operation']) ? 'in' : 'out';
            $time = (array_key_exists('time', $_POST)) ? $_POST['time'] : '';
            $delete = true;
            
            //Make sure punch_if exists
            $check_job = $this->sys->db->query("SELECT * FROM `job_punch` WHERE `punch_id`=:id", array(
                ':id' => (int) $_POST['id']
            ));
            
            if (!empty($check_job)) {
                foreach ($check_job as $job) {
                    if (0 !== (int) $job['time'] && (array_key_exists('time', $_POST) && '' !== $_POST['time'])) {
                        $delete = false;
                    }
                }
                
                if ($delete) {;
                    $this->sys->db->query("DELETE FROM `job_punch` WHERE `punch_id`=:id", array(
                        ':id'           => (int) $check_job[0]['punch_id']
                    ));
                    $this->sys->db->query("DELETE FROM `job_punch` WHERE `punch_id`=:id", array(
                        ':id'           => (int) $check_job[0]['punch_id']+1
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
                return false;
            }
        } else {
            return false;
        }
    }
    
    protected function add_date() {
        if ((array_key_exists('id', $_POST) && '' !== $_POST['id']) && (array_key_exists('employee', $_POST) && '' !== $_POST['employee'])) {
            //Check to see if job_id exists
            $check_job = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_id`=:id", array(
                ':id' => (int) $_POST['id']
            ));
            
            //Check to see if employee exists
            $check_employee = $this->sys->db->query("SELECT `category_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => (int) $_POST['employee']
            ));

            if (!empty($check_job) && !empty($check_employee)) {
                $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'in')", array(
                    ':job_id'       => (int) $_POST['id'],
                    ':employee_id'  => (int) $_POST['employee'],
                    ':category_id'  => (int) $check_employee[0]['category_id'],
                    ':date'         => $_POST['date']
                ));
                $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, '', 'out')", array(
                    ':job_id'       => (int) $_POST['id'],
                    ':employee_id'  => (int) $_POST['employee'],
                    ':category_id'  => (int) $check_employee[0]['category_id'],
                    ':date'         => $_POST['date']
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
