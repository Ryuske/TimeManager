<div class="jumbotron">
    <h1>Time Manager</h1>
    <p>Add a new employee</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
        <form class="form" method="post" action="">
                <div class="group">
                    <input class="form-control" name="firstname" type="text" placeholder="Employees' First Name" required="required" />
                    <input class="form-control" name="lastname" type="text" placeholder="Employees' Last Name" required="required" />
                </div>
                <div class="group">
                    <label>
                        Department &raquo;
                        <span class="ui-icon ui-icon-plus form_label_icon" onclick="department_operations('add')"></span>
                        <span class="ui-icon ui-icon-pencil form_label_icon" onclick="department_operations('edit')"></span>
                        <span class="ui-icon ui-icon-trash form_label_icon" onclick="department_operations('remove')"></span>
                    </label>
                    <select class="form-control" name="department">
                        <?php
                        for ($i=0; $i<count($this->sys->template->departments); $i++) {
                            ?>
                            <option value="{departments[<?php echo $i; ?>]['department_id']}">{departments[<?php echo $i; ?>]['department_name']}</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="group">
                    <label>
                        Role &raquo;
                    </label>
                    <select class="form-control" name="role">
                        <option value="none">None</option>
                        <option value="admin">Admin</option>
                        <option value="management">Management</option>
                    </select>
                </div>
                <hr />
                <input class="form-control" name="uid" type="text" placeholder="Employees' Badge UID" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" checked="checked" />
                    Generate 4-byte hex UID
                </label>
                <hr />
                <input class="form-control" name="username" type="text" placeholder="Optional Username" />
                <input class="form-control" name="password" type="password" placeholder="Password (Required With Username)" />
                {response}
                <input class="form-control" name="add_employee" type="submit" value="Add Employee" />
            </form>
        </div>
    </div>
</div>
<div class="department_add_dialog">
    <div class="dialog_text bold">
        Department to Add
    </div>
    <form class="add_department_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="department_name" placeholder="Departments' Name" />
        </div>
        <input type="hidden" name="add_department" value="add_department" />
    </form>
</div> <!-- END department_add_dialog -->

<div class="department_edit_dialog">
    <div class="dialog_text">
        Editing: <span class="bold department_name"></span>
    </div>
    <form class="edit_department_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="department_name" value="" />
        </div>
        <input type="hidden" name="department_id" value="" />
        <input type="hidden" name="edit_department" value="edit_department" />
    </form>
</div> <!-- END department_edit_dialog -->

<div class="department_remove_dialog">
    <div class="dialog_text">
        Are you sure you want to remove department <span class="bold department_name"></span>?
    </div>
    <form class="remove_department_form" method="post" action="">
        <input type="hidden" name="department_id" value="" />
        <input type="hidden" name="remove_department" value="remove_department" />
    </form>
</div> <!-- END department_remove_dialog -->