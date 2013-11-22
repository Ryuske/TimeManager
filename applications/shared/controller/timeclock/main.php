<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/22/13
 * @Purpose: Default controller for a website
 * @Version: 1.0
 */

/**
 * @Purpose: Default controller - used for top level pages (home, about, settings, etc)
 */
class timeclock_main extends controller {
    /**
     * @Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->system_di->config->timeclock_subdirectories . '_' . $dependency);
            $this->system_di->template->$name = $this->$name;
        }
    }
    
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
    }

    /**
     * @Purpose: Used to generate a failed login attempt message
     */
    protected function login_failed() {
        $this->load_dependencies(array('loggedIn'));
        
        if ($this->model_loggedIn->login_error()) {
            $this->system_di->template->login_failed = '<div class="form_failed">Incorrect Username or Password</div>';
            return True;
        }

        $this->system_di->template->login_failed = '';
    }

    /**
     * @Purpose: Used to get & return employees to the view
     */
    protected function employees() {
        $this->load_dependencies(array('employees'));
        $this->model_employees->get_employees_for_view();

        return True;
    }
    
    /**
     * @Purpose: Used to make echo'ing out the values in the view a little "prettier"
     */
    public static function writeout($employee=NULL, $employee_value=NULL) {
        global $system_di;
        echo $system_di->template->employee[$employee]['employee_' . $employee_value];
    }

    /**
     * @Purpose: Default function to be run when class is called
     */
    public function index() {
        $this->load_dependencies(array('renderPage', 'settings'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->employees();
            $this->system_di->template->list_employees_as = $this->model_settings->list_employees_as;

            $this->system_di->template->title = 'TimeClock | Home';
            $this->system_di->template->home_active = 'class="active"';
            $parse = 'home';
            $full_page = True;
        } else {
            $this->system_di->template->title = 'TimeClock | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    /**
     * @Purpose: Used to load a help page
     */
    public function about() {
        $this->load_dependencies(array('renderPage'));
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->employees();

            $this->system_di->template->title = 'TimeClock | About';
            $this->system_di->template->about_active = 'class="active"';
            $parse = 'about';
            $full_page = True;
        } else {
            $this->system_di->template->title = 'TimeClock | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
    
    /**
     * @Purpose: Used to load the settings page
     */
    public function settings() {
        $this->load_dependencies(array('renderPage', 'settings'));
        $this->login_failed();
        
        $this->system_di->template->update_status = $this->model_settings->update_status();
        
        if ($this->is_logged_in()) {
            $this->employees();
            
            $this->system_di->template->pay_period_start = $this->model_settings->pay_period_start;
            $this->system_di->template->pay_period_end = $this->model_settings->pay_period_end;
            $this->system_di->template->round_time_by = $this->model_settings->round_time_by;
            $this->system_di->template->sort_employees_by = $this->model_settings->sort_employees_by;
            $this->system_di->template->list_employees_as = $this->model_settings->list_employees_as;

            $this->system_di->template->title = 'TimeClock | Settings';
            $this->system_di->template->settings_active = 'class="active"';
            $parse = 'settings';
            $full_page = True;
        } else {
            $this->system_di->template->title = 'TimeClock | Sign In';
            $parse = 'login';
            $full_page = False;
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, $full_page);
    }
} //End timeclock_main

//End File
