<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <p>This system is designed as a management panel for external hardware.</p>
                <a href="{timemanager_root}about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
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
                        </tr>
                    </tfoot>
                </table>
                {pagination}
            </div>
        </div>
    </div>
</div>