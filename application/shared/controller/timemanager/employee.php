<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/15/13
 * Date Modified: 12/31/13
 * Purpose: Employee controller
 */

/**
 * Purpose: Employee Controller
 */
class timemanager_employee extends controller {
    /**
     * Purpose: Primarily used to load models based on $this->_dependencies;
     */
    public function load_dependencies($dependencies) {
        foreach ($dependencies as $dependency) {
            $name = 'model_' . $dependency;
            $this->$name = $this->load_model($this->sys->config->timemanager_subdirectories . '_' . $dependency);
            $this->sys->template->$name = $this->$name;
        }
    }
    
    /**
     * Purpose: This function is used to determin if the user is logged in or not
     */
    protected function is_logged_in() {
        return $this->model_loggedIn->status();
    }

    /**
     * Purpose: Default function to be run when class is called
     */
    public function index() {
        $this->load_home();
    }

    /**
     * Purpose: Function that is run when employee/add is accessed
     */
    public function add() {
        $this->sys->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'settings', 'categories', 'employees'));

        if ($this->is_logged_in()) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->admin         = $this->model_settings->is_admin();
            $this->sys->template->title         = 'Time Manager | Employee | Add';
            $this->sys->template->categories    = $this->model_categories->get();
            
            $parse = 'employee_add';
        } else {
            $this->load_home();
        }

        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * Purpose: Used for editing existing employees (employee/edit/x)
     */
    public function edit($employee_id) {
        $this->sys->template->response = '';
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'categories', 'settings'));

        $this->sys->template->employees_by_id   = $this->model_employees->get(true);
        $this->sys->template->employee_id       = (int) $employee_id;
        $this->sys->template->categories        = $this->model_categories->get();
        
        if ($this->is_logged_in() && array_key_exists($this->sys->template->employee_id, $this->sys->template->employees_by_id)) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->admin = $this->model_settings->is_admin();
            $this->sys->template->title = 'Time Manager | Employee | Edit';
            $parse = 'employee_edit';
        } else {
            $this->load_home();
        }

        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * Purpose: Used to remove an existing employee (employee/remove/x)
     */
    public function remove($employee_id) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'settings'));
        
        $this->sys->template->employees_by_id = $this->model_employees->get(true);
        $this->sys->template->employee_id = (int) $employee_id;
            
        if ($this->is_logged_in() && array_key_exists($this->sys->template->employee_id, $this->sys->template->employees_by_id)) {
            if (!$this->model_settings->is_admin()) {
                $this->load_home();
                return true;
            }
            
            $this->sys->template->admin             = $this->model_settings->is_admin();
            $this->sys->template->page_id           = 1;
            $this->sys->template->paginate_by       = $this->model_settings->paginate_by;
            $this->model_employees->get_employees_for_view();
            $this->sys->template->list_employees_as = $this->model_settings->list_employees_as;
            $this->sys->template->pagination        = $this->model_renderPage->generate_pagination('main', 'employees', 1);

            $this->sys->template->title             = 'Time Manager | Employee | Remove';
            $this->sys->template->home_active       = 'class="active"';
            $parse = 'employee_remove';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, True);
    }

    /**
     * Purpose: Used to view an existing employees time card
     */
    public function view($employee_id, $pay_period='current', $page_id = 1) {
        $this->load_dependencies(array('loggedIn', 'renderPage', 'employees', 'settings', 'payPeriod'));
        
        $this->sys->template->page_id       = (int) $page_id;
        $this->sys->template->paginate_by   = $this->model_settings->paginate_by;
        $this->sys->template->employee_id   = (int) $employee_id;
        
        if ('current' === $pay_period) {
            $this->pay_period = $this->model_payPeriod->get_pay_period();
        } else {
            $this->pay_period = $this->model_payPeriod->get_pay_period($pay_period);
        }
        
        $this->sys->template->pay_period_id     = $this->pay_period[2];
        $this->sys->template->pay_period_monday = $this->pay_period[0][0];
        $this->sys->template->pay_period_sunday = $this->pay_period[1][0];
        $this->sys->template->pay_period_table  = $this->model_payPeriod->generate_pay_period_table($this->sys->template->employee_id, $this->pay_period[0]);
        
        $this->sys->template->previous_pay_periods_table    = $this->model_payPeriod->generate_previous_pay_periods_table($this->sys->template->employee_id, $this->pay_period[2]);
        $this->sys->template->total_hours                   = $this->model_payPeriod->total_hours_for_pay_period($this->sys->template->employee_id, $this->pay_period[0]);
        $this->sys->template->add_date_response             = $this->model_payPeriod->add_date_response();
        
        if ($this->is_logged_in()) {
            $this->sys->template->admin             = $this->model_settings->is_admin();
            $this->sys->template->employees_by_id   = $this->model_employees->get(true);
            $this->sys->template->pagination        = $this->model_renderPage->generate_pagination('employee/view/' . (int) $employee_id . '/' . $pay_period, 'payperiods', (int) $page_id);

            $this->sys->template->title         = 'Time Manager | Employee | View';
            $this->sys->template->home_active   = 'class="active"';
            $parse = ($this->sys->template->admin) ? 'admin_employee_view' : 'employee_view';
        } else {
            $this->load_home();
        }

        //Parses the HTML from the view
        $this->model_renderPage->parse($parse, True);
    }
    
    /**
     * Purpose: Used to send data back using the RESTFUL API
     */
    public function rx($uid, $data, $pay_period='current') {
        $this->load_dependencies(array('payPeriod', 'settings'));
        
        $check_employee_id = $this->sys->db->query("SELECT `employee_uid` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) substr($uid, 0, 6)
        ));
        
        if (!empty($check_employee_id)) {
            $uid = $check_employee_id[0]['employee_uid'];
        }
        
        $employee = $this->sys->db->query("
            SELECT *
            FROM `employees` AS employee
                JOIN `categories` AS category on category.category_id=employee.category_id
                LEFT JOIN `job_punch` AS job on job.employee_id=employee.employee_id
                LEFT JOIN `jobs` AS jobs on jobs.job_uid=job.job_id
            WHERE `employee_uid`=:uid
            ORDER BY job.punch_id DESC
        ", array(
            ':uid' => substr($uid, 0, 64)
        ));
        
        $response = 'Error';
        
        if (empty($employee)) {
            $this->sys->template->response = $response;
            $this->sys->template->parse($this->sys->config->timemanager_subdirectories . '_payperiod_response');
            
            return False;
        }
        
        $pay_period_query = $this->sys->db->query("SELECT `time`,`date`,`operation` FROM `employee_punch` WHERE `employee_id`=:employee_id ORDER BY `employee_punch_id` DESC", array(
            ':employee_id' => (int) substr($employee[0]['employee_id'], 0, 6)
        ));
        
        $pay_period = $this->model_payPeriod->get_pay_period($pay_period);

        switch ($data) {
            case 'name':
                $response = $employee[0]['employee_firstname'] . ' ' . $employee[0]['employee_lastname'];
                break;
            case 'uid':
                $response = $employee[0]['employee_uid'];
                break;
            case 'role':
                $response = $employee[0]['employee_role'];
                break;
            case 'category':
                $response = $employee[0]['category_name'];
                break;
            case 'last_job':
                $response = $employee[0]['job_name'];
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
        
        $this->sys->template->response = $response;
        $this->sys->template->parse($this->sys->config->timemanager_subdirectories . '_payperiod_response');
        return True;
    }
    
    /**
     * Purpose: Function used to return you to the home controller if there is a problem
     */
    protected function load_home() {
        header('Location: ' . $this->sys->config->timemanager_root);
    }
}

//End File
