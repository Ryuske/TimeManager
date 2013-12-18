<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/09/13
 * Date Modified: 12/18/13
 * Purpose: Used as a wrapper for various methods surrounding clients
 * Version: 2.0
 */

global $sys;
$sys->router->load_helpers('interfaces', 'general', 'timeclock');

class model_timeclock_clients implements general_actions {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        $is_admin = $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $this->sys->session['user']
        ));
        
        if (!empty($is_admin)) {
            $is_admin = ('admin' === $is_admin[0]['employee_role']) ? true : false;
        } else {
            $is_admin = false;
        }
        
        if (array_key_exists('add_client', $_POST) && $is_admin) {
            $this->add();
        }
        if (array_key_exists('edit_client', $_POST) && $is_admin) {
            $this->edit();
        }
        if (array_key_exists('remove_client', $_POST) && $is_admin) {
            $this->remove();
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
            if (!array_key_exists('client_name', $_POST) || '' === $_POST['client_name']) {
                $error .= '<p>Please enter a client name.</p>';
            }
        }
        
        switch ($method) {
            case 'add':
                break;
            case 'edit':
                if (!array_key_exists('client_id', $_POST)) {
                    $error = '<p>Something wrong with the client ID.</p>';
                } else {
                    $client = (!$error) ? $this->sys->db->query("SELECT `client_id` FROM `clients` WHERE `client_id`=:id", array(
                        ':id' => (int) $_POST['client_id']
                    )) : '';
                    
                    $error .= '<p>Client Doesn\'t Exist.</p>';
                }
                break;
            case 'remove':
                if (!array_key_exists('client_id', $_POST)) {
                    $error = '<p>Something wrong with the client ID.</p>';
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
     * Purpose: Used ot add clients to the database
     */
    public function add() {
        $error = $this->check_input('add');
        
        if (!$error) {
            $this->sys->db->query("INSERT INTO `clients` (`client_id`, `client_name`) VALUES (NULL, :name)", array(
                ':name' => $_POST['client_name']
            ));
            
            $this->sys->template->response = '<div class="form_success">Client Added Successfully</div>';
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Used ot edit clients in the database
     */
    public function edit() {
        $error = $this->check_input('edit');
        
        if (!$error) {
            $this->sys->db->query("UPDATE `clients` SET `client_name`=:name WHERE `client_id`=:id", array(
                ':name' => $_POST['client_name'],
                ':id'   => (int) $_POST['client_id']
            ));

            $this->sys->template->response = '<div class="form_success">Client Updated Successfully</div>';
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Used ot remove clients from the database
     */
    public function remove() {
        $error = $this->check_input('remove');
        
        if (!$error) {
            $this->sys->db->query("DELETE FROM `clients` WHERE `client_id`=:id", array(
                ':id' => (int) $_POST['client_id']
            ));
            
            $this->sys->template->response = '<div class="form_success">Client Removed Successfully</div>';
            return true;
        }
        
        return false;
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * Purpose: Returns a list of all the clients
     */
    public function get($action=false, $pagination=false) {
        $clients = $this->sys->db->query("SELECT * FROM `clients`");
        
        return $clients;
    }
 }
 
 //End file
 