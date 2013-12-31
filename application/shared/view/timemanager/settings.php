<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="well">
                <p>This system is designed as a management panel for external hardware.</p>
                <a href="about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Employee Mangement</h3>
                </div>
                <div class="panel-body center">
                    <a href="{timemanager_root}employee/add">Add New Employee</a>
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
                        <option value="none" <?php echo ('none' === $this->sys->template->round_time_by) ? 'selected="selected"' : ''; ?>>None</option>
                        <option value="1" <?php echo ('1' === $this->sys->template->round_time_by) ? 'selected="selected"' : ''; ?>>1 Minute</option>
                        <option value="15" <?php echo ('15' === $this->sys->template->round_time_by) ? 'selected="selected"' : ''; ?>>15 Minutes</option>
                        <option value="30" <?php echo ('30' === $this->sys->template->round_time_by) ? 'selected="selected"' : ''; ?>>30 Minutes</option>
                    </select>
                </div>
                <div class="group">
                    <label>Items Per Page (i.e. employees per page)</label>
                    <input class="form-control" type="text" name="paginate_by" value="<?php echo (int) $this->sys->template->paginate_by; ?>" />
                </div>
                <div class="group">
                    <label>Sort Employees By</label>
                    <select class="form-control" name="sort_employees_by">
                        <option value="last_name" <?php echo ('last_name' === $this->sys->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>Last Name, First Name</option>
                        <option value="first_name" <?php echo ('first_name' === $this->sys->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>First Name, Last Name</option>
                        <option value="uid" <?php echo ('uid' === $this->sys->template->sort_employees_by) ? 'selected="selected"' : ''; ?>>UID</option>
                    </select>
                </div>
                <div class="group">
                    <label>List Employees As</label>
                    <select class="form-control" name="list_employees_as">
                        <option value="last_first" <?php echo ('last_first' === $this->sys->template->list_employees_as) ? 'selected="selected"' : ''; ?>>Last Name, First Name</option>
                        <option value="first_last"<?php echo ('first_last' === $this->sys->template->list_employees_as) ? 'selected="selected"' : ''; ?>>First Name, Last Name</option>
                    </select>
                </div>
                <div class="group">
                    <label>Sort Jobs By</label>
                    <select class="form-control" name="sort_jobs_by">
                        <option value="job_id" <?php echo ('job_id' === $this->sys->template->sort_jobs_by) ? 'selected="selected"' : ''; ?>>Job ID</option>
                        <option value="job_name" <?php echo ('job_name' === $this->sys->template->sort_jobs_by) ? 'selected="selected"' : ''; ?>>Job Name</option>
                        <option value="client_name" <?php echo ('client_name' === $this->sys->template->sort_jobs_by) ? 'selected="selected"' : ''; ?>>Client Name</option>
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