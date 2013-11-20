<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/15/13
 * @Purpose: Logout controller
 * @Version: 1.0
 */

/**
 * @Purpose: Logout Controller
 * @Extends controller
 */
class logout extends controller {
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     * @Access: Public
     */
    protected function is_logged_in() {
        return $this->logged_in->status();
    } //End logged_in

    /**
     * @Purpose: Default function to be run when class is called
     * @Access: Public
     */
    public function index() {
        $renderPage = $this->load_model('renderPage');
        $this->logged_in = $this->load_model('loggedIn');
        $this->system_di->template->login_failed = '';

        if ($this->is_logged_in()) {
            $this->logged_in->logout();
        }

        $this->system_di->template->title = 'TimeClock | Sign In';
        $parse = 'login';
        $full_page = False;

        //Parses the HTML from the view
        $renderPage->parse($parse, $full_page);
    }//End index
}//End logout

//End File
