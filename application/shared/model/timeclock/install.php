<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/26/13
 * Date Modified: 12/18/13
 * Purpose: Various functions that apply to installing timeclock
 * Version: 2.0
 */
class model_timeclock_install {
    /**
     * Purpose: Creates a constructor that sets various class variables
     */
    public function __construct() {
        global $sys;
        $this->sys = $sys;
        $sys->template->install_error = '';
        
        if (array_key_exists('install', $_POST)) {
             $sys->template->install_error = $this->check_inputs();
            return true;
        }
        return false;
    }

    /**
     * Purpose: Used to make sure all the required inputs have been added
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
            return $error;
        } else {
            return $this->add_admin();
        }
    }
    
    /**
     * Purpose: Adds the first user (admin user)
     */
    protected function add_admin() {
        $sql_file = __BASE_PATH . 'timeclock.sql';

        $table = $this->sys->db->query("SELECT `employee_id` FROM `employees` LIMIT 0,1");
        
        if (false === $table) {
            if (is_readable($sql_file)) {
                $sql_file = file_get_contents($sql_file);
                $this->sys->db->query($sql_file);
            } else {
                return 'Could not read \'' . $sql_file . '\' for importing the database.';
            }
        }
        
        $add_user = $this->sys->db->query("INSERT INTO `employees` (`employee_id`, `employee_uid`, `category_id`, `employee_firstname`, `employee_lastname`, `employee_username`, `employee_password`) VALUES ('', '', '1', :firstname, :lastname, :username, :password)", array(
            ':firstname' => $_POST['firstname'],
            ':lastname' => $_POST['lastname'],
            ':username' => $_POST['username'],
            ':password' => md5($_POST['password'])
        ));
        
        return true;
    }
}//End model_timeclock_loggedIn

//End File
