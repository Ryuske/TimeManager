<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>Edit employee</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
            <form class="form" method="post" action="">
                <input name="id" type="hidden" value="<?php employee::writeout($this->system_di->template->employee_id, 'id', 'by_id'); ?>" />
                <input class="form-control" name="firstname" type="text" value="<?php employee::writeout($this->system_di->template->employee_id, 'firstname', 'by_id'); ?>" required="required" />
                <input class="form-control" name="lastname" type="text" value="<?php employee::writeout($this->system_di->template->employee_id, 'lastname', 'by_id'); ?>" required="required" />
                <hr />
                <input class="form-control" name="uid" type="text" placeholder="Employees' Badge UID" value="<?php employee::writeout($this->system_di->template->employee_id, 'uid', 'by_id'); ?>" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" />
                    Generate 4-byte hex UID
                </label>
                <hr />
                <input class="form-control" name="username" type="text" placeholder="Optional Username" value="<?php employee::writeout($this->system_di->template->employee_id, 'username', 'by_id'); ?>" />
                <input class="form-control" name="password" type="password" placeholder="Password (Required With Username)" />
                {response}
                <input class="form-control" name="edit_employee" type="submit" value="Edit Employee" />
            </form>
        </div>
    </div>
</div>
