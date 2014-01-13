<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/09/13
 * Date Modified: 1/9/14
 * Purpose: Controller for Jobs/Job Tracking
 */

class timemanager_jobs extends controller {
    /**
     * Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->sys->config->timemanager_subdirectories . '_' . $dependency);
            $this->sys->template->$name = $this->$name;
        }
    }
    
    /**
     * Purpose: Used to generate a failed login attempt message
     */
    protected function login_failed() {
        $this->load_dependencies(array('loggedIn'));
        
        if ($this->model_loggedIn->login_error()) {
            $this->sys->template->login_failed = '<div class="form_failed">Incorrect Username or Password</div>';
            return True;
        }

        $this->sys->template->login_failed = '';
    }
    
    /**
     * Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
    }
    
    /**
     * Purpose: Used to get attachments (can be used in ajax call)
     */
    public function get_attachments($job_id) {
        $this->load_dependencies(array('loggedIn', 'jobs'));
        
        if ($this->is_logged_in()) {
            echo json_encode($this->model_jobs->get_attachments($job_id));
        }
    }
    
    /**
     * Purpose: Used to remove attachments (can be used in ajax call)
     */
    public function remove_attachment($attachment_id) {
        $this->load_dependencies(array('loggedIn', 'settings', 'jobs'));
        
        if ($this->is_logged_in() && $this->model_settings->is_admin()) {
            echo $this->model_jobs->remove_attachment($attachment_id);
        } else {
            echo 'false';
        }
    }
    
