<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/27/13
 * @Purpose: Used to pull the employees from the database and use the results in a view
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      --- EXAMPLE 1 ---
 *      $this->employees = $this->load_model('employees');
 *      $this->sys->template->all_employees_by_id = $this->employees->get_employees(True, False);
 *          Sorts employees by ID without factoring in pagination;
 *          Parameter 1 (True) is sort by id (true/false)
 *          Parameter 2 (False) is paginate (true/false)
 *
 *      --- EXAMPLE 2 ---
 *      $this->employees = $this->load_model('employees');
 *      $renderPage = $this->load_model('timeclock_renderPage');
 *      $this->employees->get_employees_for_view(True);
 *      $this->sys->template->paginate = $renderPage->generate_pagination('main', 'employees', (int) $page_id);
 *          get_employees() will return $this->sys->template->employee with an array of all the employees
 *          If parameter 1 is True, pagination will be factored in and only a protion of the employees will be returned
 *          $this->sys->template->paginate holds the generated links 1...2...3 depending on the page you're on
 *              Parameter 1 is the page, so the links will be /main/1, /main/2, etc
 *              Parameter 2 is if you want to paginate employees, or payperiods
 *              Parameter 3 is the current page you're on
 *          
 *          
 *
 */
class model_timeclock_employees {
    /**
     * @Purpose: Used to pull $sys into class scope
     */
    public function __construct() {
        global $sys;
        $this->sys = &$sys;

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
            
                $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
            } while (!empty($query));
        } else {
            $uid = $_POST['uid'];
            
            $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
            
            if (!empty($query)) {
                $error .= '<p>That UID (Unique ID) is already in use.</p>';
            }
        }

        if ('' !== $_POST['username']) {
            $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username", array(
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
            $query = $this->sys->db->query("INSERT INTO `employees` (`employee_id`, `employee_uid`, `employee_firstname`, `employee_lastname`, `employee_username`, `employee_password`) VALUES ('', :uid, :firstname, :lastname, :username, :password)", array(
                    ':uid' => $uid,
                    ':firstname' => $_POST['firstname'],
                    ':lastname' => $_POST['lastname'],
                    ':username' => $_POST['username'],
                    ':password' => $password
                ));
        }


        if ($error !== '') {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
        } else {
            $this->sys->template->response = '<div class="form_success">Employee Added Successfully</div>';
            $this->sys->template->meta = array('1', $this->sys->config->timeclock_root . 'main');
        }
    } //End add_employee

    /**
     * @Purpose: Used to edit an employees database records
     */
    protected function edit_employee() {
        $id = (int) $_POST['id'];
        $password = '';
        
        $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => $id                                                                                                                    
            ));
        
        if (empty($query)) {
            $this->sys->template->response = '<div class="form_failed">That employee doesn\'t exist.</div>';
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
            $query = $this->sys->db->query("SELECT `employee_password` FROM `employees` WHERE `employee_username`=:username", array(
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
            
                $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
                ));
                
            } while (!empty($query));
        } else {
            $uid = $_POST['uid'];
            
            $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_uid`=:uid", array(
                    ':uid' => $uid
            ));
            
            if (!empty($query) && $query[0]['employee_id'] != $id) {
                $error .= '<p>That UID (Unique ID) is already in use.</p>';
            }
        }

        if ('' !== $_POST['username']) {
            $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_username`=:username", array(
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
            $query = $this->sys->db->query("UPDATE `employees` SET `employee_uid`=:uid, `employee_firstname`=:firstname, `employee_lastname`=:lastname, `employee_username`=:username, `employee_password`=:password WHERE `employee_id`=:id", array(
                    ':id' => $id,
                    ':uid' => $uid,
                    ':firstname' => $_POST['firstname'],
                    ':lastname' => $_POST['lastname'],
                    ':username' => $_POST['username'],
                    ':password' => $password
                ));
        }


        if ($error !== '') {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
        } else {
            $this->sys->template->response = '<div class="form_success">Employee Updated Successfully</div>';
            $this->sys->template->meta = array('1', $this->sys->config->timeclock_root . 'main');
        }
    } //End edit_employee
    
    /**
     * @Purpose: User to remove an employee from the database
     */
    protected function remove_employee() {
        $id = (int) $_POST['employee_id'];
        
        $query = $this->sys->db->query("SELECT `employee_id` FROM `employees` WHERE `employee_id`=:id", array(
                ':id' => $id                                                                                                                    
            ));
        
        if (!empty($query)) {
            $query = $this->sys->db->query("DELETE FROM `employees` WHERE `employee_id`=:id", array(
                    ':id' => $id                                           
                ));
        }
        
        header('Location: ' . $this->sys->config->timeclock_root);
    }
    
    /**
     * @Purpose: Used by this constructor to return employees
     */
    public function get_employees($by_id = False, $paginate = False) {
        switch ($this->sys->template->model_settings->sort_employees_by) {
            case 'first_name':
                $sort_employees_by = '`employee_firstname`, `employee_lastname`';
                break;
            case 'uid':
                $sort_employees_by = '`employee_uid`';
                break;
            default:
                $sort_employees_by = '`employee_lastname`, `employee_firstname`';
        }
        
        if (True === $paginate) {
            $start = ((1 >= $this->sys->template->page_id)) ? 0 : (int) ($this->sys->template->page_id-1) * $this->sys->template->paginate_by;
            $end = $this->sys->template->paginate_by;
            $limit = 'LIMIT ' . $start . ',' . $end;
        } else {
            $limit = '';
        }
        
        $result = $this->sys->db->query("SELECT * FROM `employees` ORDER BY $sort_employees_by $limit");
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
    public function get_employees_for_view($paginate = True) {
        global $sys;
        $employees = $this->get_employees(False, $paginate);
        $return_employees = array();

        array_walk($employees, function($value) use(&$return_employees) {
            $return_employees[] = $value;
        });

        $this->sys->template->employees = $return_employees;
    }
}//End model_timeclock_employees

//End File
