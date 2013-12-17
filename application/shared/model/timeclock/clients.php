<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/09/13
 * @Date Modified: 12/17/13
 * @Purpose: Used as a wrapper for various methods surrounding clients
 * @Version: 2.0
 */

 class model_timeclock_clients {
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
     * @Purpose: Used ot add clients to the database
     */
    protected function add() {
        $error = (array_key_exists('client_name', $_POST) && '' === $_POST['client_name']) ? '<p>Please enter a name for the client</p>' : '';
        
        $add_client = ('' === $error) ? $this->sys->db->query("INSERT INTO `clients` (`client_id`, `client_name`) VALUES (NULL, :name)", array(
            ':name' => $_POST['client_name']
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Client Added Successfully</div>';
            return true;
        }
    }
    
    /**
     * * @Purpose: Used ot edit clients in the database
     */
    protected function edit() {
        $error = '';
        
        if (!array_key_exists('client_id', $_POST)) {
            $error = '<p>Something wrong with the client ID.</p>';
        }
        if (!array_key_exists('client_name', $_POST) || '' === $_POST['client_name']) {
            $error .= '<p>Please enter a client name.</p>';
        }
        
        $client = ('' === $error) ? $this->sys->db->query("SELECT `client_id` FROM `clients` WHERE `client_id`=:id", array(
            ':id' => (int) $_POST['client_id']
        )) : '';
        
        if (empty($error) && empty($client)) {
            $error .= '<p>Client Doesn\'t Exist.</p>';
        } elseif (empty($error)) {
            $this->sys->db->query("UPDATE `clients` SET `client_name`=:name WHERE `client_id`=:id", array(
                ':name' => $_POST['client_name'],
                ':id'   => (int) $_POST['client_id']
            ));
        }
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Client Updated Successfully</div>';
            return true;
        }
    }
    
    /**
     * @Purpose: Used ot remove clients from the database
     */
    protected function remove() {
        $error = '';
        
        if (!array_key_exists('client_id', $_POST)) {
            $error = '<p>Something wrong with the client ID.</p>';
        }
        
        if (empty($error)) {
            $this->sys->db->query("DELETE FROM `clients` WHERE `client_id`=:id", array(
                ':id' => (int) $_POST['client_id']
            ));
            
            $this->sys->template->response = '<div class="form_success">Client Removed Successfully</div>';
            return true;
        } else {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        }
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * @Purpose: Returns a list of all the clients
     */
    public function get_clients() {
        $clients = $this->sys->db->query("SELECT * FROM `clients`");
        
        return $clients;
    }
 }
 
 //End file
 