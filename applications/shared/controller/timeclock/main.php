<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/21/13
 * @Purpose: Default controller for a website
 * @Version: 1.0
 */

/**
 * @Purpose: Default controller for a website
 */
class timeclock_main extends controller {
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->logged_in->status();
    }

    /**
     * @Purpose: Used to generate a failed login attempt message
     */
    protected function login_failed() {
        if ($this->logged_in->login_error()) {
            $this->system_di->template->login_failed = '<div class="form_failed">Incorrect Username or Password</div>';
            return True;
        }

        $this->system_di->template->login_failed = '';
    }

    /**
     * @Purpose: Used to get & return employees to the view
     */
    protected function employees() {
        $this->employees = $this->load_model('employees', $this->system_di->config->timeclock_subdirectories);
        $this->employees->get_employees_for_view();

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
        $renderPage = $this->load_model('renderPage', $this->system_di->config->timeclock_subdirectories);
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        $this->login_failed();

        if ($this->is_logged_in()) {
            $this->employees();

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
        $renderPage->parse($parse, $full_page);
    }
    
    /**
     * @Purpose: Used to load a help page
     */
    public function about() {
        $renderPage = $this->load_model('renderPage', $this->system_di->config->timeclock_subdirectories);
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
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
        $renderPage->parse($parse, $full_page);
    }
    
    /**
     * @Purpose: Used to load the settings page
     */
    public function settings() {
        $renderPage = $this->load_model('renderPage', $this->system_di->config->timeclock_subdirectories);
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        $this->login_failed();
        
        $this->settings = $this->load_model('settings', $this->system_di->config->timeclock_subdirectories);
        $this->system_di->template->update_status = $this->settings->update_status();
        
        if ($this->is_logged_in()) {
            $this->employees();

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
        $renderPage->parse($parse, $full_page);
    }
} //End timeclock_main

//End File
