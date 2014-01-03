<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="well">
                <p>This system is designed as a management panel for external hardware.</p>
                <a href="{timemanager_root}about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
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
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Employees</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>UID</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Username</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i=0; $i<count($this->sys->template->employees); $i++) {
                            $name = ('first_last' === $this->sys->template->list_employees_as)
                                ? $this->sys->template->employees[$i]['firstname'] . ', ' . $this->sys->template->employees[$i]['lastname']
                                : $this->sys->template->employees[$i]['lastname'] . ', ' . $this->sys->template->employees[$i]['firstname']
                            ;
                            ?>
                            <tr>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')"><?php echo $name; ?></td>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')">{employees[<?php echo $i; ?>]['id']}</td>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')">{employees[<?php echo $i; ?>]['uid']}</td>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')">{employees[<?php echo $i; ?>]['role']}</td>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')">{employees[<?php echo $i; ?>]['department_name']}</td>
                            <td onclick="employeeTableClicked('view', '{employees[<?php echo $i; ?>]['id']}')">{employees[<?php echo $i; ?>]['username']}</td>
                                <td>
                                    <ul class="icons">
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-pencil"><span class="ui-icon ui-icon-pencil" onclick="employeeTableClicked('edit', '{employees[<?php echo $i; ?>]['id']}')"></span></li>
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-trash"><span class="ui-icon ui-icon-trash" onclick="employeeTableClicked('trash', '{employees[<?php echo $i; ?>]['id']}')"></span></li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>UID</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Username</th>
                            <th>Manage</th>
                        </tr>
                    </tfoot>
                </table>
                {pagination}
            </div> <!-- END: panel-body -->
        </div> <!-- END: panel -->
    </div> <!-- END: col-sm-12 -->
</div> <!-- END: row -->
<div class="remove_employee_dialog">
    <div class="dialog_text">
        Are you sure you want to remove <br /> 
        <span class="bold">{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['firstname']} {employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['lastname']}?
    </div>

    <form class="remove_employee_form" method="post" action="">
        <input type="hidden" name="employee_id" value="{employees_by_id[<?php echo $this->sys->template->employee_id; ?>]['id']}" />
        <input type="hidden" name="remove_employee" value="remove" />
    </form>
</div>