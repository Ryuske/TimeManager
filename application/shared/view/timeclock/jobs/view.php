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
                                        <th>Worked Load</th>
                                        <th>Quoted Load</th>
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
                                                
                                                if (array_key_exists($category, $this->sys->template->worked_load)) {
                                                    echo '<td>' . $this->sys->template->worked_load[$category] . '%</td>';
                                                } else {
                                                    echo '<td>0%</td>';
                                                }
                                                if (array_key_exists($category, $this->sys->template->quoted_load)) {
                                                    echo '<td>' . $this->sys->template->quoted_load[$category] . '%</td>';
                                                } else {
                                                    echo '<td>0%</td>';
                                                }
                                                
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
                                        <th>Worked Load</th>
                                        <th>Quoted Load</th>
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
