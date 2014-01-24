<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/10/13
 * Date Modified: 1/24/14
 * Purpose: Used as a wrapper for various methods surrounding employee departments
 */

global $sys;
$sys->router->load_helpers('interfaces', 'general', 'timemanager');
 
 class model_timemanager_departments implements general_actions {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        $is_admin = $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($this->sys->session['user'], 0, 6)
        ));
        
        if (!empty($is_admin)) {
            $is_admin = ('admin' === $is_admin[0]['employee_role']) ? true : false;
        } else {
            $is_admin = false;
        }
        
        if (array_key_exists('add_department', $_POST) && $is_admin) {
            $this->add();
        }
        if (array_key_exists('edit_department', $_POST) && $is_admin) {
            $this->edit();
        }
        if (array_key_exists('remove_department', $_POST) && $is_admin) {
            $this->remove();
        }
        if (array_key_exists('charged_hourly_value', $_POST) && is_array($_POST['charged_hourly_value']) && $is_admin) {
            $this->update_defaults();
        }
    }
    
    /**
     * Database Modification - Add/Edit/Remove
     */
    /**
     * Purpose: Used for checking $_POST inputs
     */
    public function check_input($method) {
        $error = '';
        
        if ('remove' !== $method) {
            if (!array_key_exists('department_name', $_POST) || '' === $_POST['department_name']) {
                $error .= '<p>Please enter a department name.</p>';
            }
        }
        
        switch ($method) {
            case 'add':
                break;
            case 'edit':
                if (!array_key_exists('department_id', $_POST)) {
                    $error = '<p>Something wrong with the department ID.</p>';
                } else {
                    $department = $this->sys->db->query("SELECT `department_id` FROM `departments` WHERE `department_id`=:id", array(
                        ':id' => (int) substr($_POST['department_id'], 0, 4)
                    ));
                    
                    if (empty($department)) {
                        $error .= '<p>Department Doesn\'t Exist.</p>';
                    }
                }
                
                break;
            case 'remove':
                if (!array_key_exists('department_id', $_POST)) {
                    $error = '<p>Something wrong with the department ID.</p>';
                }
                break;
            default:
                return false;
        }
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return true;
        }
            
        return false;
    }
    
    /**
     * Purpose: Used to add departments to the database
     */
    public function add() {
        $error = $this->check_input('add');
        
        if (!$error) {
            $this->sys->db->query("INSERT INTO `departments` (`department_id`, `department_name`) VALUES (NULL, :name)", array(
                ':name' => ucwords(strtolower(substr($_POST['department_name'], 0, 256)))
            ));
            
            $this->sys->template->response = '<div class="form_success">Department Added Successfully</div>';
            return true;
        }
    }
    
    /**
     * Purpose: Used to update the default values for how much each department is charged/cost to run
     */
    protected function update_defaults() {
        $query_bindings = array();
        $sql_charged_hourly_value = '';
        $sql_payed_hourly_value = '';
        $ids = '';
        
        //print_r($_POST);
        foreach ($_POST['charged_hourly_value'] as $id=>$value) {
            $ids .= $id . ', ';
            $query_bindings[':charged_id_' . $id] = $id;
            $query_bindings[':charged_value_' . $id] = $value;
            $sql_charged_hourly_value .= "
                WHEN :charged_id_$id THEN :charged_value_$id";
        }
        foreach ($_POST['payed_hourly_value'] as $id=>$value) {
            $query_bindings[':payed_id_' . $id] = $id;
            $query_bindings[':payed_value_' . $id] = $value;
            $sql_payed_hourly_value .= "
                WHEN :payed_id_$id THEN :payed_value_$id";
        }
        
        $sql = "
            UPDATE `departments`
                SET `charged_hourly_value` = CASE `department_id`
                    $sql_charged_hourly_value
                END,
                SET `payed_hourly_value`
                    $sql_payed_hourly_value
                END
            WHERE `department_id` IN (" . substr($ids, 0, -2) . ")
        ";
        //echo $sql;
        /*print_r($query_bindings);
        echo $sql_charged_hourly_value;
        echo $sql_payed_hourly_value;
        die();*/
        
        $this->sys->db->query("
            UPDATE `departments`
                SET `charged_hourly_value` = CASE `department_id`
                    $sql_charged_hourly_value
                END,
                `payed_hourly_value` = CASE `department_id`
                    $sql_payed_hourly_value
                END;
            WHERE `department_id` IN (" . substr($ids, 0, -2) . ")
        ", $query_bindings);
        
        $this->sys->template->response = '<div id="response" class="form_success">Updated Defaults Successfully</div>';
    }
    
    /**
     * Purpose: Used to edit departments in the database
     */
    public function edit() {
        $error = $this->check_input('edit');
        
        if (!$error) {
            $this->sys->db->query("UPDATE `departments` SET `department_name`=:name WHERE `department_id`=:id", array(
                ':name' => ucwords(strtolower(substr($_POST['department_name'], 0, 256))),
                ':id'   => (int) substr($_POST['department_id'], 0, 4)
            ));
            
            $this->sys->template->response = '<div class="form_success">Department Updated Successfully</div>';
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Used to remove departments from the database
     */
    public function remove() {
        $error = $this->check_input('remove');
        
        if (!$error) {
            $this->sys->db->query("DELETE FROM `departments` WHERE `department_id`=:id", array(
                ':id' => (int) substr($_POST['department_id'], 0, 4)
            ));
            
            $this->sys->template->response = '<div class="form_success">Department Removed Successfully</div>';
            
            return true;
        }
        
        return false;
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * Purpose: Returns a list of all the departments.
     * $action sorts departments by ID instead of sequentially
     */
    public function get($action=false, $pagination=false) {
        $departments = $this->sys->db->query("SELECT * FROM `departments`");
        $return_departments = array();
        
        if ($action) {
            foreach ($departments as $department) {
                $return_departments[$department['department_id']] = $department;
            }
        } else {
            $return_departments = $departments;
        }
        
        return $return_departments;
    }
 }
 
 //End file
 