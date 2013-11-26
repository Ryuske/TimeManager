<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/22/13
 * @Purpose: Employee controller
 * @Version: 1.0
 */

/**
 * @Purpose: Employee Controller
 */
class timeclock_employee extends controller {
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
        return $this->model_loggedIn->status();
    }
    
    /**
     * @Purpose: Used to get & return employees to the view
     */
    protected function employees($by_id=True) {
        $this->load_dependencies(array('employees'));
        if (!is_bool($by_id)) {
            die(debug_print_backtrace());
        }
        if (True === $by_id) {
            return $this->model_employees->get_employees(True, False);
        } else {
            return $this->model_employees->get_employees(False, False);
        }
    }
    
    /**
     * @Purpose: Used to make echo'ing out the values in the view a little "prettier"
     */
    public static function writeout($employee_id=NULL, $employee_value='id', $sort_by = 'order') {
        global $system_di;
        if (NULL !== $employee_id && 'order' === $sort_by) {
            echo $system_di->template->all_employees[$employee_id]['employee_' . $employee_value];
        } elseif (NULL !== $employee_id && 'by_id' === $sort_by) {
            echo $system_di->template->all_employees_by_id[$employee_id]['employee_' . $employee_value];
        } else {
            echo $system_di->template->employee['employee_' . $employee_value];
        }
    }

    /**
     * @Purpose: Default function to be run when class is called
     */
    public function index() {
        $this->load_home();
    }

    /**
     * @Purpose: Function that is run when employee/add is accessed
     */
    public function add() {
        $this->system_di->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees'));

        if ($this->is_logged_in()) {
            $this->system_di->template->title = 'TimeClock | Employee | Add';
            $parse = 'employee_add';
        } else {
            $this->load_home();
        }

        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * @Purpose: Used for editing existing employees (employee/edit/x)
     */
    public function edit($employee_id) {
        $this->system_di->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'settings'));
        $this->system_di->template->all_employees_by_id = $this->model_employees->get_employees(True);
        $this->system_di->template->employee_id = (int) $employee_id;
        
        if ($this->is_logged_in()) {
            $this->system_di->template->title = 'TimeClock | Employee | Edit';
            $parse = 'employee_edit';
        } else {
            $this->load_home();
        }

        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * @Purpose: Used to remove an existing employee (employee/remove/x)
     */
    public function remove($employee_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings'));

        if ($this->is_logged_in()) {
            $this->system_di->template->all_employees = $this->employees(False);
            $this->system_di->template->all_employees_by_id = $this->employees(True);
            $this->system_di->template->employee_id = (int) $employee_id;

            $this->system_di->template->title = 'TimeClock | Employee | Remove';
            $this->system_di->template->home_active = 'class="active"';
            $parse = 'employee_remove';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, True);
    }

    /**
     * @Purpose: Used to view an existing employees time card
     */
    public function view($employee_id, $pay_period='current', $page_id = 1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'payPeriod'));
        $this->system_di->template->page_id = (int) $page_id;
        $this->system_di->template->paginate_by = $this->model_settings->paginate_by;
        $this->system_di->template->employee_id = (int) $employee_id;
        
        if ('current' === $pay_period) {
            $this->pay_period = $this->model_payPeriod->get_pay_period();
        } else {
            $this->pay_period = $this->model_payPeriod->get_pay_period($pay_period);
        }
        
        $this->system_di->template->pay_period_id = $this->pay_period[2];
        $this->system_di->template->pay_period_monday = $this->pay_period[0][0];
        $this->system_di->template->pay_period_sunday = $this->pay_period[1][0];
        $this->system_di->template->pay_period_table = $this->model_payPeriod->generate_pay_period_table($this->system_di->template->employee_id, $this->pay_period[0]);
        
        $this->system_di->template->previous_pay_periods_table = $this->model_payPeriod->generate_previous_pay_periods_table($this->system_di->template->employee_id, $this->pay_period[2]);
        $this->system_di->template->total_hours = $this->model_payPeriod->total_hours_for_pay_period($this->system_di->template->employee_id, $this->pay_period[0]);
        
        $this->system_di->template->add_date_response = $this->model_payPeriod->add_date_response();
        
        if ($this->is_logged_in()) {
            $this->system_di->template->all_employees = $this->employees(False);
            $this->system_di->template->all_employees_by_id = $this->employees(True);
            $this->system_di->template->pagination = $this->model_renderPage->generate_pagination('employee/view/' . (int) $employee_id . '/' . $pay_period, 'payperiods', (int) $page_id);

            $this->system_di->template->title = 'TimeClock | Employee | View';
            $this->system_di->template->home_active = 'class="active"';
            $parse = 'employee_view';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * @Purpose: Function used to return you to the home controller if there is a problem
     */
    protected function load_home() {
        header('Location: ' . $this->system_di->config->timeclock_root);
    }
}//End timeclock_employee

//End File
