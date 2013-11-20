<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/13/13
 * @Purpose: Various functions that apply to logged in users (or not logged in)
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $loggedIn = $this->load_model('loggedIn');
 */
class model_loggedIn {
    private static $_user;
    public static $_loginError;

    /**
     * @Purpose: Creates a constructor that sets various class variables
     * @Access: Public
     */
    public function __construct() {
        global $system_di;
        $this->system_di = $system_di;
        self::$_loginError = $this->login();
    } //End __construct

    /**
     * @Purpose: Used to check if there was an error logging in or not
     * @Access: Public
     */
    public function login_error() {
        return self::$_loginError;
    } //End login_error

    /**
     * @Purpose: Determins if a user is logged in or not
     * @Access: Public
     */
    public function status() {
        if (NULL !== self::$_user) {
            return True;
        }

        return False;
    } //end status

    /**
     * @Purpose: Used by this constructor to set class variables
     * @Access: Public
     */
    public function login() {
        if (isset($_POST['login'])) {
            $result = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username AND `employee_password`=:password",
                array(
                    ':username' => $_POST['username'],
                    ':password' => md5($_POST['password'])
                ));
            
            if (isset($result[0]['employee_id'])) {
                $this->system_di->session['user'] = $result[0]['employee_id'];
            } else {
                return True;
            }
        }
        if (isset($this->system_di->session['user']) && NULL !== $this->system_di->session['user']) {
            self::$_user = $this->system_di->session['user'];
        } else {
            self::$_user = NULL;
        }
    } //end login

    /**
     * @Purpose: Used to log a user out
     * @Access: Public
     */
    public function logout() {
        self::$_user = NULL;
        session_destroy();
    } //End logout
}//End model_loggedIn

//End File
