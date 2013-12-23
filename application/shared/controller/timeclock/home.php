<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/13/13
 * Date Modified: 12/23/13
 * Purpose: Default controller - used for top level pages (home, about, settings, etc)
 * Version: 2.0
 */

class timeclock_home extends controller {
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
     * Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
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
     * Purpose: Used to get & return employees to the view
     */
    protected function employees() {
        $this->load_dependencies(array('employees'));
        $this->model_employees->get_employees_for_view();

        return True;
    }

    /**
     * Purpose: Default function to be run when class is called
     */
    public function index($page_id=1) {
        $table = $this->sys->db->query("SELECT `employee_id` FROM `employees` LIMIT 0,1");
        if (false === $table || empty($table)) {
            $this->install();
            return true;
        }
        
        $this->load_dependencies(array('renderPage', 'settings'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->page_id = (1 > $page_id) ? 1 : (int) $page_id;
            $this->sys->template->paginate_by = $this->model_settings->paginate_by;
            $this->employees();
            $this->sys->template->list_employees_as = $this->model_settings->list_employees_as;
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('main', 'employees', (int) $page_id);

            $this->sys->template->title = 'TimeClock | Home';
            $this->sys->template->home_active = 'class="active"';
            
            $parse = ($this->sys->template->admin) ? 'admin_home' : 'home';
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
     * Purpose: Used to load a help page
     */
    public function about() {
        $this->load_dependencies(array('renderPage', 'settings'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->title = 'TimeClock | About';
            $this->sys->template->about_active = 'class="active"';
            $parse = ($this->sys->template->admin) ? 'admin_about' : 'about';
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
     * Purpose: Used to load the settings page
     */
    public function settings() {
        $this->load_dependencies(array('renderPage', 'settings'));
        $this->login_failed();
        
        $this->sys->template->update_status = $this->model_settings->update_status();
        
        if ($this->is_logged_in()) {
            if (!$this->model_settings->is_admin()) {
                $this->index();
                return true;
            }
            
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->pay_period_start = $this->model_settings->pay_period_start;
            $this->sys->template->pay_period_end = $this->model_settings->pay_period_end;
            $this->sys->template->round_time_by = $this->model_settings->round_time_by;
            $this->sys->template->paginate_by = (NULL !== $this->model_settings->paginate_by) ? $this->model_settings->paginate_by : 10;
            $this->sys->template->sort_employees_by = $this->model_settings->sort_employees_by;
            $this->sys->template->list_employees_as = $this->model_settings->list_employees_as;
            $this->sys->template->sort_jobs_by = $this->model_settings->sort_jobs_by;

            $this->sys->template->title = 'TimeClock | Settings';
            $this->sys->template->settings_active = 'class="active"';
            $parse = 'settings';
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
     * Purpose: Used for the initial installation of TimeClock
     */
    public function install() {
        $this->load_dependencies(array('renderPage', 'install', 'settings'));
        $this->login_failed();
        $this->sys->template->title = 'TimeClock | Installation';
        $this->sys->template->round_time_by = $this->model_settings->round_time_by;
        $this->sys->template->paginate_by = (NULL !== $this->model_settings->paginate_by) ? $this->model_settings->paginate_by : 10;
        $this->sys->template->sort_employees_by = $this->model_settings->sort_employees_by;
        $this->sys->template->list_employees_as = $this->model_settings->list_employees_as;
        $parse = 'install_main';
        
        $employee = $this->sys->db->query("SELECT `employee_id` FROM `employees` LIMIT 0,1");
        if (!empty($employee)) {
            $this->sys->template->title = 'TimeClock | Sign In';
            $parse = 'login';
        }

        $this->model_renderPage->parse($parse, False);
    }
    
    /**
     * Purpose: Returns the current_time according to the server
     */
    public function current_time() {
        $this->sys->template->response = date('g:ia', time());
        
        $this->sys->template->parse($this->sys->config->timeclock_subdirectories . '_payperiod_response');
    }
} //End timeclock_home

//End File
