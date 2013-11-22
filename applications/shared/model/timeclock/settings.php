<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/21/13
 * @Date Modified: 11/22/13
 * @Purpose: Used to get and set settings
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $this->settings = $this->load_model('settings', <config path for timeclock>);
 *
 *      $this->settings->something = 'koala';
 *      echo $this->settings->something; //Returns 'koala'
 */
class model_timeclock_settings {
    private $_settings = array();
    private $_update_status = '';

    /**
     * @Purpose: For initalizing the settings from the database
     */
    public function __construct() {
        global $system_di;
        $settings = $system_di->db->query("SELECT `setting_name`, `setting_value` FROM `settings`");
        
        foreach ($settings as $setting) {
            $this->_settings[$setting['setting_name']] = $setting['setting_value'];
        }
        
        if (array_key_exists('update_settings', $_POST)) {
            $this->update_settings();
        }
    }
    
    /**
     * @Purpose: Used to set a setting in an object-like manner
     */
    public function __set($key, $value) {
        global $system_di;
        
        if (array_key_exists($key, $this->_settings)) {
            $system_di->db->query("UPDATE `settings` SET `setting_value`=:value WHERE `setting_name`=:key", array(
                ':key' => $key,
                ':value' => $value
            ));
        } else {
            $system_di->db->query("INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES ('', :key, :value)", array(
                ':key' => $key,
                ':value' => $value
            ));
        }
        
        $setting = $system_di->db->query("SELECT `setting_name`, `setting_value` FROM `settings` WHERE `setting_name`=:key", array(
            ':key' => $key
        ));
        
        $this->_settings[$setting[0]['setting_name']] = $setting[0]['setting_value'];
    }
    
    /**
     * @Purpose: Used to get a setting in an object-like manner
     */
    public function __get($key) {
        return array_key_exists($key, $this->_settings) ? $this->_settings[$key] : NULL;
    }
    
    /**
     * @Purpose: Loads a page, including the neccessary HTML headers and things
     */
    public function update_settings() {
        $valid_days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $valid_round_times = array('none', '1', '15', '30');
        $valid_sort_by = array('first_name', 'last_name', 'uid');
        $valid_list_as = array('last_first', 'first_last');
        
        $this->__set('pay_period_start', (array_key_exists('pay_period_start', $_POST) && (in_array($_POST['pay_period_start'], $valid_days)) ? $_POST['pay_period_start'] : 'Monday'));
        $this->__set('pay_period_end', ((array_key_exists('pay_period_end', $_POST) && in_array($_POST['pay_period_end'], $valid_days)) ? $_POST['pay_period_end'] : 'Sunday'));
        $this->__set('round_time_by', ((array_key_exists('round_time_by', $_POST) && array_key_exists($_POST['round_times_by'], $valid_round_times)) ? $_POST['round_time_by'] : 'none'));
        $this->__set('sort_employees_by', ((array_key_exists('sort_employees_by', $_POST) && in_array($_POST['sort_employees_by'], $valid_sort_by)) ? $_POST['sort_employees_by'] : 'first_name'));
        $this->__set('list_employees_as', ((array_key_exists('list_employees_as', $_POST) && in_array($_POST['list_employees_as'], $valid_list_as)) ? $_POST['list_employees_as'] : 'last_first'));
    
        $this->_update_status = '<div class="form_success">Settings Updated Successfully</div>';
    }
    
    /**
     * @Purpose: Used to pass back information about the settings (i.e. update successful)
     */
    public function update_status($status='') {
        return $this->_update_status;
    }
} //End model_timeclock_settings

//End File
