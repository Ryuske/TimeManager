<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/09/13
 * Date Modified: 1/8/14
 * Purpose: Used as a wrapper for various methods surrounding jobs
 */

global $sys;
$sys->router->load_helpers('traits', 'jobs', 'timemanager');
$sys->router->load_helpers('interfaces', 'general', 'timemanager');
 
class model_timemanager_jobs implements general_actions {
    use job_timecard;
    
    protected $_dateFormat = 'm/d/y';
    protected $_timeFormat = 'g:ia';
    protected $_add_date_response = '';
    
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        $is_admin = $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($this->sys->session['user'], 0, 6)
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
        if (array_key_exists('upload_attachment', $_POST) && $is_admin) {
            $this->upload_attachment();
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
            if (!array_key_exists('job_quantity', $_POST) || '' === $_POST['job_quantity']) {
                $error .= '<p>Please enter a quantity.</p>';
            }
            if (!array_key_exists('job_start_date', $_POST) || '' === $_POST['job_start_date']) {
                $error .= '<p>Please enter a start date for the job.</p>';
            }
            if (!array_key_exists('job_due_date', $_POST) || '' === $_POST['job_due_date']) {
                $error .= '<p>Please enter a due date for the job.</p>';
            }
            if ((!array_key_exists('uid', $_POST) || '' === $_POST['uid']) && !array_key_exists('generate_uid', $_POST)) {
                $error .= '<p>Please either enter a UID or select \'Generate ID.\'</p>';
            }
            
            if (array_key_exists('generate_uid', $_POST) && (!array_key_exists('uid', $_POST) || '' === $_POST['uid'])) {
                do {
                    $_POST['uid'] = mt_rand(0, 99999);
                    
                    $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                        ':id' => substr($_POST['uid'], 0, 256)
                    ));
                } while (!empty($query));
                
            } else {
                $client = $this->sys->db->query("SELECT client.client_name, job.job_name FROM `clients` AS client LEFT JOIN `jobs` AS job on job.job_uid=:uid WHERE client.client_id=job.client", array(
                    ':uid' => substr($_POST['uid'], 0, 256)
                ));
                
                if (!empty($check_job)) {
                    $error .= 'That UID is already in use by the job ' . $check_job[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
                }
            }
            
            if (array_key_exists('quote', $_POST)) {
                $departments_query = $this->sys->db->query("SELECT * FROM `departments`");
                $departments = array();
                
                foreach ($departments_query as $department) {
                    $departments[$department['department_id']] = $department;
                }
                
                $departments_query = array();
                
                foreach ($_POST['quote'] as $id=>&$time) {
                    if (array_key_exists($id, $departments)) {
                        if ('' === $time) {
                            $time = 0;
                        }
                        
                        $departments_query[$id] = $time;
                    }
                }
                $_POST['quote'] = json_encode($departments_query);
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
                        ':id' => (int) substr($_POST['id'], 0, 10)
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
                        ':id' => (int) substr($_POST['job_id'], 0, 10)
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

        $this->sys->db->query("INSERT INTO `jobs` (`job_id`, `job_uid`, `job_name`, `client`, `job_quantity`, `job_start_date`, `job_due_date`, `status`, `quoted_time`) VALUES (NULL, :id, :name, :client, :quantity, :start_date, :due_date, 'na', :quote)", array(
            ':id'           => substr($_POST['uid'], 0, 256),
            ':name'         => ucwords(strtolower(substr($_POST['job_name'], 0, 256))),
            ':client'       => (int) substr($_POST['client'], 0, 6),
            ':quantity'     => (int) substr($_POST['job_quantity'], 0, 6),
            ':start_date'   => substr($_POST['job_start_date'], 0, 8),
            ':due_date'     => substr($_POST['job_due_date'], 0, 8),
            ':quote'        => $_POST['quote']
        ));
        
        if (!$error) {
            $this->sys->template->response = '<div class="form_success">Job Added Successfully</div>';
            $this->sys->template->meta = array('1', $this->sys->config->timemanager_root . 'jobs');
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Used to edit jobs in the database
     */
    public function edit() {
        $error = $this->check_input('edit');
        
        $this->sys->db->query("UPDATE `jobs` SET `job_uid`=:uid, `job_name`=:name, `client`=:client, `job_quantity`=:quantity, `job_start_date`=:start_date, `job_due_date`=:due_date, `status`=:status, `quoted_time`=:quote WHERE `job_id`=:id", array(
            ':id'           => (int) substr($_POST['id'], 0, 10),
            ':uid'          => substr($_POST['uid'], 0, 256),
            ':name'         => ucwords(strtolower(substr($_POST['job_name'], 0, 256))),
            ':client'       => (int) substr($_POST['client'], 0, 6),
            ':quantity'     => (int) substr($_POST['job_quantity'], 0, 6),
            ':start_date'   => substr($_POST['job_start_date'], 0, 8),
            ':due_date'     => substr($_POST['job_due_date'], 0, 8),
            ':status'       => $_POST['status'],
            ':quote'        => $_POST['quote']
        ));
        
        if (!$error) {
            $this->sys->template->response = '<div class="form_success">Job Updated Successfully</div>';
            $this->sys->template->meta = array('0', $this->sys->config->timemanager_root . 'jobs');
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
            $files = $this->sys->db->query("SELECT `file_path` FROM `files` WHERE `job_id`=:id", array(
                ':id' => (int) substr($_POST['job_id'], 0, 10)
            ));
            $directory = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/';
            
            foreach ($files as $file) {
                unlink($directory . $file['file_path']);
            }

            $this->sys->db->query("DELETE FROM `jobs` WHERE `job_id`=:id", array(
                ':id' => (int) substr($_POST['job_id'], 0, 10)
            ));
        }
        
        Header('Location: ' . $this->sys->config->timemanager_root . 'jobs');
    }
    
    /**
     * Purpose: Used to upload files/attachments to jobs
     */
    public function upload_attachment() {
        $finfo = new finfo(FILEINFO_MIME_TYPE, "/usr/share/misc/magic");
        $this->sys->template->response = '';
        $allowed_files = array(
            'image/gif',
            'image/jpeg',
            'image/jpg',
            'image/pjpeg',
            'image/x-png',
            'image/png',
            'image/x-autocad',
            'image/x-dwg',
            'drawing/x-dxf',
            'application/pdf',
            'application/octet-stream',
            'application/acad',
            'application/x-autocad',
            'application/dxf',
            'application/x-dxf',
            'application/zip',
            'multipart/x-zip'
        );

        if (!array_key_exists('job_id', $_POST) || '' === $_POST['job_id']) {
            $this->sys->template->response .= '<p>Unknown job ID.</p>';
        }

        if ('' === $this->sys->template->response) {
            $job = $this->sys->db->query("SELECT `job_id`, `job_uid` FROM `jobs` WHERE `job_uid`=:job_id", array(
                ':job_id' => (int) substr($_POST['job_id'], 0, 5)
            ));

            if (empty($job)) {
                $this->sys->template->response .= '<p>Unknown job ID.</p>';
            } else {
                $this->sys->template->job_id .= $job[0]['job_uid']; 
                $_POST['job_id'] = $job[0]['job_id'];
            }
            
            if (0 === $_FILES['attachment']['error'] && '' === $this->sys->template->response) {
                if (in_array($finfo->file($_FILES['attachment']['tmp_name']), $allowed_files)) {
                    $extension = pathinfo($_FILES['attachment']['name']);
                    $extension = '.' . $extension['extension'];
                    $directory = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/';
                    $file = '';
                    
                    do {
                        $file = substr(md5(rand(0, 9999)), 0, 5);
                        $file .= $extension;
                    } while (is_file($directory . $file));
                    
                    move_uploaded_file($_FILES['attachment']['tmp_name'], $directory . $file);

                    $this->sys->db->query("INSERT INTO `files` (`file_id`, `job_id`, `file_path`, `file_name`) VALUES (NULL, :job_id, :path, :name)", array(
                        ':job_id'   => (int) substr($_POST['job_id'], 0, 10),
                        ':path'     => $file,
                        ':name'     => substr($_FILES['attachment']['name'], 0, 100)
                    ));
                    
                    $this->sys->template->response .= '<p>Uploaded Successfully</p>';
                } else {
                    $this->sys->template->response .= '<p>Unsupported filetype.</p>';
                }
            } else {
                $this->sys->template->response .= '<p>Error uploading file.</p>';
            }
        }
    } //End upload_attachment
    
    /**
     * Purpose: Used to remove attachments
     */
    public function remove_attachment($attachment_id) {
        $attachment = $this->sys->db->query("SELECT `file_path` FROM `files` WHERE `file_id`=:file_id", array(
            ':file_id' => (int) substr($attachment_id, 0, 20)
        ));
        if (empty($attachment)) {
            return 'false';
        }
        
        $this->sys->db->query("DELETE FROM `files` WHERE `file_id`=:file_id", array(
            ':file_id' => (int) substr($attachment_id, 0, 20)
        ));
        
        $directory = dirname($_SERVER["SCRIPT_FILENAME"]) . '/uploads/';
        unlink($directory . $attachment[0]['file_path']);
        
        return 'true';
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
        
        if ('tracking' === $action) {
            $jobs = $this->sys->db->query("
                SELECT *
                FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client
                WHERE jobs.status <> 'c'
                ORDER BY convert(jobs.job_due_date, date) DESC, $sort_by $limit");

            foreach ($jobs as &$job) {
                $job['quoted_time'] = json_decode($job['quoted_time'], true);
            }
        } else if ('all' !== $action) {
            $jobs = $this->sys->db->query("
                SELECT *
                FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client
                WHERE jobs.job_uid=:id
                ORDER BY $sort_by $limit
            ", array(
                ':id' => substr($action, 0, 256)
            ));

            if (!empty($jobs)) {
                $jobs = $jobs[0];
                $jobs['quoted_time'] = json_decode($jobs['quoted_time'], true);
            } else {
                return false;
            }
        } else {
            $jobs = $this->sys->db->query("
                SELECT *
                FROM `jobs` AS jobs JOIN `clients` AS clients on clients.client_id = jobs.client
                ORDER BY $sort_by $limit");
            foreach ($jobs as &$job) {
                $job['quoted_time'] = json_decode($job['quoted_time'], true);
            }
        }

        return $jobs;
    }
    
    /**
     * Purpose: Used to get attachments associated to a job
     */
    public function get_attachments($job_id) {
        $attachments = $this->sys->db->query("
            SELECT file.file_id, file.file_name, file.file_path
            FROM `files` as file
                JOIN `jobs` as job ON job.job_uid=:job_id
            WHERE file.job_id=job.job_id",
        array(
            ':job_id' => substr($job_id, 0, 256)
        ));
        $files = array();
        
        foreach ($attachments as $attachment) {
            $files[$attachment['file_id']] = array(
                'name' => $attachment['file_name'],
                'path' => $this->sys->config->timemanager_uploads . $attachment['file_path']
            );
        }
        
        return $files;
    }
    
    /**
     * Purpose: Used to find the first and last dates something happened to a job
     */
    public function find_dates($job_id, $date_to_get) {
        if ('start' === $date_to_get) {
            $query = $this->sys->db->query("SELECT `date` FROM `job_punch` WHERE `job_id`=:id ORDER BY `date` LIMIT 1", array(
                ':id' => (int) substr($job_id, 0, 10)
            ));
        } else { //end
            $query = $this->sys->db->query("SELECT `date` FROM `job_punch` WHERE `job_id`=:id ORDER BY `date` DESC LIMIT 1", array(
                ':id' => (int) substr($job_id, 0, 10)
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
                LEFT JOIN `departments` AS departments on departments.department_id=employees.department_id
            WHERE punch.job_id=:id ORDER BY punch.date", array(':id' => (int) substr($job_id, 0, 10)));
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
                    $return .= '<td onclick="updateJobInfo(\'' . $id['in'] . '\', ' . $job_info[$hour['employee']]['employee_id'] . ', ' . $hour['department_id'] . ')">' . $job_info[$hour['employee']]['employee_firstname'] . ', ' . $job_info[$hour['employee']]['employee_lastname'] . '</td>';
                    $return .= '<td onclick="updateJobInfo(\'' . $id['in'] . '\', ' . $job_info[$hour['employee']]['employee_id'] . ', ' . $hour['department_id'] . ')">' . $hour['department_name'] . '</td>';
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
    public function work_load($job_uid, $quoted_load=true, $by_department=false) {
        $load_time = ($quoted_load) ? $this->get($job_uid, false) : $this->total_hours($job_uid, true);
        $zero_load = 0;
        
        if (array_key_exists('quoted_time', $load_time)) {
            $load_time = $load_time['quoted_time'];
        }
        
        $total_load = 0;
        $total_employees = 0;
        $load = array();
        $employees = array();
        
        foreach ($load_time as $department=>$time) {
            $department_employees = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `department_id`=:department_id", array(
                ':department_id' => (int) substr($department, 0, 4)
            ));
            
            $employees[$department] = count($department_employees);
            if (0 < $employees[$department]) {
                $load[$department] = round($time/($employees[$department]*8), 2);
            } else {
                $load[$department] = 0;
            }
        }
        
        if ($by_department) {
            foreach ($load as &$department_load) {
                if (0 === $department_load) {
                    $department_load = 100;
                }
            }
            return $load;
        }
        
        foreach ($load as $department_load) {
            $total_load += $department_load;
            if (0 < $total_load) {
                $zero_load = 1;
            }
        }
        
        $total_load = (0 === $zero_load) ? 100 : $total_load;
        return $total_load;
    }
    
    /**
     * Purpose: Used to figure out what the last department worked on a job was
     */
    public function last_operation($job_id) {
        $last_department = $this->sys->db->query("SELECT department.department_name FROM `job_punch` AS job LEFT JOIN `departments` AS department on department.department_id=job.department_id WHERE job.job_id=:id ORDER BY job.time ASC LIMIT 0,1", array(
            ':id' => (int) $job_id
        ));
        
        $response = (!empty($last_department)) ? $last_department[0]['department_name'] : 'n/a';
        
        return $response;
    }
 }

//End file
