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
                        <div class="title col-sm-6">
                            {job['job_name']} ({job['job_uid']}) for {job['client_name']}
                            <?php
                            switch ($this->sys->template->job['status']) {
                                case 'c':
                                    $status = 'Completed | <span class="start_date">' . $this->sys->template->start_date . '</span> - <span class="end_sate">' . $this->sys->template->start_date . "</span>";
                                    break;
                                case 'wip':
                                    $status = 'In Progress | <span class="start_date">' . $this->sys->template->start_date . '</span> - <span class="end_sate">' . $this->sys->template->start_date . "</span>";
                                    break;
                                default: //na
                                    $status = 'Not Started';
                            }
                            ?>
                            (<?php echo $status; ?>)
                        </div>
                        <div class="col-sm-1 col-sm-offset-5">
                            <a href="{timeclock_root}jobs/print_friendly/{job['job_uid']}" target="_blank" class="btn btn-primary btn-sm" role="button">Print</a>
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
                                <th>Employee Name</th>
                                <th>Category</th>
                                <th>Total Hours</th>
                            </tr>
                        </tfoot>
                    </table>
                    <a href="javascript:addDate('{job_id}', 'job')">Add Date</a>
                </div> <!-- END: panel-body -->
            </div> <!-- END: panel -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title row">
                                <div class="title col-sm-12">
                                    Hours Breakdown ({total_hours} Total Hours | <?php echo $this->model_jobs->quoted_hours($this->sys->template->job['job_id']); ?> Quoted Hours)
                                </div>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Worked Hours</th>
                                        <th>Quoted Hours</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($this->sys->template->job['quoted_time'] as $category=>$time) {
                                        if (0 < $time) {
                                            echo '<tr>';
                                                echo '<td>' . $this->sys->template->categories[$category]['category_name'] . '</td>';
                                                if (array_key_exists($category, $this->sys->template->hours_by_category)) {
                                                    $worked = $this->sys->template->hours_by_category[$category];
                                                    echo '<td>' . $this->sys->template->hours_by_category[$category] . '</td>';
                                                } else {
                                                    $worked = 0;
                                                    echo '<td>0</td>';
                                                }
                                                echo '<td>' . $time . '</td>';
                                                
                                                $difference = $time - $worked;
                                                if (0 <= $difference) {
                                                    echo '<td class="green">' . $difference . '</td>';
                                                } else {
                                                    echo '<td class="red">' . $difference . '</td>';
                                                }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Category</th>
                                        <th>Worked Hours</th>
                                        <th>Quoted Hours</th>
                                        <th>Difference</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div> <!-- END: col-sm -->
                    </div> <!-- END: row -->
                </div> <!-- END: panel-body -->
            </div> <!-- END: panel -->
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
<div class="update_info_dialog">
    <form class="form-horizontal update_info_form" method="post" action="">
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
        <div class="group">
            <label>Category</label>
            <select class="form-control" name="category">
                <?php
                foreach ($this->sys->template->categories as $category) {
                    echo '<option value="' . $category['category_id'] . '">' . $category['category_name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="job_id" />
        <input type="hidden" name="update_info" value="update_info" />
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
