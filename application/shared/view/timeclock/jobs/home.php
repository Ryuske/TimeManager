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
                    <h3 class="panel-title">Job Mangement</h3>
                </div>
                <div class="panel-body center">
                    <p><a href="{timeclock_root}jobs/add">Add New Job</a></p>
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
                <table class="table table-striped jobs">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Client Name</th>
                            <th>Job Name</th>
                            <th>Status</th>
                            <th>Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i=0; $i<count($this->sys->template->jobs); $i++) {
                            $status = array('', '');
                            switch ($this->sys->template->jobs[$i]['status']) {
                                case 'na':
                                    $status = array('jobs not_started', 'Not Started');
                                    break;
                                case 'wip':
                                    $status = array('jobs wip', 'In Progress');
                                    break;
                                case 'c':
                                    $status = array('jobs completed', 'Completed');
                                    break;
                                default:
                                    //Do nothing
                            }
                            ?>
                            <tr>
                            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_id']}')">{jobs[<?php echo $i; ?>]['job_uid']}</td>
                            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_id']}')">{jobs[<?php echo $i; ?>]['client_name']}</td>
                            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_id']}')">{jobs[<?php echo $i; ?>]['job_name']}</td>
                            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_id']}')"><div class="<?php echo $status[0]; ?>"><?php echo $status[1]; ?></div></td>
                                <td>
                                    <ul class="icons">
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-pencil"><span class="ui-icon ui-icon-pencil" onclick="jobTableClicked('edit', '{jobs[<?php echo $i; ?>]['job_id']}')"></span></li>
                                    <li class="ui-state-default ui-corner-all" title=".ui-icon-trash"><span class="ui-icon ui-icon-trash" onclick="jobTableClicked('trash', '{jobs[<?php echo $i; ?>]['job_id']}')"></span></li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Job ID</th>
                            <th>Client Name</th>
                            <th>Job Name</th>
                            <th>Status</th>
                            <th>Manage</th>
                        </tr>
                    </tfoot>
                </table>
                {pagination}
            </div>
        </div>
    </div>
</div>