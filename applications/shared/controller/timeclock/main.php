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
 * @Extends controller
 */
class timeclock_main extends controller {
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     * @Access: Protected
     */
    protected function is_logged_in() {
        return $this->logged_in->status();
    } //End logged_in

    /**
     * @Purpose: Used to generate a failed login attempt message
     * @Access: Protected
     */
    protected function login_failed() {
        if ($this->logged_in->login_error()) {
            $this->system_di->template->login_failed = '<div class="form_failed">Incorrect Username or Password</div>';
            return True;
        }

        $this->system_di->template->login_failed = '';
    } //End login_failed

    /**
     * @Purpose: Used to get & return employees to the view
     * @Access: Protected
     */
    protected function employees() {
        $this->employees = $this->load_model('employees', $this->system_di->config->timeclock_subdirectories);
        $this->employees->get_employees_for_view();

        return True;
    } //End employees

    /**
     * @Purpose: Used to make echo'ing out the values in the view a little "prettier"
     * @Access: Public
     */
    public static function writeout($employee=NULL, $employee_value=NULL) {
        global $system_di;
        echo $system_di->template->employee[$employee]['employee_' . $employee_value];
    } //End writeout

    /**
     * @Purpose: Default function to be run when class is called
     * @Access: Public
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
    }//End index
    
    /**
     * @Purpose: Used to load a help page
     * @Access: Public
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
    } //End about
}//End timeclock_main

//End File
