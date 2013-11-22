<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/22/13
 * @Purpose: Various functions that apply to logged in users (or not logged in)
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $loggedIn = $this->load_model('loggedIn');
 */
class model_timeclock_loggedIn {
    private static $_user;
    public static $_loginError;

    /**
     * @Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $system_di;
        $this->system_di = $system_di;
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
