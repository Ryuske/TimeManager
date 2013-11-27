<div class="jumbotron">
    <h1>TimeClock</h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="well">
                <p>This system is designed as a management panel for external timeclock hardware.</p>
                <a href="{timeclock_root}about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
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
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Employees</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee UID</th>
                            <th>Employee Username</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i=0; $i<count($this->system_di->template->all_employees); $i++) {
                            ?>
                            <tr>
                            <td onclick="employeeTableClicked('view', '<?php timeclock_employee::writeout($i, 'id'); ?>')"><?php timeclock_employee::writeout($i, 'lastname'); ?>, <?php timeclock_employee::writeout($i, 'firstname'); ?></td>
                            <td onclick="employeeTableClicked('view', '<?php timeclock_employee::writeout($i, 'id'); ?>')"><?php timeclock_employee::writeout($i, 'uid'); ?></td>
                            <td onclick="employeeTableClicked('view', '<?php timeclock_employee::writeout($i, 'id'); ?>')"><?php timeclock_employee::writeout($i, 'username'); ?></td>
                                <td>
                                    <ul class="icons">
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-pencil"><span class="ui-icon ui-icon-pencil" onclick="employeeTableClicked('edit', '<?php timeclock_employee::writeout($i, 'id'); ?>')"></span></li>
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-trash"><span class="ui-icon ui-icon-trash" onclick="employeeTableClicked('trash', '<?php timeclock_employee::writeout($i, 'id'); ?>')"></span></li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee UID</th>
                            <th>Employee Username</th>
                            <th>Manage</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="remove_employee_dialog">
    <div class="dialog_text">
        Are you sure you want to remove<br />
        <span class="bold"><?php timeclock_employee::writeout($this->system_di->template->employee_id, 'firstname', 'by_id'); ?> <?php timeclock_employee::writeout($this->system_di->template->employee_id, 'lastname', 'by_id'); ?></span>?
    </div>

    <form class="remove_employee_form" method="post" action="">
        <input type="hidden" name="employee_id" value="<?php timeclock_employee::writeout($this->system_di->template->employee_id, 'id', 'by_id'); ?>" />
        <input type="hidden" name="remove_employee" value="remove" />
    </form>
</div>