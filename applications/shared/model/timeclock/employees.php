<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/21/13
 * @Purpose: Used to pull the employees from the database and use the results in a view
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $this->employees = $this->load_model('employees');
 *      $this->system_di->template->all_employees_by_id = $this->employees->getEmployees('by_id');
 */
class model_employees {
    /**
     * @Purpose: Used to pull $system_di into class scope
     */
    public function __construct() {
        global $system_di;
        $this->system_di = &$system_di;

        if (array_key_exists('add_employee', $_POST)) {
            $this->add_employee();
        }
        
        if (array_key_exists('edit_employee', $_POST)) {
            $this->edit_employee();
        }
        
        if (array_key_exists('remove_employee', $_POST)) {
            $this->remove_employee();
        }
    }

    /**
     * @Purpose: Used to add an employee to the database
     */
    protected function add_employee() {
        $error = '';

        if ('' === $_POST['firstname']) {
            $error = '<p>Please enter employee\'s first name.</p>';
        }
        if ('' === $_POST['lastname']) {
            $error .= '<p>Please enter employee\'s last name.</p>';
        }
        if ('' === $_POST['uid'] && !isset($_POST['generate_uid'])) {
            $error .= '<p>You either need to enter a UID or check \'Pick a UID For Me\'.</p>';
        }
        if ('' !== $_POST['username'] && $_POST['password'] === '') {
            $error .= '<p>If you pick a username, you must enter a password.</p>';
        }
        
        if (isset($_POST['generate_uid']) && '' === $_POST['uid']) {
            do {
                $uid = substr(md5(rand()), 0, 8);
                $uid = str_split($uid, 2);
                foreach ($uid as &$new_uid) {
                    $new_uid = '0x' . strtoupper($new_uid);
                }
                $uid = implode(' ', $uid);
            
                $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
            } while (!empty($query));
        } else {
            $uid = $_POST['uid'];
            
            $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
            
            if (!empty($query)) {
                $error .= '<p>That UID (Unique ID) is already in use.</p>';
            }
        }

        if ('' !== $_POST['username']) {
            $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username", array(
                    ':username' => $_POST['username']
                ));
                
            if (empty($query)) {
                $error .= '<p>That username is already in use.</p>';
            }
            
            $password = md5($_POST['password']);
        } else {
            $password = '';
        }

        if ($error === '') {
            $query = $this->system_di->db->query("INSERT INTO `employees` (`employee_id`, `employee_uid`, `employee_firstname`, `employee_lastname`, `employee_username`, `employee_password`) VALUES ('', :uid, :firstname, :lastname, :username, :password)", array(
                    ':uid' => $uid,
                    ':firstname' => $_POST['firstname'],
                    ':lastname' => $_POST['lastname'],
                    ':username' => $_POST['username'],
                    ':password' => $password
                ));
        }


        if ($error !== '') {
            $this->system_di->template->response = '<div class="form_failed">' . $error . '</div>';
        } else {
            $this->system_di->template->response = '<div class="form_success">Employee Added Successfully</div>';
            $this->system_di->template->meta = array('1', $this->system_di->config->timeclock_root . 'main');
        }
    } //End add_employee

    /**
     * @Purpose: Used to edit an employees database records
     */
    protected function edit_employee() {
        $id = (int) $_POST['id'];
        $password = '';
        
        $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => $id                                                                                                                    
            ));
        
        if (empty($query)) {
            $this->system_di->template->response = '<div class="form_failed">That employee doesn\'t exist.</div>';
            return False;
        }
        
        $error = '';

        if ('' === $_POST['firstname']) {
            $error = '<p>Please enter employee\'s first name.</p>';
        }
        if ('' === $_POST['lastname']) {
            $error .= '<p>Please enter employee\'s last name.</p>';
        }
        if ('' === $_POST['uid'] && !isset($_POST['generate_uid'])) {
            $error .= '<p>You either need to enter a UID or check \'Pick a UID For Me\'.</p>';
        }
        if ('' !== $_POST['username'] && $_POST['password'] === '') {
            $query = $this->system_di->db->query("SELECT `employee_password` FROM `employees` WHERE `employee_username`=:username", array(
                    ':username' => $_POST['username']
                ));
            
            $password = $query[0]['employee_password'];
            if ('' === $password) {
                $error .= '<p>If you pick a username, you must enter a password.</p>';
            }
        }
        
        if (isset($_POST['generate_uid']) && '' === $_POST['uid']) {
            do {
                $uid = substr(md5(rand()), 0, 8);
                $uid = str_split($uid, 2);
                foreach ($uid as &$new_uid) {
                    $new_uid = '0x' . strtoupper($new_uid);
                }
                $uid = implode(' ', $uid);
            
                $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
            } while (empty($query));
        } else {
            $uid = $_POST['uid'];
            
            $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
            ));
            
            if (!empty($query) && $query[0]['employee_id'] != $id) {
                $error .= '<p>That UID (Unique ID) is already in use.</p>';
            }
        }

        if ('' !== $_POST['username']) {
            $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username", array(
                    ':username' => $_POST['username']
                ));
                
            if (!empty($query) && $query[0]['employee_id'] != $id) {
                $error .= '<p>That username is already in use.</p>';
            }
            
            if ('' === $password) {
                $password = md5($_POST['password']);
            }
        } else {
            $password = '';
        }

        if ($error === '') {
            $query = $this->system_di->db->query("UPDATE `employees` SET `employee_uid`=:uid, `employee_firstname`=:firstname, `employee_lastname`=:lastname, `employee_username`=:username, `employee_password`=:password WHERE `employee_id`=:id", array(
                    ':id' => $id,
                    ':uid' => $uid,
                    ':firstname' => $_POST['firstname'],
                    ':lastname' => $_POST['lastname'],
                    ':username' => $_POST['username'],
                    ':password' => $password
                ));
        }


        if ($error !== '') {
            $this->system_di->template->response = '<div class="form_failed">' . $error . '</div>';
        } else {
            $this->system_di->template->response = '<div class="form_success">Employee Updated Successfully</div>';
            $this->system_di->template->meta = array('1', $this->system_di->config->timeclock_root . 'main');
        }
    } //End edit_employee
    
    /**
     * @Purpose: User to remove an employee from the database
     */
    protected function remove_employee() {
        $id = (int) $_POST['employee_id'];
        
        $query = $this->system_di->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => $id                                                                                                                    
            ));
        
        if (!empty($query)) {
            $query = $this->system_di->db->query("DELETE FROM `employees` WHERE `employee_id`=:id", array(
                    ':id' => $id                                           
                ));
        }
        
        header('Location: ' . $this->system_di->config->timeclock_root);
    }
    
    /**
     * @Purpose: Used by this constructor to return employees
     */
    public function get_employees($by_id = False) {
        $result = $this->system_di->db->query("SELECT * FROM `employees` ORDER BY `employee_lastname`, `employee_firstname`");
        $employees = array();
        
        if (True === $by_id) {
            array_walk($result, function($employee) use(&$employees) {
                $employees[$employee['employee_id']] = $employee;
            });
        
            return $employees;
        }
        
        return $result;
    }

    /**
     * @Purpose: Used to return employees in a view-friendly manner
     */
    public function get_employees_for_view() {
        global $system_di;
        $employees = $this->get_employees();
        $return_employees = array();

        array_walk($employees, function($value) use(&$return_employees) {
            $return_employees[] = $value;
        }); //End array_walk $employees

        $this->system_di->template->employee = $return_employees;
    }
}//End model_employees

//End File
