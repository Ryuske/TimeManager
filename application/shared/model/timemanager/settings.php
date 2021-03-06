<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/21/13
 * Date Modified: 12/31/13
 * Purpose: Used to get and set settings
 */

class model_timemanager_settings {
    private $_settings = array();
    private $_update_status = '';

    /**
     * Purpose: For initalizing the settings from the database
     */
    public function __construct() {
        global $sys;

        $settings = $sys->db->query("SELECT `setting_name`, `setting_value` FROM `settings`");
        $settings = (false === $settings) ? array() : $settings;
        
        foreach ($settings as $setting) {
            $this->_settings[$setting['setting_name']] = $setting['setting_value'];
        }
        
        if (array_key_exists('update_settings', $_POST) && $this->is_admin()) {
            $this->update_settings();
        }
    }
    
    /**
     * Purpose: Used to set a setting in an object-like manner
     */
    public function __set($key, $value) {
        global $sys;
        
        if (array_key_exists($key, $this->_settings)) {
            $sys->db->query("UPDATE `settings` SET `setting_value`=:value WHERE `setting_name`=:key", array(
                ':key' => substr($key, 0, 64),
                ':value' => substr($value, 0, 128)
            ));
        } else {
            $sys->db->query("INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES (NULL, :key, :value)", array(
                ':key' => substr($key, 0, 64),
                ':value' => substr($value, 0, 128)
            ));
        }
        
        $setting = $sys->db->query("SELECT `setting_name`, `setting_value` FROM `settings` WHERE `setting_name`=:key", array(
            ':key' => substr($key, 0, 64)
        ));
        
        $this->_settings[$setting[0]['setting_name']] = $setting[0]['setting_value'];
    }
    
    /**
     * Purpose: Used to get a setting in an object-like manner
     */
    public function __get($key) {
        return array_key_exists($key, $this->_settings) ? $this->_settings[$key] : NULL;
    }
    
    /**
     * Purpose: Used to overload empty() so that you can check is settings are empty/isset
     */
    public function __isset($key) {
        return isset($this->_settings[$key]);
    }
    
    /**
     * Purpose: Loads a page, including the neccessary HTML headers and things
     */
    public function update_settings() {
        $valid_round_times =        array('none', '1', '15', '30');
        $valid_sort_employees_by =  array('first_name', 'last_name', 'uid');
        $valid_list_employees_as =  array('last_first', 'first_last');
        $valid_sort_jobs_by =       array('job_id', 'job_name', 'client_name');
        
        $this->__set('round_time_by', ((array_key_exists('round_time_by', $_POST) && in_array($_POST['round_time_by'], $valid_round_times)) ? $_POST['round_time_by'] : 'none'));
        $this->__set('sort_employees_by', ((array_key_exists('sort_employees_by', $_POST) && in_array($_POST['sort_employees_by'], $valid_sort_employees_by)) ? $_POST['sort_employees_by'] : 'first_name'));
        $this->__set('list_employees_as', ((array_key_exists('list_employees_as', $_POST) && in_array($_POST['list_employees_as'], $valid_list_employees_as)) ? $_POST['list_employees_as'] : 'last_first'));
        $this->__set('sort_jobs_by', ((array_key_exists('sort_jobs_by', $_POST) && in_array($_POST['sort_jobs_by'], $valid_sort_jobs_by)) ? $_POST['sort_jobs_by'] : 'job_id'));
        $this->__set('paginate_by', ((array_key_exists('paginate_by', $_POST)) ? (int) $_POST['paginate_by'] : '10'));
        
        $this->_update_status = '<div class="form_success">Settings Updated Successfully</div>';
    }
    
    /**
     * Purpose: Used to pass back information about the settings (i.e. update successful)
     */
    public function update_status($status='') {
        return $this->_update_status;
    }
    
    /**
     * Purpose: Used to determin if employee is admin
     */
    public function is_admin() {
        global $sys;
        
        $is_admin = $sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($sys->session['user'], 0, 6)
        ));
        
        if (empty($is_admin)) {
            return false;
        } elseif ('admin' === $is_admin[0]['employee_role']) {
            return true;
        } else {
            return false;
        }
    }
} //End model_timemanager_settings

//End File
