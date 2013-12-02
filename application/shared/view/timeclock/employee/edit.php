<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>Edit employee</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
            <form class="form" method="post" action="">
                <input name="employee_id" type="hidden" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['id']}" />
                <input class="form-control" name="firstname" type="text" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['firstname']}" required="required" />
                <input class="form-control" name="lastname" type="text" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['lastname']}" required="required" />
                <hr />
                <input class="form-control" name="uid" type="text" placeholder="Employees' Badge UID" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['uid']}" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" />
                    Generate 4-byte hex UID
                </label>
                <hr />
                <input class="form-control" name="username" type="text" placeholder="Optional Username" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['username']}" />
                <input class="form-control" name="password" type="password" placeholder="Password (Required With Username)" />
                {response}
                <input class="form-control" name="edit_employee" type="submit" value="Edit Employee" />
            </form>
        </div>
    </div>
</div>
