<div class="jumbotron">
    <h1>Time Manager</h1>
    <p>Quoting Defaults</p>
</div>
<div class="row">
    <div class="col-lg-7 col-lg-offset-2">
        <div class="well">
        <form class="form" method="post" action="#response">
            <div class="group">
                <label>Hourly Values ($) by Department</label>
                <?php
                $i = 0;
                
                foreach ($this->sys->template->departments as $department) {
                    if (is_integer($i/2)) {
                        echo '<div class="row bottom-spacing">';
                    }
                    
                    echo '<div class="col-sm-6">';
                    echo '<label class="col-sm-12">' . $department['department_name'] . '</label>';
                    echo '<div class="col-sm-6"><span>Charged</span>
                        <input
                            class="form-control"
                            name="charged_hourly_value[' . $department['department_id'] . ']"
                            type="text"
                            value="' . number_format($department['charged_hourly_value'], 2) . '"
                            placeholder="0.00"
                        /></div>';
                    echo '<div class="col-sm-6"><span>Payed</span>
                        <input
                            class="form-control"
                            name="payed_hourly_value[' . $department['department_id'] . ']"
                            type="text"
                            value="' . number_format($department['payed_hourly_value'], 2) . '"
                            placeholder="0.00"
                        /></div>';
                    echo '</div>';
                    
                    if (!is_integer($i/2)) {
                        echo '</div>';
                    }
                    
                    $i++;
                }
                
                if (!is_integer($i/2)) {
                    echo '</div>';
                }
                ?>
            </div>
            {response}
            <div class="row">
                <div class="col-sm-4 col-sm-offset-8">
                    <input class="form-control" name="add_job" type="submit" value="Update Defaults" />
                </div>
            </div>
            </form>
        </div>
    </div>
</div>