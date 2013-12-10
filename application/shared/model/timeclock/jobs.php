<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/09/13
 * @Date Modified: 12/09/13
 * @Purpose: Used as a wrapper for various methods surrounding jobs
 * @Version: 1.0
 */

 class model_timeclock_jobs {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        if (array_key_exists('add_job', $_POST)) {
            $this->add();
        }
        if (array_key_exists('edit_job', $_POST)) {
            $this->edit();
        }
        if (array_key_exists('remove_job', $_POST)) {
            $this->remove();
        }
    }
    
    protected function add() {
        $error = '';
        
        if (!array_key_exists('client', $_POST) || '' === $_POST['client']) {
            $error = '<p>Please select a client.</p>';
        }
        if (!array_key_exists('job_name', $_POST) || '' === $_POST['job_name']) {
            $error .= '<p>Please enter a job name.</p>';
        }
        if ((!array_key_exists('uid', $_POST) || '' === $_POST['uid']) && !array_key_exists('generate_uid', $_POST)) {
            $error .= '<p>Please either enter a UID or select \'Generate ID.\'</p>';
        }
        
        if (array_key_exists('generate_uid', $_POST) && (!array_key_exists('uid', $_POST) || '' === $_POST['uid'])) {
            do {
                $id = mt_rand(0, 99999);
                
                $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                    ':id' => $id
                ));
            } while (!empty($query));
            
        } else {
            $id = $_POST['uid'];
            
            $check_job = $this->sys->db->query("SELECT `job_name`, `client` FROM `jobs` WHERE `job_uid`=:id", array(
                ':id' => $id
            ));
            
            if (!empty($check_job)) {
                $error .= 'That UID is already in use by the job ' . $check_job[0]['job_name'] . ' for ' . $check_job[0]['client'] . '.';
                $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
                return false;
            }
        }
        
        $add_job = ('' === $error) ? $this->sys->db->query("INSERT INTO `jobs` (`job_id`, `job_uid`, `job_name`, `client`, `status`) VALUES (NULL, :id, :name, :client, 'na')", array(
            ':id'       => $id,
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client']
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Job Added Successfully</div>';
            return true;
        }
    }
    
    protected function edit() {
        
    }
    
    protected function remove() {
        
    }
 }

//End file
