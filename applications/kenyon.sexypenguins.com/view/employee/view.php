<?php
$date_format = 'm/d/y';
?>

<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>View employee</p>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title row">
                        <div class="title col-sm-5">
                            <?php employee::writeout($this->system_di->template->employee_id, 'firstname', 'by_id'); ?>
                            <?php employee::writeout($this->system_di->template->employee_id, 'lastname', 'by_id'); ?>
                            (<?php echo date($date_format, $this->system_di->template->pay_period_monday); ?> -
                            <?php echo date($date_format, $this->system_di->template->pay_period_sunday); ?>) -
                            <?php echo (float) $this->system_di->template->total_hours; ?> hours
                        </div>
                        <div class="col-sm-1 col-sm-offset-6">
                            <a href="{timeclock_root}payperiod/print_friendly/<?php employee::writeout($this->system_di->template->employee_id, 'id', 'by_id'); ?>/<?php echo $this->system_di->template->pay_period_monday; ?>" target="_blank" class="btn btn-primary btn-sm" role="button">Print</a>
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
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            {pay_period_table}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Total Hours</th>
                            </tr>
                        </tfoot>
                    </table>
                    <a href="javascript:addDate({pay_period_id})">Add Date</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Previous Pay Periods</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <?php echo $this->system_di->template->previous_pay_periods_table[0]; ?>
                                        <tfoot>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-sm-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <?php echo $this->system_di->template->previous_pay_periods_table[1]; ?>
                                        <tfoot>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-sm-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <?php echo $this->system_di->template->previous_pay_periods_table[2]; ?>
                                        <tfoot>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-sm-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <?php echo $this->system_di->template->previous_pay_periods_table[3]; ?>
                                        <tfoot>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="update_time_dialog">
    <form class="update_time_form" method="post" action="">
        <div class="dialog_text">
            <div class="bold dialog_title"></div>
            <input class="dialog_input" type="text" name="time" value="3:00pm" />
        </div>
        <input type="hidden" name="employee_id" value="<?php employee::writeout($this->system_di->template->employee_id, 'id', 'by_id'); ?>" />
        <input type="hidden" name="date" />
        <input type="hidden" name="time_index" />
        <input type="hidden" name="time_operation" />
        <input type="hidden" name="update_time" value="update" />
    </form>
</div>
<div class="add_date_dialog">
    <form class="add_date_form" method="post" action="">
        <div class="dialog_text">
            <div class="date"></div>
            <div class="hide start_date"><?php echo date('m/d/Y', $this->system_di->template->pay_period_monday); ?></div>
            <input class="date_to_add" type="hidden" name="date" />
        </div>
        <input type="hidden" name="employee_id" value="<?php employee::writeout($this->system_di->template->employee_id, 'id', 'by_id'); ?>" />
        <input type="hidden" name="pay_period" />
        <input type="hidden" name="add_date" value="add_date" />
    </form>
</div>
