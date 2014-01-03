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
                <h3 class="panel-title">Jobs - Quotes</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped jobs">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Client Name</th>
                            <th>Job Name</th>
                            <th>Worked Time</th>
                            <th>Quoted Time</th>
                            <th>Worked Load</th>
                            <th>Quoted Load</th>
                            <th>Status</th>
                            <th>Attachments</th>
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
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['job_uid']}</td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['client_name']}</td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['job_name']}</td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><?php echo $this->model_jobs->total_hours($this->sys->template->jobs[$i]['job_uid'], false); ?></td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><?php echo $this->model_jobs->quoted_hours($this->sys->template->jobs[$i]['job_id']); ?></td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><?php echo $this->model_jobs->work_load($this->sys->template->jobs[$i]['job_uid'], false, false); ?>%</td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><?php echo $this->model_jobs->work_load($this->sys->template->jobs[$i]['job_uid'], true, false); ?>%</td>
                                <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><div class="<?php echo $status[0]; ?>"><?php echo $status[1]; ?></div></td>
                                <td>
                                    <ul class="icons">
                                        <li class="ui-state-default ui-corner-all" title=".ui-icon-folder-open"><span class="ui-icon ui-icon-folder-open" onclick="jobTableClicked('attachments', '{jobs[<?php echo $i; ?>]['job_uid']}')"></span></li>
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
                            <th>Worked Time</th>
                            <th>Quoted Time</th>
                            <th>Worked Load</th>
                            <th>Quoted Load</th>
                            <th>Status</th>
                            <th>Attachments</th>
                        </tr>
                    </tfoot>
                </table>
                {pagination}
            </div>
        </div>
    </div>
</div>
<div class="job_attachments_dialog">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="attachments">
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>