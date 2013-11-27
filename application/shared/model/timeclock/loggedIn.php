<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/27/13
 * @Purpose: Various functions that apply to logged in users (or not logged in)
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $loggedIn = $this->load_model('loggedIn'); //Brings the model into scope & also tries to log a user in
 *
 *      $this->sys->template->login_error = $loggedIn->login_error();
 *          Allows you to return the login error to the view
 *          
 *      $loggedIn->status()
 *          Returns True or False depending on if you're logged in. Usually used within an if statement.
 *          
 *      $loggedIn->logout()
 *          Logs a user out 
 *
 */
class model_timeclock_loggedIn {
    private static $_user;
    public static $_loginError;

    /**
     * @Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $sys;
        $this->sys = $sys;
        self::$_loginError = $this->login();
    }

    /**
     * @Purpose: Used to check if there was an error logging in or not
     */
    public function login_error() {
        return self::$_loginError;
    }

    /**
     * @Purpose: Determins if a user is logged in or not
     */
    public function status() {
        if (NULL !== self::$_user) {
            return True;
        }

        return False;
    }

    /**
     * @Purpose: Used by this constructor to set class variables
     */
    public function login() {
        if (isset($_POST['login'])) {
            $result = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username AND `employee_password`=:password",
                array(
                    ':username' => $_POST['username'],
                    ':password' => md5($_POST['password'])
                ));
            
            if (isset($result[0]['employee_id'])) {
                $this->sys->session['user'] = $result[0]['employee_id'];
            } else {
                return True;
            }
        }
        if (isset($this->sys->session['user']) && NULL !== $this->sys->session['user']) {
            self::$_user = $this->sys->session['user'];
        } else {
            self::$_user = NULL;
        }
    }

    /**
     * @Purpose: Used to log a user out
     */
    public function logout() {
        self::$_user = NULL;
        session_destroy();
    }
}//End model_timeclock_loggedIn

//End File
