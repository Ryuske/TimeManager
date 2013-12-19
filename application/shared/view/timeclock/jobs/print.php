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
<h2>Hours Breakdown ({total_hours} Total Hours | <?php echo $this->model_jobs->quoted_hours($this->sys->template->job['job_id']); ?> Quoted Hours)</h2>
<div class="row">
    <div class="col-sm-6">
        <table class="table">
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
    </div>
</div>