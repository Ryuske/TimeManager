<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/21/13
 * @Purpose: Logout controller
 * @Version: 1.0
 */

/**
 * @Purpose: Logout Controller
 */
class timeclock_logout extends controller {
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->logged_in->status();
    }

    /**
     * @Purpose: Default function to be run when class is called
     */
    public function index() {
        $renderPage = $this->load_model('renderPage', $this->system_di->config->timeclock_subdirectories);
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        $this->system_di->template->login_failed = '';

        if ($this->is_logged_in()) {
            $this->logged_in->logout();
        }

        $this->system_di->template->title = 'TimeClock | Sign In';
        $parse = 'login';
        $full_page = False;

        //Parses the HTML from the view
        $renderPage->parse($parse, $full_page);
    }
}

//End File
