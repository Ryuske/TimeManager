<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/26/13
 * @Purpose: Controller for pay periods
 * @Version: 1.0
 */

/**
 * @Purpose: Controller for pay periods
 */
class timeclock_payperiod extends controller {
    /**
     * @Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->system_di->config->timeclock_subdirectories . '_' . $dependency);
            $this->system_di->template->$name = $this->$name;
        }
    }
    
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        $this->load_dependencies(array('loggedIn'));
        
        return $this->model_loggedIn->status();
    }
    
    /**
     * @Purpose: Default function to be run when class is called
     */
    public function index() {}
    
    /**
     * @Purpose: Used to punch in and out using the RESTFUL API
     */
    public function tx($uid) {
        $this->load_dependencies(array('payPeriod'));
        
        $employee = $this->system_di->db->query("SELECT * FROM `employees` WHERE `employee_uid`=:uid", array(
            ':uid' => $uid
        ));
        
        $response = $this->model_payPeriod->employee_punch($employee[0]['employee_id']);
        
        if ('Error' == $response) {
            $this->system_di->template->response = $response;
        } else {
            $this->system_di->template->response = $employee[0]['employee_firstname'] . ', ' . $response[0] . ', ' . $response[1]['date'] . ', ' . $response[1]['time'];
        }
        
        $this->system_di->template->parse($this->system_di->config->timeclock_subdirectories . '_payperiod_response');
    }
    
    /**
     * @Purpose: Used to send data back using the RESTFUL API
     */
    public function rx($uid, $data, $pay_period='current') {
        $this->load_dependencies(array('payPeriod'));
        $employee = $this->system_di->db->query("SELECT * FROM `employees` WHERE `employee_uid`=:uid", array(
            ':uid' => $uid
        ));
        $pay_period_query = $this->system_di->db->query("SELECT `time`,`date`,`operation` FROM `employee_punch` WHERE `employee_id`=:employee_id ORDER BY `employee_punch_id` DESC", array(
            ':employee_id' => (int) $employee[0]['employee_id']
        ));
        
        $pay_period = $this->model_payPeriod->get_pay_period();
        $response = 'Error';
        
        if (empty($employee)) {
            $this->system_di->template->response = $response;
            $this->system_di->template->parse($this->system_di->config->timeclock_subdirectories . '_payperiod_response');
            
            return False;
        }

        switch ($data) {
            case 'employee_name':
                $response = $employee[0]['employee_firstname'] . ' ' . $employee[0]['employee_lastname'];
                break;
            case 'last_op':
                $response = $pay_period_query[0]['operation'];
                break;
            case 'last_time':
                $response = date('g:ia', $pay_period_query[0]['time']);
                break;
            case 'last_date':
                $response = $pay_period_query[0]['date'];
                break;
            case 'total_hours':
                $response = $this->model_payPeriod->total_hours_for_pay_period($employee[0]['employee_id'], $pay_period[0][0]);
                break;
            default:
                $response = NULL;
        }
        
        $this->system_di->template->response = $response;
        $this->system_di->template->parse($this->system_di->config->timeclock_subdirectories . '_payperiod_response');
        return True;
    }
    
    /**
     * @Purpose: Used to create a print friendly version of a pay period
     */
    public function print_friendly($employee_id, $pay_period) {
        $this->load_dependencies(array('renderPage', 'payPeriod'));
        $pay_period = $this->model_payPeriod->get_pay_period($pay_period);
        
        $employee = $this->system_di->db->query("SELECT `employee_firstname`,`employee_lastname` FROM `employees` WHERE `employee_id`=:employee_id", array(
            ':employee_id' => (int) $employee_id
        ));
        
        if ($this->is_logged_in()) {
            $this->system_di->template->title = 'TimeClock | Printer Friendly Timecard';
            $this->system_di->template->pay_period_table = $this->model_payPeriod->generate_pay_period_table($employee_id, $pay_period[0][0]);
            $this->system_di->template->name = $employee[0]['employee_firstname'] . ' ' . $employee[0]['employee_lastname'];
            $this->system_di->template->monday = date('m/d/y', $pay_period[0][0]);
            $this->system_di->template->sunday = date('m/d/y', $pay_period[1][0]);
            $this->system_di->template->total_hours = $this->model_payPeriod->total_hours_for_pay_period($employee_id, $pay_period[0][0]);
        } else {
            header('Location: ' . $this->system_di->config->timeclock_root);
        }
        
        $this->model_renderPage->parse('payperiod_print');
    }
}//End timeclock_payperiod

//End File
