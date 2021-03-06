<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/26/13
 * Date Modified: 1/3/14
 * Purpose: Various functions that apply to installing timemanager
 */
class model_timemanager_install {
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
        $sql_file = __BASE_PATH . 'timemanager.sql';

        $table = $this->sys->db->query("SELECT `employee_id` FROM `employees` LIMIT 0,1");
        
        if (false === $table) {
            if (is_readable($sql_file)) {
                $sql_file = file_get_contents($sql_file);
                $this->sys->db->query($sql_file);
            } else {
                return 'Could not read \'' . $sql_file . '\' for importing the database.';
            }
        }
        
        $add_user = $this->sys->db->query("INSERT INTO `employees` (`employee_id`, `employee_uid`, `department_id`, `employee_role`, `employee_firstname`, `employee_lastname`, `employee_username`, `employee_password`) VALUES ('', '', '1', 'admin', :firstname, :lastname, :username, :password)", array(
            ':firstname' => ucwords(substr($_POST['firstname'], 0, 24)),
            ':lastname' => ucwords(substr($_POST['lastname'], 0, 24)),
            ':username' => substr($_POST['username'], 0, 28),
            ':password' => md5($_POST['password'])
        ));
        
        return true;
    }
}//End model_timemanager_loggedIn

//End File
