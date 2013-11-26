<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="well">
            <form class="form-horizontal" name="install_form" method="post" action="">
                <h2 class="form-install-heading">Welcome | Installation</h2>
                <div class="group">
                    <input class="form-control" name="firstname" type="text" placeholder="Admin First Name" required="required" />
                </div>
                <div class="group">
                    <input class="form-control" name="lastname" type="text" placeholder="Admin Last Name" required="required" />
                </div>
                <div class="group">
                    <input class="form-control" name="username" type="text" placeholder="Admin Username" required="required" />
                </div>
                <div class="group">
                    <input class="form-control" name="password" type="password" placeholder="Admin Password" required="required" />
                </div>
                <hr />
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
                <input type="hidden" name="update_settings" value="update_settings" />
                <div class="form_failed">{install_error}</div>
                <button type="submit" name="install" class="btn btn-primary">Install</button>
            </form>
        </div>
    </div>
</div>
