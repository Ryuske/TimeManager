<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/13/13
 * Date Modified: 12/18/13
 * Purpose: Controller for pay periods
 * Version: 2.0
 */

/**
 * Purpose: Controller for pay periods
 */
class timeclock_payperiod extends controller {
    /**
     * Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->sys->config->timeclock_subdirectories . '_' . $dependency);
            $this->sys->template->$name = $this->$name;
        }
    }

    /**
     * Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        $this->load_dependencies(array('loggedIn'));

        return $this->model_loggedIn->status();
    }

    /**
     * Purpose: Default function to be run when class is called
     */
    public function index() {}

    /**
     * Purpose: Used to punch in and out using the RESTFUL API
     */
    public function tx($uid) {
        $this->load_dependencies(array('payPeriod'));

        $check_employee_id = $this->sys->db->query("SELECT `employee_uid` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => $uid
        ));

        if (!empty($check_employee_id)) {
            $uid = $check_employee_id[0]['employee_uid'];
        }

        $employee = $this->sys->db->query("SELECT * FROM `employees` WHERE `employee_uid`=:uid", array(
            ':uid' => $uid
        ));

        $response = (!empty($employee)) ? $this->model_payPeriod->employee_punch($employee[0]['employee_id']) : 'Error';

        if ('Error' == $response) {
            $this->sys->template->response = $response;
        } else {
            $this->sys->template->response = $employee[0]['employee_firstname'] . ', ' . $response[0] . ', ' . $response[1]['date'] . ', ' . $response[1]['time'];
        }

        $this->sys->template->parse($this->sys->config->timeclock_subdirectories . '_payperiod_response');
    }

    /**
     * Purpose: Placeholder - what would be useful information to return about a pay period?
     */
    public function rx() {}

    /**
     * Purpose: Used to create a print friendly version of a pay period
     */
    public function print_friendly($employee_id, $pay_period) {
        $this->load_dependencies(array('renderPage', 'payPeriod', 'settings'));
        $pay_period = $this->model_payPeriod->get_pay_period($pay_period);

        $employee = $this->sys->db->query("SELECT `employee_firstname`,`employee_lastname` FROM `employees` WHERE `employee_id`=:employee_id", array(
            ':employee_id' => (int) $employee_id
        ));

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Printer Friendly Timecard';
            $this->sys->template->pay_period_table = $this->model_payPeriod->generate_pay_period_table($employee_id, $pay_period[0][0]);
            $this->sys->template->name = $employee[0]['employee_firstname'] . ' ' . $employee[0]['employee_lastname'];
            $this->sys->template->monday = date('m/d/y', $pay_period[0][0]);
            $this->sys->template->sunday = date('m/d/y', $pay_period[1][0]);
            $this->sys->template->total_hours = $this->model_payPeriod->total_hours_for_pay_period($employee_id, $pay_period[0][0]);
        } else {
            header('Location: ' . $this->sys->config->timeclock_root);
        }

        $this->model_renderPage->parse('payperiod_print');
    }
}//End timeclock_payperiod

//End File
