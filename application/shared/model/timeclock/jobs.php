<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/09/13
 * Date Modified: 12/19/13
 * Purpose: Used as a wrapper for various methods surrounding jobs
 * Version: 2.0
 */

global $sys;
$sys->router->load_helpers('traits', 'jobs', 'timeclock');
$sys->router->load_helpers('interfaces', 'general', 'timeclock');
 
class model_timeclock_jobs implements general_actions {
    use job_timecard;
    
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    protected $_add_date_response = '';
    
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        $is_admin = $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $this->sys->session['user']
        ));
        
        if (!empty($is_admin)) {
            $is_admin = ('admin' === $is_admin[0]['employee_role']) ? true : false;
        } else {
            $is_admin = false;
        }
        
        if (array_key_exists('add_job', $_POST) && $is_admin) {
            $this->add();
        }
        if (array_key_exists('edit_job', $_POST) && $is_admin) {
            $this->edit();
        }
        if (array_key_exists('remove_job', $_POST) && $is_admin) {
            $this->remove();
        }
        if (array_key_exists('update_time', $_POST) && $is_admin) {
            $this->update_time();
        }
        if (array_key_exists('add_date', $_POST) && $is_admin) {
            $this->add_date();
        }
        if (array_key_exists('update_info', $_POST) && $is_admin) {
            $this->update_time_info();
        }
    }
    
    /**
     * Database Modification - Add/Edit/Remove
     */
    /**
     * Purpose: Used for checking $_POST inputs
     */
    public function check_input($method) {
        $error = '';
        
        if ('remove' !== $method) {
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
                    $_POST['uid'] = mt_rand(0, 99999);
                    
                    $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                        ':id' => $_POST['uid']
                    ));
                } while (!empty($query));
                
            } else {
                $client = $this->sys->db->query("SELECT client.client_name, job.job_name FROM `clients` AS client LEFT JOIN `jobs` AS job on job.job_uid=:uid WHERE client.client_id=job.client", array(
                    ':uid' => $_POST['uid']
                ));
                
                if (!empty($check_job)) {
                    $error .= 'That UID is already in use by the job ' . $check_job[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
                }
            }
            
            if (array_key_exists('quote', $_POST)) {
                $categories_query = $this->sys->db->query("SELECT * FROM `categories`");
                $categories = array();
                
                foreach ($categories_query as $category) {
                    $categories[$category['category_id']] = $category;
                }
                
                $categories_query = array();
                
                foreach ($_POST['quote'] as $id=>&$time) {
                    if (array_key_exists($id, $categories)) {
                        if ('' === $time) {
                            $time = 0;
                        }
                        
                        $categories_query[$id] = $time;
                    }
                }
                $_POST['quote'] = json_encode($categories_query);
            } else {
                $error .= '<p>Please enter quoted times.</p>';
            }
        }
        
        switch ($method) {
            case 'add':
                break;
            case 'edit':
                if (array_key_exists('id', $_POST)) {
                    $check_job = $this->sys->db->query("SELECT `job_uid` FROM `jobs` WHERE `job_id`=:id", array(
                        ':id' => (int) $_POST['id']
                    ));
                    
                    if (empty($check_job)) {
                        $error = '<p>That job doesn\'t exist.</p>';
                    }
                } else {
                    $error = '<p>That job doesn\'t exist.</p>';
                }
                
                switch ($_POST['status']) {
                    case 'na':
                        $_POST['status'] = 'na';
                        break;
                    case 'wip':
                        $_POST['status'] = 'wip';
                        break;
                    default: //c
                        $_POST['status'] = 'c';
                }
                break;
            case 'remove':
                if (array_key_exists('job_id', $_POST)) {
                    $check_job = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_id`=:id", array(
                        ':id' => (int) $_POST['job_id']
                    ));
                    
                    if (empty($check_job)) {
                        $error = '<p>That job doesn\'t exist.</p>';
                    }
                } else {
                    $error = '<p>That job doesn\'t exist.</p>';
                }
                break;
            default:
                return false;
        }

        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return true;
        }
            
        return false;
    }
    
    /**
     * Purpose: Used to add jobs to the database
     */
    public function add() {
        $error = $this->check_input('add');

        $this->sys->db->query("INSERT INTO `jobs` (`job_id`, `job_uid`, `job_name`, `client`, `status`, `quoted_time`) VALUES (NULL, :id, :name, :client, 'na', :quote)", array(
            ':id'       => $_POST['uid'],
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client'],
            ':quote'    => $_POST['quote']
        ));
        
        if (!$error) {
            $this->sys->template->response = '<div class="form_success">Job Added Successfully</div>';
            $this->sys->template->meta = array('1', $this->sys->config->timeclock_root . 'jobs');
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Used to edit jobs in the database
     */
    public function edit() {
        $error = $this->check_input('edit');
        
        $this->sys->db->query("UPDATE `jobs` SET `job_uid`=:uid, `job_name`=:name, `client`=:client, `status`=:status, `quoted_time`=:quote WHERE `job_id`=:id", array(
            ':id'       => (int) $_POST['id'],
            ':uid'      => $_POST['uid'],
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client'],
            ':status'   => $_POST['status'],
            ':quote'    => $_POST['quote']
        ));
        
        if (!$error) {
            $this->sys->template->response = '<div class="form_success">Job Updated Successfully</div>';
            $this->sys->template->meta = array('0', $this->sys->config->timeclock_root . 'jobs');
            return true;
        }
        
        return false;
    } //End edit
    
    /**
     * Purpose: Used to remove jobs from the database
     */
    public function remove() {
        $error = $this->check_input('remove');
        
        if (!$error) {
            $this->sys->db->query("DELETE FROM `jobs` WHERE `job_id`=:id", array(
                ':id' => (int) $_POST['job_id']
            ));
        }
        
        header('Location: ' . $this->sys->config->timeclock_root . 'jobs');
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * Purpose: Returns a list of all the jobs
     */
    public function get($action='all', $paginate=true) {
        if (true === $paginate && 'all' === $action) {
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
        if ('all' !== $action) {
            $jobs = $this->sys->db->query("
                SELECT *
                FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client
                WHERE jobs.job_uid=:id
                ORDER BY $sort_by $limit
            ", array(
                ':id' => $action
            ));

            if (!empty($jobs)) {
                $jobs = $jobs[0];
                $jobs['quoted_time'] = json_decode($jobs['quoted_time'], true);
            } else {
                return false;
            }
        } else {
            $jobs = $this->sys->db->query("SELECT * FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client ORDER BY $sort_by $limit");
            foreach ($jobs as &$job) {
                $job['quoted_time'] = json_decode($job['quoted_time'], true);
            }
        }

        return $jobs;
    }
    
    /**
     * Purpose: Used to find the first and last dates something happened to a job
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
     * Purpose: Used to create the table that is seen in the view.
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
    
    /**
     * Purpose: Used to calculate the load a job;
     */
    public function work_load($job_uid, $quoted_load=true, $by_category=false) {
        $load_time = ($quoted_load) ? $this->get($job_uid, false) : $this->total_hours($job_uid, true);
        $zero_load = 0;
        
        if (array_key_exists('quoted_time', $load_time)) {
            $load_time = $load_time['quoted_time'];
        }
        
        $total_load = 0;
        $total_employees = 0;
        $load = array();
        $employees = array();
        
        foreach ($load_time as $category=>$time) {
            $category_employees = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `category_id`=:category_id", array(
                ':category_id' => $category
            ));
            
            $employees[$category] = count($category_employees);
            if (0 < $employees[$category]) {
                $load[$category] = round($time/($employees[$category]*8), 2);
            } else {
                $load[$category] = 0;
            }
        }
        
        if ($by_category) {
            foreach ($load as &$category_load) {
                if (0 === $category_load) {
                    $category_load = 100;
                }
            }
            return $load;
        }
        
        foreach ($load as $category_load) {
            $total_load += $category_load;
            if (0 < $total_load) {
                $zero_load = 1;
            }
        }
        
        $total_load = (0 === $zero_load) ? 100 : $total_load;
        return $total_load;
    }
 }

//End file
