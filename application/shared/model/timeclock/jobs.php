<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/09/13
 * @Date Modified: 12/11/13
 * @Purpose: Used as a wrapper for various methods surrounding jobs
 * @Version: 2.0
 */
global $sys;
$sys->router->load_traits('jobs', 'timeclock');
 
 class model_timeclock_jobs {
    use job_timecard;
    
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    protected $_add_date_response = '';
    
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
        if (array_key_exists('update_time', $_POST)) {
            $this->update_time();
        }
        if (array_key_exists('add_date', $_POST)) {
            $this->add_date();
        }
        if (array_key_exists('update_info', $_POST)) {
            $this->update_time_info();
        }
    }
    
    /**
     * Database Modification - Add/Edit/Remove
     */
    /**
     * @Purpose: Used to add jobs to the database
     */
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
            $client = $this->sys->db->query("SELECT `client_name` FROM `clients` WHERE `client_id`=:client_id", array(
                'client_id' => $check_job[0]['client']
            ));
            
            if (!empty($check_job)) {
                $error .= 'That UID is already in use by the job ' . $check_job[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
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
            $this->sys->template->meta = array('1', $this->sys->config->timeclock_root . 'jobs');
            return true;
        }
    }
    
    /**
     * @Purpose: Used to edit jobs in the database
     */
    protected function edit() {
        $error = '';
        
        $check_job = $this->sys->db->query("SELECT `job_uid` FROM `jobs` WHERE `job_id`=:id", array(
            ':id' => (int) $_POST['id']
        ));
        
        if (empty($check_job)) {
            $error = '<p>That job doesn\'t exist.</p>';
        }
        if (!array_key_exists('client', $_POST) || '' === $_POST['client']) {
            $error .= '<p>Please select a client.</p>';
        }
        if (!array_key_exists('job_name', $_POST) || '' === $_POST['job_name']) {
            $error .= '<p>Please enter a job name.</p>';
        }
        if ((!array_key_exists('uid', $_POST) || '' === $_POST['uid']) && !array_key_exists('generate_uid', $_POST)) {
            $error .= '<p>Please either enter a UID or select \'Generate ID.\'</p>';
        }
        
        if (array_key_exists('generate_uid', $_POST) && (!array_key_exists('uid', $_POST) || '' === $_POST['uid'])) {
            do {
                $uid = mt_rand(0, 99999);
                
                $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                    ':id' => $uid
                ));
            } while (!empty($query));
            
        } else {
            $uid = $_POST['uid'];
            
            if ($check_job[0]['job_uid'] != $_POST['uid']) {
                $check_uid = $this->sys->db->query("SELECT `job_name`, `client` FROM `jobs` WHERE `job_uid`=:uid", array(
                    ':uid' => $uid
                ));
                
                if (!empty($check_uid)) {
                    $client = $this->sys->db->query("SELECT `client_name` FROM `clients` WHERE `client_id`=:client_id", array(
                        ':client_id' => $check_uid[0]['client']
                    ));
                    
                    $error .= 'That UID is already in use by the job ' . $check_uid[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
                    $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
                    return false;
                }
            }
        }
        
        switch ($_POST['status']) {
            case 'na':
                $status = 'na';
                break;
            case 'wip':
                $status = 'wip';
                break;
            default: //c
                $status = 'c';
        }
        
        $edit_job = ('' === $error) ? $this->sys->db->query("UPDATE `jobs` SET `job_uid`=:uid, `job_name`=:name, `client`=:client, `status`=:status WHERE `job_id`=:id", array(
            ':id'       => (int) $_POST['id'],
            ':uid'      => $uid,
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client'],
            ':status'   => $status
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Job Updated Successfully</div>';
            $this->sys->template->meta = array('0', $this->sys->config->timeclock_root . 'jobs');
            return true;
        }
    } //End edit
    
    /**
     * @Purpose: Used to remove jobs from the database
     */
    protected function remove() {
        $params = array(':id' => $_POST['job_id']);
        
        $check_job = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_id`=:id", $params);
        
        if (!empty($check_job)) {
            $this->sys->db->query("DELETE FROM `jobs` WHERE `job_id`=:id", $params);
        }
        
        header('Location: ' . $this->sys->config->timeclock_root . 'jobs');
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * @Purpose: Returns a list of all the jobs
     */
    public function get_jobs($job_id='all', $paginate=true) {
        if (true === $paginate && 'all' === $job_id) {
            $start = ((1 >= $this->sys->template->page_id)) ? 0 : (int) ($this->sys->template->page_id-1) * $this->sys->template->paginate_by;
            $end = $this->sys->template->paginate_by;
            $limit = 'LIMIT ' . $start . ',' . $end;
        } else {
            $limit = '';
        }
        
        switch ($this->sys->template->model_settings->sort_jobs_by) {
            case 'job_name':
                $sort_by = 'jobs.job_name, clients.client_name';
                break;
            case 'client_name':
                $sort_by = 'clients.client_name, jobs.job_uid';
                break;
            default: //job_id
                $sort_by = 'jobs.job_uid, jobs.job_name';
        }
        if ('all' !== $job_id) {
            $jobs = $this->sys->db->query("
                SELECT *
                FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client
                WHERE jobs.job_uid=:id
                ORDER BY $sort_by $limit
            ", array(
                ':id' => $job_id
            ));

            if (!empty($jobs)) {
                $jobs = $jobs[0];
            } else {
                return false;
            }
        } else {
            $jobs = $this->sys->db->query("SELECT * FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client ORDER BY $sort_by $limit");
        }

        return $jobs;
    }
    
    /**
     * @Purpose: Used to find the first and last dates something happened to a job
     */
    public function find_dates($job_id, $date_to_get) {
        if ('start' === $date_to_get) {
            $query = $this->sys->db->query("SELECT `date` FROM `job_punch` WHERE `job_id`=:id ORDER BY `date` LIMIT 1", array(
                ':id' => $job_id
            ));
        } else { //end
            $query = $this->sys->db->query("SELECT `date` FROM `job_punch` WHERE `job_id`=:id ORDER BY `date` DESC LIMIT 1", array(
                ':id' => $job_id
            ));
        }
        
        return (!empty($query)) ? $query[0]['date'] : '';
    }
    
     /**
     * @Purpose: Used to create the table that is seen in the view.
     */
    public function generate_job_table($job_id) {
        $hours = $this->get_hours($job_id);
        $job_query = $this->sys->db->query("
            SELECT * FROM `job_punch` AS punch
                JOIN `employees` AS employees on employees.employee_id=punch.employee_id
                LEFT JOIN `categories` AS categories on categories.category_id=employees.category_id
            WHERE punch.job_id=:id ORDER BY punch.date", array(':id' => $job_id));
        $job_info = array();
        
        foreach ($job_query as $temp) {
            $job_info[$temp['employee_id']] = $temp;
        }
        $return = '';
        
        if (!is_bool($hours)) {
            foreach ($hours as $punch_id=>$hour) {
                if (!is_integer($punch_id/2)) {
                    $id['in'] = $punch_id;
                    $id['out'] = $punch_id+1;
                    $hour['in'] = $hour;
                    $hour['out'] = (array_key_exists(($id['out']), $hours)) ? $hours[$id['out']] : array('out' => '');
                } else {
                    $id['in'] = $punch_id-1;
                    $id['out'] = $punch_id;
                    $hour['in'] = (array_key_exists(($id['in']), $hours)) ? NULL : array('in' => '');
                    $hour['out'] = $hour;
                }
                
                if (NULL !== $hour['in']) {
                    $in = (!empty($hour['in']['in'])) ? date($this->_timeFormat, (int) $hour['in']['in']) : '';
                    $out = (!empty($hour['out']['out'])) ? date($this->_timeFormat, (int) $hour['out']['out']) : '';
                    
                    $return .= '<td>' . $hour['date'] . '</td>';
                    $return .= '<td onclick="updateJobTime(\'' . $id['in'] . '\', jQuery(this), \'in\')">' . $in . '</td>';
                    $return .= '<td onclick="updateJobTime(\'' . $id['out'] . '\', jQuery(this), \'out\')">' . $out . '</td>';
                    $return .= '<td onclick="updateJobInfo(\'' . $id['in'] . '\', ' . $job_info[$hour['employee']]['employee_id'] . ', ' . $hour['category_id'] . ')">' . $job_info[$hour['employee']]['employee_firstname'] . ', ' . $job_info[$hour['employee']]['employee_lastname'] . '</td>';
                    $return .= '<td onclick="updateJobInfo(\'' . $id['in'] . '\', ' . $job_info[$hour['employee']]['employee_id'] . ', ' . $hour['category_id'] . ')">' . $hour['category_name'] . '</td>';
                    $return .= '<td>' . $hour['total_hours'] . '</td>';
                    $return .= '</tr>';
                }
            }
        }
        
        return $return;
    }
 }

//End file
