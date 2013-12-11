<?php
switch ($this->sys->template->job['status']) {
    case 'c':
        $status = 'Completed';
        break;
    case 'wip':
        $status = "In Progress";
        break;
    default: //na
        $status = "Not Started";
}
?>
<h2>{job['job_name']} ({job['job_uid']}) for {job['client_name']} | <?php echo $status; ?> | {start_date} - {last_date}</h2>
<table class="table">
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
<h2>Hours Breakdown ({total_hours} Total Hours)</h2>
<div class="row">
    <div class="col-sm-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->sys->template->hours_by_category as $category=>$time) {
                    echo '<tr>';
                    echo '<td>' . $category . '</td>';
                    echo '<td>' . $time . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Category</th>
                    <th>Total Hours</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>