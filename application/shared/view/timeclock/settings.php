<div class="jumbotron">
    <h1>TimeClock</h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="well">
                <p>This system is designed as a management panel for external timeclock hardware.</p>
                <a href="about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Employee Mangement</h3>
                </div>
                <div class="panel-body center">
                    <a href="employee/add">Add New Employee</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="well">
            <form class="form-horizontal" name="settings_form" method="post" action="">
                <div class="group">
                    <label>Round Time By</label>
                    <select class="form-control" name="round_time_by">
                        <option value="none" <?php echo ('none' === $this->system_di->template->round_time_by) ? 'selected="selected"' : ''; ?>>None</option>
                        <option value="1" <?php echo ('1' === $this->system_di->template->round_time_by) ? 'selected="selected"' : ''; ?>>1 Minute</option>
                        <option value="15" <?php echo ('15' === $this->system_di->template->round_time_by) ? 'selected="selected"' : ''; ?>>15 Minutes</option>
                        <option value="30" <?php echo ('30' === $this->system_di->template->round_time_by) ? 'selected="selected"' : ''; ?>>30 Minutes</option>
                    </select>
                </div>
                <div class="group">
                    <label>Items Per Page (i.e. employees per page)</label>
                    <input class="form-control" type="text" name="paginate_by" value="<?php echo (int) $this->system_di->template->paginate_by; ?>" />
                </div>
                <div class="group">
                    <label>Sort Employees By</label>
                    <select class="form-control" name="sort_employees_by">
                        <option value="last_name" <?php echo ('last_name' === $this->system_di->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>Last Name, First Name</option>
                        <option value="first_name" <?php echo ('first_name' === $this->system_di->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>First Name, Last Name</option>
                        <option value="uid" <?php echo ('uid' === $this->system_di->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>UID</option>
                    </select>
                </div>
                <div class="group">
                    <label>List Employees As</label>
                    <select class="form-control" name="list_employees_as">
                        <option value="last_first" <?php echo ('last_first' === $this->system_di->template->list_employees_by) ? 'selected="selected"' : ''; ?>>Last Name, First Name</option>
                        <option value="first_last"<?php echo ('first_last' === $this->system_di->template->list_employees_by) ? 'selected="selected"' : ''; ?>>First Name, Last Name</option>
                    </select>
                </div>
                {update_status}
                <button type="submit" name="update_settings" class="btn btn-primary">Submit</button>
            </form>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                </div>
            </div>
        </div>
    </div>
</div>