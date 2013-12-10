<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 12/10/13
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
            $this->$name = $this->load_model($this->sys->config->timeclock_subdirectories . '_' . $dependency);
            $this->sys->template->$name = $this->$name;
        }
    }
    
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
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
        $this->sys->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'categories', 'employees'));

        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Employee | Add';
            $this->sys->template->categories = $this->model_categories->get_categories();
            
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
        $this->sys->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'categories', 'settings'));
        $this->sys->template->employees_by_id = $this->model_employees->get_employees(True);
        $this->sys->template->employee_id = (int) $employee_id;
        $this->sys->template->categories = $this->model_categories->get_categories();
        
        if ($this->is_logged_in()) {
            $this->sys->template->title = 'TimeClock | Employee | Edit';
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'settings'));

        if ($this->is_logged_in()) {
            $this->sys->template->page_id = 1;
            $this->sys->template->paginate_by = $this->model_settings->paginate_by;
            $this->sys->template->employees = $this->model_employees->get_employees(False);
            $this->sys->template->employees_by_id = $this->model_employees->get_employees(True);
            $this->sys->template->employee_id = (int) $employee_id;
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('main', 'employees', 1);

            $this->sys->template->title = 'TimeClock | Employee | Remove';
            $this->sys->template->home_active = 'class="active"';
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
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'settings', 'payPeriod'));
        $this->sys->template->page_id = (int) $page_id;
        $this->sys->template->paginate_by = $this->model_settings->paginate_by;
        $this->sys->template->employee_id = (int) $employee_id;
        
        if ('current' === $pay_period) {
            $this->pay_period = $this->model_payPeriod->get_pay_period();
        } else {
            $this->pay_period = $this->model_payPeriod->get_pay_period($pay_period);
        }
        
        $this->sys->template->pay_period_id = $this->pay_period[2];
        $this->sys->template->pay_period_monday = $this->pay_period[0][0];
        $this->sys->template->pay_period_sunday = $this->pay_period[1][0];
        $this->sys->template->pay_period_table = $this->model_payPeriod->generate_pay_period_table($this->sys->template->employee_id, $this->pay_period[0]);
        
        $this->sys->template->previous_pay_periods_table = $this->model_payPeriod->generate_previous_pay_periods_table($this->sys->template->employee_id, $this->pay_period[2]);
        $this->sys->template->total_hours = $this->model_payPeriod->total_hours_for_pay_period($this->sys->template->employee_id, $this->pay_period[0]);
        
        $this->sys->template->add_date_response = $this->model_payPeriod->add_date_response();
        
        if ($this->is_logged_in()) {
            $this->sys->template->employees_by_id = $this->model_employees->get_employees(True);
            $this->sys->template->pagination = $this->model_renderPage->generate_pagination('employee/view/' . (int) $employee_id . '/' . $pay_period, 'payperiods', (int) $page_id);

            $this->sys->template->title = 'TimeClock | Employee | View';
            $this->sys->template->home_active = 'class="active"';
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
        header('Location: ' . $this->sys->config->timeclock_root);
    }
}//End timeclock_employee

//End File
