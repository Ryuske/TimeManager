<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/09/13
 * Date Modified: 12/18/13
 * Purpose: Controller for Jobs/Job Tracking
 * Version: 2.0
 */

class timeclock_jobs extends controller {
    /**
     * Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->sys->config->timeclock_subdirectories . '_' . $dependency);
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
     * Purpose: Loads the home page for jobs
     */
    public function index($page_id=1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->admin         = $this->model_settings->is_admin();
            $this->sys->template->title         = 'TimeClock | Jobs';
            $this->sys->template->jobs_active   = 'class="active"';
            $this->sys->template->page_id       = (int) $page_id;
            $this->sys->template->paginate_by   = $this->model_settings->paginate_by;
            $this->sys->template->jobs          = $this->model_jobs->get();
            $this->sys->template->pagination    = $this->model_renderPage->generate_pagination('jobs/home', 'jobs', (int) $page_id);
            
            $parse = ($this->sys->template->admin) ? 'admin_jobs_home' : 'jobs_home';
            $full_page = True;
        } else {
            $this->sys->template->title = 'TimeClock | Sign In';
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'clients', 'jobs', 'categories'));

        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in()) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->categories = $this->model_categories->get();
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->title = 'TimeClock | Jobs | Add';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->clients = $this->model_clients->get();
            
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'clients', 'settings', 'jobs', 'categories'));
        $this->sys->template->job = $this->model_jobs->get($job_id, false);
        
        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in() && $this->sys->template->job) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->categories = $this->model_categories->get();
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->title = 'TimeClock | Jobs | Edit';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->clients = $this->model_clients->get();
            
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
    public function remove($job_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->sys->template->job = $this->model_jobs->get($job_id, false);

        if ($this->is_logged_in() && $this->sys->template->job) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->admin         = $this->model_settings->is_admin();
            $this->sys->template->title         = 'TimeClock | Jobs | Remove';
            $this->sys->template->jobs_active   = 'class="active"';
            $this->sys->template->page_id       = 1;
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'categories', 'jobs', 'settings'));
        $this->sys->template->page_id       = (int) $page_id;
        $this->sys->template->paginate_by   = $this->model_settings->paginate_by;
        $this->sys->template->job_id        = $job_uid;
        
        if ($this->is_logged_in()) {
            $this->sys->template->admin             = $this->model_settings->is_admin();
            $this->sys->template->job               = $this->model_jobs->get($job_uid, false);
            $this->sys->template->job_table         = $this->model_jobs->generate_job_table($job_uid);
            $this->sys->template->start_date        = $this->model_jobs->find_dates($job_uid, 'start');
            $this->sys->template->last_date         = $this->model_jobs->find_dates($job_uid, 'end');
            $this->sys->template->add_date_response = '';
            $this->sys->template->pagination        = $this->model_renderPage->generate_pagination('jobs/view/' . $job_uid . '', (int) $page_id);
            $this->sys->template->employees         = $this->model_employees->get();
            $this->sys->template->categories        = $this->model_categories->get(true);
            $this->sys->template->total_hours       = $this->model_jobs->total_hours($job_uid, false);
            $this->sys->template->hours_by_category = $this->model_jobs->total_hours($job_uid, true);
            $this->sys->template->worked_load       = $this->model_jobs->work_load($job_uid, false, true);
            $this->sys->template->quoted_load       = $this->model_jobs->work_load($job_uid, true, true);
            
            $this->sys->template->title = 'TimeClock | Jobs | View';
            $this->sys->template->jobs_active = 'class="active"';
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'categories', 'jobs', 'settings'));
        $this->sys->template->job_id = $job_uid;
        if ($this->is_logged_in()) {
            $this->sys->template->job               = $this->model_jobs->get($job_uid);
            $this->sys->template->job_table         = $this->model_jobs->generate_job_table($job_uid);
            $this->sys->template->start_date        = $this->model_jobs->find_dates($job_uid, 'start');
            $this->sys->template->last_date         = $this->model_jobs->find_dates($job_uid, 'end');
            $this->sys->template->employees         = $this->model_employees->get();
            $this->sys->template->categories        = $this->model_categories->get();
            $this->sys->template->total_hours       = $this->model_jobs->total_hours($job_uid, false);
            $this->sys->template->hours_by_category = $this->model_jobs->total_hours($job_uid, true);
            
            $this->sys->template->title = 'TimeClock | Jobs | Print';
            $parse = 'jobs_print';
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
                LEFT JOIN `categories` AS category on category.category_id=punch.category_id
            WHERE job.job_uid=:job_uid
            ORDER BY punch.punch_id DESC
        ", array(
            ':job_uid'      => $job_uid
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
                case 'last_category':
                    $response = $check_ids[0]['category_name'];;
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
        
        $this->sys->template->parse($this->sys->config->timeclock_subdirectories . '_jobs_response');
    }
    /**
     * Purpose: Used to punch in/out on a job
     */
    public function tx($job_uid, $employee_uid) {
        $this->load_dependencies(array('jobs'));
        $response = 'Error';
        
        $check_employee_id = $this->sys->db->query("SELECT `employee_uid` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => $employee_uid
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
            ':job_uid'      => $job_uid,
            ':employee_uid' => $employee_uid
        ));

        if (!empty($check_ids)) {
            $response = $this->model_jobs->punch($check_ids[0]['job_uid'], $check_ids[0]['employee_id']);
            $response = $check_ids[0]['employee_firstname'] . ' ' . $check_ids[0]['employee_lastname'] . ', ' . $check_ids[0]['job_uid'] . ', ' . $check_ids[0]['job_name'] . ', ' . $response[0] . ', ' . $response[1]['time'];
        }
        
        $this->sys->template->response = $response;
        
        $this->sys->template->parse($this->sys->config->timeclock_subdirectories . '_jobs_response');
    }
    
    /**
     * Purpose: Function used to return you to the home controller if there is a problem
     */
    protected function load_home() {
        header('Location: ' . $this->sys->config->timeclock_root);
    }
}

//End file
