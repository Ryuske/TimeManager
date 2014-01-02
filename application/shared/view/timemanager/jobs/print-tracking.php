<h2>Job Tracking</h2>
<table class="table jobs">
    <thead>
        <tr>
            <th>Job ID</th>
            <th>Client Name</th>
            <th>Job Name</th>
            <th>Quantity</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Last Operation</th>
            <th>Status</th>
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
            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['job_quantity']}</td>
            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['job_start_date']}</td>
            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')">{jobs[<?php echo $i; ?>]['job_due_date']}</td>
            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><?php echo $this->model_jobs->last_operation($this->sys->template->jobs[$i]['job_id']); ?></td>
            <td onclick="jobTableClicked('view', '{jobs[<?php echo $i; ?>]['job_uid']}')"><div class="<?php echo $status[0]; ?>"><?php echo $status[1]; ?></div></td>
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
            <th>Quantity</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Last Operation</th>
            <th>Status</th>
        </tr>
    </tfoot>
</table>