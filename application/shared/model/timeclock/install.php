<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/26/13
 * @Date Modified: 11/26/13
 * @Purpose: Various functions that apply to installing timeclock
 * @Version: 1.0
 */
class model_timeclock_install {
    /**
     * @Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $system_di;
        $this->system_di = $system_di;
        $system_di->template->install_error = '';
        
        if (array_key_exists('install', $_POST)) {
             $system_di->template->install_error = $this->check_inputs();
            return True;
        }
        return False;
    }
    
    /**
     * @Purpose: Used to make sure all the required inputs have been added
     */
    protected function check_inputs() {
        $error = '';
        
        switch ($_POST) {
            case !array_key_exists('firstname', $_POST):
                $error .= 'Admin first name is required. <br />';
            case !array_key_exists('lastname', $_POST):
                $error .= 'Admin last name is required. <br />';
            case !array_key_exists('username', $_POST):
                $error .= 'Admin username is required. <br />';
            case !array_key_exists('password', $_POST):
                $error .= 'Admin password is required. <br />';
            default:
                break;
        }
        
        if ('' !== $error) {
            return False;
        } else {
            $this->add_admin();
            return True;
        }
    }

    /**
     * @Purpose: Adds the first user (admin user)
     */
    protected function add_admin() {
        $add_user = $this->system_di->db->query("INSERT INTO `employees` (`employee_id`, `employee_uid`, `employee_firstname`, `employee_lastname`, `employee_username`, `employee_password`) VALUES ('', '', :firstname, :lastname, :username, :password)", array(
            ':firstname' => $_POST['firstname'],
            ':lastname' => $_POST['lastname'],
            ':username' => $_POST['username'],
            ':password' => md5($_POST['password'])
        ));
        
        header('Location: ' . $this->system_di->config->timeclock_root . 'index');
    }
}//End model_timeclock_loggedIn

//End File