    /**
     * Purpose: Loads the home page for jobs; Also has information about job quotes
     */
    public function index($page_id=1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->admin                 = $this->model_settings->is_admin();
            $this->sys->template->title                 = 'Time Manager | Jobs | Quotes';
            $this->sys->template->jobs_quoting_active   = 'class="active"';
            $this->sys->template->page_id               = (int) $page_id;
            $this->sys->template->paginate_by           = $this->model_settings->paginate_by;
            $this->sys->template->jobs                  = $this->model_jobs->get();
            $this->sys->template->pagination            = $this->model_renderPage->generate_pagination('jobs/home', 'jobs', (int) $page_id);
            $this->sys->template->response              = ('' !== $this->sys->template->response) ? $this->sys->template->response : '';
            
            $parse = ($this->sys->template->admin) ? 'admin_jobs_home' : 'jobs_home';
            $full_page = True;
        } else {
            $this->sys->template->title = 'Time Manager | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    /**
     * Purpose: Used to view/modify the quote of a job
     */
    public function quote($job_uid) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'jobs', 'departments', 'quotes', 'settings'));
        $this->sys->template->job_uid = $job_uid;
        
        if ($this->is_logged_in()) {
            $this->sys->template->admin                 = $this->model_settings->is_admin();
            $this->sys->template->quote                 = $this->model_quotes->get($job_uid);
            $this->sys->template->departments           = $this->model_departments->get(true);
            $this->sys->template->title                 = 'Time Manager | Jobs | Quote';
            $this->sys->template->jobs_quoting_active   = 'class="active"';
            
            $this->model_renderPage->parse('jobs_quote', true);
        } else {
            $this->load_home();
        }
    }
    
    /**
     * Purpose: Loads page that has information about job tracking
     */
    public function tracking($page_id=1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->admin                 = $this->model_settings->is_admin();
            $this->sys->template->title                 = 'Time Manager | Jobs | Tracking';
            $this->sys->template->jobs_tracking_active  = 'class="active"';
            $this->sys->template->page_id               = (int) $page_id;
            $this->sys->template->paginate_by           = $this->model_settings->paginate_by;
            $this->sys->template->jobs                  = $this->model_jobs->get('tracking');
            $this->sys->template->pagination            = $this->model_renderPage->generate_pagination('jobs/tracking', 'jobs', (int) $page_id);
            $this->sys->template->response              = ('' !== $this->sys->template->response) ? $this->sys->template->response : '';
            
            $parse = ($this->sys->template->admin) ? 'admin_jobs_tracking' : 'jobs_tracking';
            $full_page = True;
        } else {
            $this->sys->template->title = 'Time Manager | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    /**
     * Purpose: Load add jobs page
     */
    public function add() {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'clients', 'jobs', 'departments', 'quotes'));

        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in()) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->departments           = $this->model_departments->get();
            $this->sys->template->admin                 = $this->model_settings->is_admin();
            $this->sys->template->title                 = 'Time Manager | Jobs | Add';
            $this->sys->template->jobs_quoting_active   = 'class="active"';
            $this->sys->template->clients               = $this->model_clients->get();
            
            if (array_key_exists('job_id', $_POST)) {
                $this->model_quotes->add();
            }
            
            $parse = 'jobs_add';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    /**
     * Purpose: Load edit jobs page
     */
    public function edit($job_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'clients', 'settings', 'jobs', 'departments'));
        $this->sys->template->job = $this->model_jobs->get($job_id, false);
        
        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in() && $this->sys->template->job) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->departments   = $this->model_departments->get();
            $this->sys->template->admin         = $this->model_settings->is_admin();
            $this->sys->template->title         = 'Time Manager | Jobs | Edit';
            $this->sys->template->jobs_active   = 'class="active"';
            $this->sys->template->clients       = $this->model_clients->get();
            
            $parse = (false !== $this->sys->template->job) ? 'jobs_edit' : 'jobs_home';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    /**
     * Purpose: Load remove jobs page
     */
    public function remove($page_id, $job_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->sys->template->job = $this->model_jobs->get($job_id, false);

        if ($this->is_logged_in() && $this->sys->template->job) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->admin         = $this->model_settings->is_admin();
            $this->sys->template->title         = 'Time Manager | Jobs | Remove';
            $this->sys->template->jobs_active   = 'class="active"';
            $this->sys->template->page_id       = (int) $page_id;
            $this->sys->template->paginate_by   = $this->model_settings->paginate_by;
            $this->sys->template->jobs          = $this->model_jobs->get();
            $this->sys->template->pagination    = $this->model_renderPage->generate_pagination('jobs/home', 'jobs', 1);
            
            $parse = 'jobs_remove';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    /**
     * Purpose: Used to view an existing jobs time card
     */
    public function view($job_uid, $page_id = 1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'departments', 'jobs', 'settings'));
        $this->sys->template->page_id       = (int) $page_id;
        $this->sys->template->paginate_by   = $this->model_settings->paginate_by;
        $this->sys->template->job_id        = $job_uid;
        
        if ($this->is_logged_in()) {
            $this->sys->template->admin                 = $this->model_settings->is_admin();
            $this->sys->template->job                   = $this->model_jobs->get($job_uid, false);
            $this->sys->template->job_table             = $this->model_jobs->generate_job_table($job_uid);
            $this->sys->template->start_date            = $this->model_jobs->find_dates($job_uid, 'start');
            $this->sys->template->last_date             = $this->model_jobs->find_dates($job_uid, 'end');
            $this->sys->template->add_date_response     = '';
            $this->sys->template->pagination            = $this->model_renderPage->generate_pagination('jobs/view/' . $job_uid . '', (int) $page_id);
            $this->sys->template->employees             = $this->model_employees->get();
            $this->sys->template->departments           = $this->model_departments->get(true);
            $this->sys->template->total_hours           = $this->model_jobs->total_hours($job_uid, false);
            $this->sys->template->hours_by_department   = $this->model_jobs->total_hours($job_uid, true);
            $this->sys->template->worked_load           = $this->model_jobs->work_load($job_uid, false, true);
            $this->sys->template->quoted_load           = $this->model_jobs->work_load($job_uid, true, true);
            
            $this->sys->template->title = 'Time Manager | Jobs | View';
            $parse = ($this->sys->template->admin) ? 'admin_jobs_view' : 'jobs_view';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, true);
    }
    
    /**
     * Purpose: Used to generate a printer friendly version of a jobs time
     */
    public function print_friendly($job_uid) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'departments', 'jobs', 'settings'));
        $this->sys->template->job_id = $job_uid;
        
        if ($this->is_logged_in()) {
            $this->sys->template->job                   = $this->model_jobs->get($job_uid, false);
            $this->sys->template->job_table             = $this->model_jobs->generate_job_table($job_uid);
            $this->sys->template->start_date            = $this->model_jobs->find_dates($job_uid, 'start');
            $this->sys->template->last_date             = $this->model_jobs->find_dates($job_uid, 'end');
            $this->sys->template->employees             = $this->model_employees->get();
            $this->sys->template->departments           = $this->model_departments->get(true);
            $this->sys->template->total_hours           = $this->model_jobs->total_hours($job_uid, false);
            $this->sys->template->hours_by_department   = $this->model_jobs->total_hours($job_uid, true);
            $this->sys->template->worked_load           = $this->model_jobs->work_load($job_uid, false, true);
            $this->sys->template->quoted_load           = $this->model_jobs->work_load($job_uid, true, true);
            
            $this->sys->template->title = 'Time Manager | Jobs | Print';
            $parse = 'jobs_print';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, false);
    }
    
    /**
     *
     */
    public function print_tracking() {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'departments', 'jobs', 'settings'));
        
        if ($this->is_logged_in()) {
            $this->sys->template->title                 = 'Time Manager | Jobs | Tracking | Print';
            $this->sys->template->jobs_tracking_active  = 'class="active"';
            $this->sys->template->paginate_by           = $this->model_settings->paginate_by;
            $this->sys->template->jobs                  = $this->model_jobs->get('tracking');
            
            $parse = 'jobs_print-tracking';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, false);
    }
    
    /**
     * Purpose: Used to recieve information about a job
     */
    public function rx($job_uid, $data) {
        $this->load_dependencies(array('settings', 'jobs'));
        $response = 'Error';
        
        //Check job & employee exist
        $check_ids = $this->sys->db->query("
            SELECT *
            FROM `jobs` AS job
                JOIN `job_punch` AS punch on punch.job_id=:job_uid
                LEFT JOIN `employees` AS employee on employee.employee_id=punch.employee_id
                LEFT JOIN `clients` AS client on client.client_id=job.client
                LEFT JOIN `departments` AS department on department.department_id=punch.department_id
            WHERE job.job_uid=:job_uid
            ORDER BY punch.punch_id DESC
        ", array(
            ':job_uid' => substr($job_uid, 0, 256)
        ));
        
        if (!empty($check_ids)) {
            switch ($data) {
                case 'name':
                    $response = $check_ids[0]['job_name'];
                    break;
                case 'client':
                    $response = $check_ids[0]['client_name'];
                    break;
                case 'last_time':
                    $response = date('g:ia', $check_ids[0]['time']);
                    break;
                case 'last_date':
                    $response = $check_ids[0]['date'];
                    break;
                case 'last_department':
                    $response = $check_ids[0]['department_name'];;
                    break;
                case 'last_employee':
                    $response = $check_ids[0]['employee_firstname'] . ' ' . $check_ids[0]['employee_lastname'];
                    break;
                case 'total_hours':
                    $response = $this->model_jobs->total_hours($job_uid, false);
                    break;
                default:
                    $response = NULL;
            }
        }
        
        $this->sys->template->response = $response;
        
        $this->sys->template->parse($this->sys->config->timemanager_subdirectories . '_jobs_response');
    }
    /**
     * Purpose: Used to punch in/out on a job
     */
    public function tx($job_uid, $employee_uid, $department='default') {
        $this->load_dependencies(array('jobs'));
        $response = 'Error';
        
        $check_employee_id = $this->sys->db->query("SELECT `employee_uid` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($employee_uid, 0, 6)
        ));
        
        if (!empty($check_employee_id)) {
            $employee_uid = $check_employee_id[0]['employee_uid'];
        }
        
        //Check job & employee exist
        $check_ids = $this->sys->db->query("
            SELECT *
            FROM `jobs` AS job JOIN `employees` AS employee on employee.employee_uid=:employee_uid
            WHERE job.job_uid=:job_uid
        ", array(
            ':job_uid'      => substr($job_uid, 0, 256),
            ':employee_uid' => substr($employee_uid, 0, 64)
        ));

        if (!empty($check_ids)) {
            $response = $this->model_jobs->punch($check_ids[0]['job_uid'], $check_ids[0]['employee_id'], $department);
            $response = $check_ids[0]['employee_firstname'] . ' ' . $check_ids[0]['employee_lastname'] . ', ' . $check_ids[0]['job_uid'] . ', ' . $check_ids[0]['job_name'] . ', ' . $response[0] . ', ' . $response[1]['time'];
        }
        
        $this->sys->template->response = $response;
        
        $this->sys->template->parse($this->sys->config->timemanager_subdirectories . '_jobs_response');
    }
    
    /**
     * Purpose: Function used to return you to the home controller if there is a problem
     */
    protected function load_home() {
        header('Location: ' . $this->sys->config->timemanager_root);
    }
}

//End file
