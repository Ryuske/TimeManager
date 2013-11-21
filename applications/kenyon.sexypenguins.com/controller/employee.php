<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/15/13
 * @Date Modified: 11/21/13
 * @Purpose: Employee controller
 * @Version: 1.0
 */

/**
 * @Purpose: Employee Controller
 * @Extends controller
 */
class employee extends controller {
    /**
     * @Purpose: This function is used to determin if the user is logged in or not
     * @Access: Public
     */
    protected function is_logged_in() {
        return $this->logged_in->status();
    } //End logged_in
    
    /**
     * @Purpose: Used to get & return employees to the view
     * @Access: Protected
     */
    protected function employees($by_id=True) {
        $this->employees = $this->load_model('employees', $this->system_di->config->timeclock_subdirectories);
        if (!is_bool($by_id)) {
            die(debug_print_backtrace());
        }
        if (True === $by_id) {
            return $this->employees->get_employees(True);
        } else {
            return $this->employees->get_employees();
        }
    } //End employees
    
    /**
     * @Purpose: Used to make echo'ing out the values in the view a little "prettier"
     * @Access: Public
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
    } //End employee

    /**
     * @Purpose: Default function to be run when class is called
     * @Access: Public
     */
    public function index() {
        $this->load_home();
    }//End index

    /**
     * @Purpose: Function that is run when employee/add is accessed
     * @Access: Public
     */
    public function add() {
        $this->system_di->template->response = '';
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        $this->employees = $this->load_model('employees', $this->system_di->config->timeclock_subdirectories);

        if ($this->is_logged_in()) {
            $this->system_di->template->title = 'TimeClock | Employee | Add';
        } else {
            $this->load_home();
        }

        $this->render('employee_add');
    } //End add
    
    /**
     * @Purpose: Used for editing existing employees (employee/edit/x)
     * @Access: Public
     */
    public function edit($employee_id) {
        $this->system_di->template->response = '';
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        
        $this->employees = $this->load_model('employees', $this->system_di->config->timeclock_subdirectories);
        $this->system_di->template->all_employees_by_id = $this->employees->get_employees(True);
        $this->system_di->template->employee_id = (int) $employee_id;
        
        if ($this->is_logged_in()) {
            $this->system_di->template->title = 'TimeClock | Employee | Edit';
        } else {
            $this->load_home();
        }

        $this->render('employee_edit');
    } //End edit
    
    /**
     * @Purpose: Used to remove an existing employee (employee/remove/x)
     * @Access: Public
     */
    public function remove($employee_id) {
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);

        if ($this->is_logged_in()) {
            $this->system_di->template->all_employees = $this->employees(False);
            $this->system_di->template->all_employees_by_id = $this->employees(True);
            $this->system_di->template->employee_id = (int) $employee_id;

            $this->system_di->template->title = 'TimeClock | Employee | Remove';
            $this->system_di->template->home_active = 'class="active"';
            $parse = 'employee_remove';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->render($parse, $full_page);
    } //End remove

    /**
     * @Purpose: Used to remove an existing employee (employee/remove/x)
     * @Access: Public
     */
    public function view($employee_id, $pay_period='current') {
        $this->logged_in = $this->load_model('loggedIn', $this->system_di->config->timeclock_subdirectories);
        $this->pay_period_model = $this->load_model('payPeriod', $this->system_di->config->timeclock_subdirectories);
        
        $this->system_di->template->employee_id = (int) $employee_id;
        
        if ('current' === $pay_period) {
            $this->pay_period = $this->pay_period_model->get_pay_period();
        } else {
            $this->pay_period = $this->pay_period_model->get_pay_period($pay_period);
        }
        
        $this->system_di->template->pay_period_id = $this->pay_period[2];
        $this->system_di->template->pay_period_monday = $this->pay_period[0][0];
        $this->system_di->template->pay_period_sunday = $this->pay_period[1][0];
        $this->system_di->template->pay_period_table = $this->pay_period_model->generate_pay_period_table($this->system_di->template->employee_id, $this->pay_period[0]);
        
        $this->system_di->template->previous_pay_periods_table = $this->pay_period_model->generate_previous_pay_periods_table($this->system_di->template->employee_id, $this->pay_period[2]);
        $this->system_di->template->total_hours = $this->pay_period_model->total_hours_for_pay_period($this->system_di->template->employee_id, $this->pay_period[0]);
        
        if ($this->is_logged_in()) {
            $this->system_di->template->all_employees = $this->employees(False);
            $this->system_di->template->all_employees_by_id = $this->employees(True);

            $this->system_di->template->title = 'TimeClock | Employee | View';
            $this->system_di->template->home_active = 'class="active"';
            $parse = 'employee_view';
            $full_page = True;
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->render($parse, $full_page);
    } //End view
    
    /**
     * @Purpose: Function used to return you to the home controller if there is a problem
     * @Access: Protected
     */
    protected function load_home() {
        header('Location: ' . $this->system_di->config->timeclock_root);
    } //End load_home

    /**
     * @Purpose: Used to load pages, including the HTML headers and footers
     */
    protected function render($page, $full_view = True) {
        $renderPage = $this->load_model('renderPage', $this->system_di->config->timeclock_subdirectories);

        $renderPage->parse($page, $full_view);
    } //End render
}//End employee

//End File
