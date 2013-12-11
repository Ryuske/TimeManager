<?php
trait job_timecard {
    protected function total_hours() {
        
    }
    
    protected function get_hours($job_id) {
        $hours = $this->sys->db->query("SELECT `date`, `time`, `operation` FROM `job_punch` WHERE `job_id`=:job_id ORDER BY `date` ASC", array(
            ':job_id' => (int) $job_id,
        ));
        
        $return_hours = array();
        
        array_walk($hours, function($row) use(&$return_hours) {
            if ('in' === $row['operation']) {
                $return_hours[$row['date']]['in'] = $row['time'];
            } elseif ('out' === $row['operation']) {
                $return_hours[$row['date']]['out'] = $row['time'];
            }
            
            $return_hours[$row['date']]['total_hours'] = 0;
        });
        
        $sys = $this->sys;
        array_walk($return_hours, function($hour, $date) use(&$return_hours, $sys) {
            if (!array_key_exists('in', $hour)) {
                $return_hours[$date]['in'] = '';
                $hour['in'] = '';
            }
            if (!array_key_exists('out', $hour)) {
                $return_hours[$date]['out'] = '';
                $hour['out'] = '';
            }
            
            if (empty($hour['in']) || empty($hour['out'])) {
                return 0;
            }

            $in = (array_key_exists('in', $hour)) ? strtotime(date('g:ia', $hour['in'])) : 0;
            $out = (array_key_exists('out', $hour)) ? strtotime(date('g:ia', $hour['out'])) : 0;

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
            
            if (!array_key_exists('total_hours', $return_hours[$date])) {
                $return_hours[$date]['total_hours'] = 0;
            }
        });
        
        return $return_hours;
    }
    
    protected function punch($job_id, $employee_id) {
        $date_time = array('date' => date($this->_dateFormat), 'time' => time());
        $operation = '';
        $last_job = $this->sys->db->query("SELECT `operation` FROM `job_punch` WHERE `job_id`=:job_id AND `employee_id`=:employee_id", array(
            ':job_id'       => (int) $job_id,
            ':employee_id'  => (int) $employee_id
        ));
        $employee = $this->sys->db->query("SELECT `category_id` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $employee_id
        ));
        
        if (empty($last_job)) {
            $this->punch_in($job_id, $employee_id);
        } else {
            switch ($last_job[0]['operation']) {
                case 'in':
                    $operation = 'out';
                    break;
                default: //out
                    $operation = 'in';
            }
        }
        
        $this->sys->db->query("INSERT INTO `job_punch` (`punch_id`, `job_id`, `employee_id`, `category_id`, `date`, `time`, `operation`) VALUES (NULL, :job_id, :employee_id, :category_id, :date, :time, :operation)", array(
            ':job_id'       => (int) $job_id,
            ':employee_id'  => (int) $employee_id,
            ':category_id'  => (int) $employee[0]['category_id'],
            ':date'         => $date_time['date'],
            ':time'         => $date_time['time'],
            ':operation'    => $operation
        ));
        $this->sys->db->query("UPDATE `jobs` SET `status`='wip' WHERE `job_id`=:id", array(
            ':id' => (int) $job_id
        ));
        
        return array($operation, $date_time);
    }
    
    protected function add_date() {
        
    }
}

//End file
