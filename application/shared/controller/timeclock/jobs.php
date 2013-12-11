<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/09/13
 * @Date Modified: 12/10/13
 * @Purpose: Controller for Jobs/Job Tracking
 * @Version: 1.0
 */

class timeclock_jobs extends controller {
    /**
     * @Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->sys->config->timeclock_subdirectories . '_' . $dependency);
            $this->sys->template->$name = $this->$name;
        }
    }
    
    /**
     * @Purpose: Used to generate a failed login attempt message
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
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
    }
    
    public function index($page_id=1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Jobs';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->page_id = (int) $page_id;
            $this->sys->template->paginate_by = $this->model_settings->paginate_by;
            $this->sys->template->jobs = $this->model_jobs->get_jobs();
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('jobs/home', 'jobs', (int) $page_id);
            
            $parse = 'jobs_home';
            $full_page = True;
        } else {
            $this->sys->template->title = 'TimeClock | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    public function add() {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'clients', 'jobs'));

        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Jobs | Add';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->clients = $this->model_clients->get_clients();
            
            $parse = 'jobs_add';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    public function edit($job_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'clients', 'settings', 'jobs'));

        if (!isset($this->sys->template->response)) {
            $this->sys->template->response = '';
        }

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Jobs | Edit';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->clients = $this->model_clients->get_clients();
            $this->sys->template->job = $this->model_jobs->get_jobs((int) $job_id);
            
            $parse = (false !== $this->sys->template->job) ? 'jobs_edit' : 'jobs_home';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    public function remove($job_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'jobs'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Jobs | Remove';
            $this->sys->template->jobs_active = 'class="active"';
            $this->sys->template->page_id = 1;
            $this->sys->template->paginate_by = $this->model_settings->paginate_by;
            $this->sys->template->jobs = $this->model_jobs->get_jobs();
            $this->sys->template->job = $this->model_jobs->get_jobs((int) $job_id);
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('jobs/home', 'jobs', 1);
            
            $parse = 'jobs_remove';
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
     * @Purpose: Used to view an existing jobs time card
     */
    public function view($job_id, $page_id = 1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'jobs', 'settings'));
        $this->sys->template->page_id = (int) $page_id;
        $this->sys->template->paginate_by = $this->model_settings->paginate_by;
        $this->sys->template->job_id = (int) $job_id;
        
        if ($this->is_logged_in()) {
            $this->sys->template->job = $this->model_jobs->get_jobs((int) $job_id);
            $this->sys->template->job_table = $this->model_jobs->generate_job_table((int) $job_id);
            $this->sys->template->start_date = $this->model_jobs->find_dates((int) $job_id, 'start');
            $this->sys->template->last_date = $this->model_jobs->find_dates((int) $job_id, 'end');
            $this->sys->template->add_date_response = '';
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('jobs/view/' . (int) $job_id . '', (int) $page_id);
            $this->sys->template->employees = $this->model_employees->get_employees();
            
            $this->sys->template->title = 'TimeClock | Jobs | View';
            $this->sys->template->jobs_active = 'class="active"';
            $parse = 'jobs_view';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * @Purpose: Function used to return you to the home controller if there is a problem
     */
    protected function load_home() {
        header('Location: ' . $this->sys->config->timeclock_root);
    }
}

//End file
