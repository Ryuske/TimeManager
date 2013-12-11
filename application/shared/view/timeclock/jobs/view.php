<?php
$date_format = 'm/d/y';
?>

<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>View job</p>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title row">
                        <div class="title col-sm-5">
                            {job['job_name']} ({job['job_uid']}) for {job['client_name']}
                            (<span class="start_date">{start_date}</span> -
                            <span class="end_date">{last_date}</span>) -
                            <?php //echo (float) $this->sys->template->total_hours; ?> hours
                        </div>
                        <div class="col-sm-1 col-sm-offset-6">
                            <a href="{timeclock_root}jobs/print_friendly/{jobs['job_id']}" target="_blank" class="btn btn-primary btn-sm" role="button">Print</a>
                        </div>
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Employee UID</th>
                                <th>Employee Name</th>
                                <th>Category</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            {job_table}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Employee UID</th>
                                <th>Employee Name</th>
                                <th>Category</th>
                                <th>Total Hours</th>
                            </tr>
                        </tfoot>
                    </table>
                    <a href="javascript:addDate({job_id}, 'job')">Add Date</a>
                </div>
            </div> <!-- END: panel-body -->
        </div> <!-- END: well -->
    </div> <!-- END: col-sm-12 -->
</div> <!-- END: row -->
<div class="update_time_dialog">
    <form class="update_time_form" method="post" action="">
        <div class="dialog_text">
            <div class="bold dialog_title"></div>
            <input class="dialog_input" type="text" name="time" value="3:00pm" />
        </div>
        <input type="hidden" name="id" />
        <input type="hidden" name="operation" />
        <input type="hidden" name="job_id" />
        <input type="hidden" name="update_time" value="update" />
    </form>
</div>
<div class="add_date_dialog">
    <form class="form-horizontal add_date_form" method="post" action="">
        <div class="group">
            <div class="dialog_text">
                <div class="date"></div>
                <input class="date_to_add" type="hidden" name="date" />
            </div>
        </div>
        <div class="group">
            <label>Employee</label>
            <select class="form-control" name="employee">
                <?php
                foreach ($this->sys->template->employees as $employee) {
                    echo '<option value="' . $employee['id'] . '">' . $employee['firstname'] . ' ' . $employee['lastname'] . '</option>';
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="id" />
        <input type="hidden" name="add_date" value="add_date" />
    </form>
</div>
<div class="add_date_response">
    <div class="add_date_response_text">
        All previous in/out slots must be filled
    </div>
</div>
{add_date_response}
