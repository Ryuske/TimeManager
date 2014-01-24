<?php
$quoted_time = $this->model_quotes->get_time_quote($this->sys->template->departments, $this->sys->template->quote);
$quoted_material = $this->model_quotes->get_material($this->sys->template->quote['quote']['quoted_material'], 'quoted');
$actual_material = $this->model_quotes->get_material($this->sys->template->quote['quote']['actual_material'], 'actual');
$quoted_outsource = $this->model_quotes->get_outsource($this->sys->template->quote['quote']['quoted_outsource'], 'quoted');
$actual_outsource = $this->model_quotes->get_outsource($this->sys->template->quote['quote']['actual_outsource'], 'actual');
$this->model_quotes->get_information($this->sys->template->quote['quote']);
$total_quoted_cost = number_format($quoted_time['total_cost'] + $quoted_material['quoted_total'] + $quoted_outsource['quoted_total'] + $this->model_quotes->get_shared_data('sheets_total_cost', 0), 2);
$total_actual_cost = number_format($this->sys->template->total_worked_hours['cost'] + $quoted_material['original_total'] + $quoted_outsource['original_total'] + $this->model_quotes->get_shared_data('sheets_total_cost', 0, 'actual'), 2);
$profit = number_format($total_quoted_cost - $total_actual_cost, 2);
?>

<script>
    jQuery(document).ready(function() {
        jQuery(window.location.hash).collapse('show');
    });
</script>
<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="well">
        <p>
            {quote['job']['client_name']} {quote['job']['job_name']} | Quantity: {quote['job']['job_quantity']} | Job #: {quote['job']['job_uid']}
        </p>
        <p>
            Quoted Total: $<?php echo $total_quoted_cost; ?>
            &bull;
            Profit: <?php echo (0 >= $profit) ? '<span class="red">$' . $profit . '</span>' : '<span class="green">$' . $profit . '</span>' ; ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel-group" id="accordion">
            <form name="update_quote_form" method="post" action="">
                <input name="update_quote" type="hidden" value="update_quote" />
                <input name="job_id" type="hidden" value="{quote['job']['job_id']}" />
                <div class="well well-sm"> <!-- Time Stats -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <a id="time"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseTimeQuote" href="#collapseTimeQuote">
                                    Time Quote | Total Time: <?php echo number_format(array_shift($quoted_time), 2); ?> Hours | Total Cost: $<?php echo number_format(array_shift($quoted_time), 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseTimeQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Cost Per Hour</th>
                                            <th>Initial Time</th>
                                            <th>Initial Cost</th>
                                            <th>Repeat Time</th>
                                            <th>Repeat Cost</th>
                                            <th>Total Time</th>
                                            <th>Total Repeat Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($quoted_time as $time) {
                                            if (0 == $time['total_individual_cost']) {
                                                $total_individual_cost = 'N/A';
                                            } else {
                                                $total_individual_cost = '$' . $time['total_individual_cost'];
                                            }
                                            
                                            echo '<tr>';
                                                echo '<td>' . $time['department_name'] . '</td>';
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'hourly_value\')">
                                                        $<span class="time_hourly_value_' . $time['department_id'] . '">' . $time['hourly_value'] . '</span>
                                                        <input class="time_hourly_value_' . $time['department_id'] . '"
                                                            name="quotes[time][' . $time['department_id'] . '][hourly_value]"
                                                            type="hidden"
                                                            value="' . $time['hourly_value'] . '"
                                                        /></td>';
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'initial_time\')">
                                                        <span class="time_initial_time_' . $time['department_id'] . '">' . $time['initial_time'] . '</span> hours
                                                        <input class="time_initial_time_' . $time['department_id'] . '"
                                                            name="quotes[time][' . $time['department_id'] . '][initial_time]"
                                                            type="hidden"
                                                            value="' . $time['initial_time'] . '"
                                                        /></td>';
                                                echo '<td>$' . $time['initial_cost'] . '</td>';
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'repeat_time\')">
                                                        <span class="time_repeat_time_' . $time['department_id'] . '">' . $time['repeat_time'] . '</span> hours
                                                        <input class="time_repeat_time_' . $time['department_id'] . '"
                                                            name="quotes[time][' . $time['department_id'] . '][repeat_time]"
                                                            type="hidden"
                                                            value="' . $time['repeat_time'] . '"
                                                        /></td>';
                                                echo '<td>$' . $time['repeat_cost'] . '</td>';
                                                echo '<td>' . $time['total_time'] . ' hours</td>';
                                                echo '<td>' . $total_individual_cost . '</td>';
                                                echo '<td>$' . $time['total_cost'] . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Department</th>
                                            <th>Cost Per Hour</th>
                                            <th>Initial Time</th>
                                            <th>Initial Cost</th>
                                            <th>Repeat Time</th>
                                            <th>Repeat Cost</th>
                                            <th>Total Time</th>
                                            <th>Total Repeat Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div> <!-- END: time quote -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <a id="time"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseTimeActual" href="#collapseTimeActual">
                                    Time Actual | Total Time: <?php echo number_format($this->sys->template->total_worked_hours['hours'], 2); ?> hours | Total Cost: $<?php echo number_format($this->sys->template->total_worked_hours['cost'], 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseTimeActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Cost</th>
                                            <th>Quoted Time</th>
                                            <th>Worked Time</th>
                                            <th>Time Difference</th>
                                            <th>Cost Difference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--<tr>
                                            <td>Engineering</td>
                                            <td>$30</td>
                                            <td>.5</td>
                                            <td>.25</td>
                                            <td><span class="green">.25</span></td>
                                            <td><span class="green">$22.5</span></td>
                                        </tr>-->
                                        <?php
                                        foreach ($this->sys->template->departments as $department) {
                                            $worked = (array_key_exists($department['department_id'], $this->sys->template->hours_by_department))
                                                ? $this->sys->template->hours_by_department[$department['department_id']]
                                                : 0;
                                            $total_cost = (float) $department['payed_hourly_value'] * $worked;
                                            $quoted_time_work = (array_key_exists($department['department_id'], $quoted_time)) ? $quoted_time[$department['department_id']]['total_time'] : 0;
                                            $quoted_time_cost = (array_key_exists($department['department_id'], $quoted_time)) ? $quoted_time[$department['department_id']]['total_cost'] : 0;
                                            $time_difference = (0 > ($quoted_time_work - $worked))
                                                ? '<span class="red">' . $quoted_time_work - $worked . '</span>'
                                                : '<span class="green">' . $quoted_time_work - $worked . '</span>';
                                            $cost_difference = (0 > ($quoted_time_cost - $total_cost))
                                                ? '<span class="red">' . $quoted_time_cost - $total_cost . '</span>'
                                                : '<span class="green">' . $quoted_time_cost - $total_cost . '</span>';
                                            
                                            echo '<tr>';
                                                echo '<td>' . $department['department_name'] . '</td>';
                                                echo '<td>$' . number_format($department['payed_hourly_value'], 2) . '</td>';
                                                echo '<td>' . $quoted_time_work . '</td>';
                                                echo '<td>' . $worked . '</td>';
                                                echo '<td>' . $time_difference . '</td>';
                                                echo '<td>' . $cost_difference . '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Department</th>
                                            <th>Cost</th>
                                            <th>Worked Time</th>
                                            <th>Total Time</th>
                                            <th>Time Difference</th>
                                            <th>Cost Difference</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div> <!-- END: actual quote -->
                </div> <!-- END: time stats -->
                <div class="well well-sm"> <!-- Materials Stats -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <a id="materials"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseMaterialsQuote" href="#collapseMaterialsQuote">
                                    Materials Quoted | Quoted Total: $<?php echo number_format(array_shift($quoted_material), 2); ?> | Original Total: $<?php echo number_format(array_shift($quoted_material), 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseMaterialsQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Vendor</th>
                                            <th>Individual Quantity</th>
                                            <th>Total Quantity</th>
                                            <th>Material Cost (each)</th>
                                            <th>Mark-up</th>
                                            <th>Total Price</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($quoted_material)) {
                                            foreach ($quoted_material as $material) {
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="quoted_material_description_' . $material['material_id'] . '">' . $material['description'] . '</span>
                                                            <input class="quoted_material_description_' . $material['material_id'] . '"
                                                                name="quotes[material][' . $material['material_id'] . '][description]"
                                                                type="hidden"
                                                                value="' . $material['description'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="quoted_material_vendor_' . $material['material_id'] . '">' . $material['vendor'] . '</span>
                                                            <input class="quoted_material_vendor_' . $material['material_id'] . '"
                                                                name="quotes[material][' . $material['material_id'] . '][vendor]"
                                                                type="hidden"
                                                                value="' . $material['vendor'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="quoted_material_individual_quantity_' . $material['material_id'] . '">'
                                                                . $material['individual_quantity'] .
                                                            '</span>
                                                            <input class="quoted_material_individual_quantity_' . $material['material_id'] . '"
                                                                name="quotes[material][' . $material['material_id'] . '][individual_quantity]"
                                                                type="hidden"
                                                                value="' . $material['individual_quantity'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">'
                                                            . $material['total_quantity'] .
                                                        '</td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            $<span class="quoted_material_cost_' . $material['material_id'] . '">' . $material['cost'] . '</span>
                                                            <input class="quoted_material_cost_' . $material['material_id'] . '"
                                                                name="quotes[material][' . $material['material_id'] . '][cost]"
                                                                type="hidden"
                                                                value="' . $material['cost'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="quoted_material_markup_' . $material['material_id'] . '">' . $material['markup'] . '</span>%
                                                            <input class="quoted_material_markup_' . $material['material_id'] . '"
                                                                name="quotes[material][' . $material['material_id'] . '][markup]"
                                                                type="hidden"
                                                                value="' . $material['markup'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            $' . $material['total_cost'] .
                                                        '</td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Description</th>
                                            <th>Vendor</th>
                                            <th>Individual Quantity</th>
                                            <th>Total Quantity</th>
                                            <th>Material Cost (each)</th>
                                            <th>Mark-up</th>
                                            <th>Total Price</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.quote_add_material_dialog').dialog('open');">Add Material</a>
                            </div>
                        </div>
                    </div> <!-- END: materials quoted -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <a id="materials"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseMaterialsActual" href="#collapseMaterialsActual">
                                    Materials Actual | Total: $<?php echo number_format(array_shift($actual_material), 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseMaterialsActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Vendor</th>
                                            <th>Individual Quantity</th>
                                            <th>Total Quantity</th>
                                            <th>Material Cost (each)</th>
                                            <th>Total Price</th>
                                            <th>P.O. #</th>
                                            <th>Delivery Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($actual_material)) {
                                            foreach ($actual_material as $material) {
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="actual_material_description_' . $material['material_id'] . '">' . $material['description'] . '</span>
                                                            <input class="actual_material_description_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][description]"
                                                                type="hidden"
                                                                value="' . $material['description'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="actual_material_vendor_' . $material['material_id'] . '">' . $material['vendor'] . '</span>
                                                            <input class="actual_material_vendor_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][vendor]"
                                                                type="hidden"
                                                                value="' . $material['vendor'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="actual_material_individual_quantity_' . $material['material_id'] . '">'
                                                                . $material['individual_quantity'] .
                                                            '</span>
                                                            <input class="actual_material_individual_quantity_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][individual_quantity]"
                                                                type="hidden"
                                                                value="' . $material['individual_quantity'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">'
                                                            . $material['total_quantity'] .
                                                        '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            $<span class="actual_material_cost_' . $material['material_id'] . '">' . $material['cost'] . '</span>
                                                            <input class="actual_material_cost_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][cost]"
                                                                type="hidden"
                                                                value="' . $material['cost'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            $' . $material['total_cost'] .
                                                        '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="actual_material_po_' . $material['material_id'] . '">' . $material['po'] . '</span>
                                                            <input class="actual_material_po_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][po]"
                                                                type="hidden"
                                                                value="' . $material['po'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">
                                                            <span class="actual_material_delivery_date_' . $material['material_id'] . '">' . $material['delivery_date'] . '</span>
                                                            <input class="actual_material_delivery_date_' . $material['material_id'] . '"
                                                                name="actuals[material][' . $material['material_id'] . '][delivery_date]"
                                                                type="hidden"
                                                                value="' . $material['delivery_date'] . '"
                                                            /></td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Description</th>
                                            <th>Vendor</th>
                                            <th>Individual Quantity</th>
                                            <th>Total Quantity</th>
                                            <th>Material Cost (each)</th>
                                            <th>Total Price</th>
                                            <th>P.O. #</th>
                                            <th>Delivery Date</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.actual_add_material_dialog').dialog('open');">Add Material</a>
                            </div>
                        </div>
                    </div> <!-- END: materials actual -->
                </div> <!-- END: materials stats -->
                <div class="well well-sm"> <!-- Out Source -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <a id="parts"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseOutsourceQuote" href="#collapseOutsourceQuote">
                                    Out-source Quote | Quoted Total: $<?php echo number_format(array_shift($quoted_outsource), 2); ?> | Original Total: $<?php echo number_format(array_shift($quoted_outsource), 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseOutsourceQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Individual Cost</th>
                                            <th>Mark-up</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($quoted_outsource)) {
                                            foreach ($quoted_outsource as $outsource) {
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="quoted_outsource_process_' . $outsource['outsource_id'] . '">' . $outsource['process'] . '</span>
                                                            <input class="quoted_outsource_process_' . $outsource['outsource_id'] . '"
                                                                name="quotes[outsource][' . $outsource['outsource_id'] . '][process]"
                                                                type="hidden"
                                                                value="' . $outsource['process'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="quoted_outsource_company_' . $outsource['outsource_id'] . '">' . $outsource['company'] . '</span>
                                                            <input class="quoted_outsource_company_' . $outsource['outsource_id'] . '"
                                                                name="quotes[outsource][' . $outsource['outsource_id'] . '][company]"
                                                                type="hidden"
                                                                value="' . $outsource['company'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="quoted_outsource_quantity_' . $outsource['outsource_id'] . '">' . $outsource['quantity'] . '</span>
                                                            <input class="quoted_outsource_quantity_' . $outsource['outsource_id'] . '"
                                                                name="quotes[outsource][' . $outsource['outsource_id'] . '][quantity]"
                                                                type="hidden"
                                                                value="' . $outsource['quantity'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            $<span class="quoted_outsource_cost_' . $outsource['outsource_id'] . '">' . $outsource['cost'] . '</span>
                                                            <input class="quoted_outsource_cost_' . $outsource['outsource_id'] . '"
                                                                name="quotes[outsource][' . $outsource['outsource_id'] . '][cost]"
                                                                type="hidden"
                                                                value="' . $outsource['cost'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="quoted_outsource_markup_' . $outsource['outsource_id'] . '">' . $outsource['markup'] . '</span>%
                                                            <input class="quoted_outsource_markup_' . $outsource['outsource_id'] . '"
                                                                name="quotes[outsource][' . $outsource['outsource_id'] . '][markup]"
                                                                type="hidden"
                                                                value="' . $outsource['markup'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            $' . $outsource['total_cost'] .
                                                        '</td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Individual Cost</th>
                                            <th>Mark-up</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.quote_add_outsource_dialog').dialog('open');">Add Process</a>
                            </div>
                        </div>
                    </div> <!-- END: out source quote -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <a id="parts"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseOutsourceActual" href="#collapseOutsourceActual">
                                    Out-source Actual | Total: $<?php echo number_format(array_shift($actual_outsource), 2); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseOutsourceActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Individual Cost</th>
                                            <th>Total</th>
                                            <th>P.O. #</th>
                                            <th>Completion Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($actual_outsource)) {
                                            foreach ($actual_outsource as $outsource) {
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="actual_outsource_process_' . $outsource['outsource_id'] . '">' . $outsource['process'] . '</span>
                                                            <input class="actual_outsource_process_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][process]"
                                                                type="hidden"
                                                                value="' . $outsource['process'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="actual_outsource_company_' . $outsource['outsource_id'] . '">' . $outsource['company'] . '</span>
                                                            <input class="actual_outsource_company_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][company]"
                                                                type="hidden"
                                                                value="' . $outsource['company'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="actual_outsource_quantity_' . $outsource['outsource_id'] . '">' . $outsource['quantity'] . '</span>
                                                            <input class="actual_outsource_quantity_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][quantity]"
                                                                type="hidden"
                                                                value="' . $outsource['quantity'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            $<span class="actual_outsource_cost_' . $outsource['outsource_id'] . '">' . $outsource['cost'] . '</span>
                                                            <input class="actual_outsource_cost_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][cost]"
                                                                type="hidden"
                                                                value="' . $outsource['cost'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            $' . $outsource['total_cost'] . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="actual_outsource_po_' . $outsource['outsource_id'] . '">' . $outsource['po'] . '</span>
                                                            <input class="actual_outsource_po_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][po]"
                                                                type="hidden"
                                                                value="' . $outsource['po'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_outsource\', \'' . $outsource['outsource_id'] . '\', \'\')">
                                                            <span class="actual_outsource_delivery_date_' . $outsource['outsource_id'] . '">' . $outsource['delivery_date'] . '</span>
                                                            <input class="actual_outsource_delivery_date_' . $outsource['outsource_id'] . '"
                                                                name="actuals[outsource][' . $outsource['outsource_id'] . '][delivery_date]"
                                                                type="hidden"
                                                                value="' . $outsource['delivery_date'] . '"
                                                            /></td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Individual Cost</th>
                                            <th>Total</th>
                                            <th>P.O. #</th>
                                            <th>Completion Date</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.actual_add_outsource_dialog').dialog('open')">Add Process</a>
                            </div>
                        </div>
                    </div> <!-- END: out source actual -->
                </div> <!-- END: Out source -->
                <div class="well well-sm"> <!-- Sheet Metal Stats -->
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <a id="sheets"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseSheetsQuote" href="#collapseSheetsQuote">
                                    Sheets Quoted | Quoted Total: $<?php echo $this->model_quotes->get_shared_data('sheets_total_cost', 0); ?> | Actual Total: $<?php echo $this->model_quotes->get_shared_data('sheets_total_cost', 1); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseSheetsQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Material</th>
                                            <th>Vendor</th>
                                            <th>Size</th>
                                            <th>Lbs/sheet</th>
                                            <th>Sheets Required</th>
                                            <th>Cost/Sheet</th>
                                            <th>Cost/Lbs</th>
                                            <th>Total</th>
                                            <th>Mark-up</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($this->model_quotes->sheets['quoted'])) {
                                            foreach ($this->model_quotes->sheets['quoted'] as $sheet) {
                                                $required_sheets = $this->model_quotes->get_shared_data('sheets_required', $sheet['sheet_id']);
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheets\', \'' . $sheet['sheet_id'] . '\', \'\')">' . $sheet['sheet_id'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheets\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="quoted_sheet_material_' . $sheet['sheet_id'] . '">' . $sheet['material'] . '</span>
                                                            <input class="quoted_sheet_material_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][material]"
                                                                type="hidden"
                                                                value="' . $sheet['material'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="quoted_sheet_vendor_' . $sheet['sheet_id'] . '">' . $sheet['vendor'] . '</span>
                                                            <input class="quoted_sheet_vendor_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][vendor]"
                                                                type="hidden"
                                                                value="' . $sheet['vendor'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="quoted_sheet_size_' . $sheet['sheet_id'] . '">' . $sheet['size'] . '</span>
                                                            <input class="quoted_sheet_size_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $sheet['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="quoted_sheet_lbs_sheet_' . $sheet['sheet_id'] . '">' . $sheet['lbs_sheet'] . '</span>
                                                            <input class="quoted_sheet_lbs_sheet_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][lbs_sheet]"
                                                                type="hidden"
                                                                value="' . $sheet['lbs_sheet'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            ' . $required_sheets . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $' . $sheet['cost_sheet'] . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $<span class="quoted_sheet_cost_lb_' . $sheet['sheet_id'] . '">' . $sheet['cost_lb'] . '</span>
                                                            <input class="quoted_sheet_cost_lb_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][cost_lb]"
                                                                type="hidden"
                                                                value="' . $sheet['cost_lb'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $' . $this->model_quotes->get_shared_data('sheet_total_cost', $sheet['sheet_id']) . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="quoted_sheet_markup_' . $sheet['sheet_id'] . '">' . $sheet['markup'] . '</span>%
                                                            <input class="quoted_sheet_markup_' . $sheet['sheet_id'] . '"
                                                                name="quotes[sheets][' . $sheet['sheet_id'] . '][markup]"
                                                                type="hidden"
                                                                value="' . $sheet['markup'] . '"
                                                            /></td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Material</th>
                                            <th>Vendor</th>
                                            <th>Size</th>
                                            <th>Lbs/sheet</th>
                                            <th>Sheets Required</th>
                                            <th>Cost/Sheet</th>
                                            <th>Cost/Lbs</th>
                                            <th>Total</th>
                                            <th>Mark-up</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.quote_add_sheet_dialog').dialog('open')">Add Sheet</a>
                            </div>
                        </div>
                    </div> <!-- END: sheets quoted -->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <a id="sheets"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseSheetsActual" href="#collapseSheetsActual">
                                    Sheets Actual | Total: $<?php echo $this->model_quotes->get_shared_data('sheets_total_cost', 0, 'actual'); ?>
                                </a>
                            </h3>
                        </div>
                        <div id="collapseSheetsActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Material</th>
                                            <th>Vendor</th>
                                            <th>Size</th>
                                            <th>Lbs/sheet</th>
                                            <th>Sheets Required</th>
                                            <th>Cost/Sheet</th>
                                            <th>Cost/Lbs</th>
                                            <th>Sheet Cost</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($this->model_quotes->sheets['actual'])) {
                                            foreach ($this->model_quotes->sheets['actual'] as $sheet) {
                                                $required_sheets = $this->model_quotes->get_shared_data('sheets_required', $sheet['sheet_id'], 'actual');
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">' . $sheet['sheet_id'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="actual_sheet_material_' . $sheet['sheet_id'] . '">' . $sheet['material'] . '</span>
                                                            <input class="actual_sheet_material_' . $sheet['sheet_id'] . '"
                                                                name="actuals[sheets][' . $sheet['sheet_id'] . '][material]"
                                                                type="hidden"
                                                                value="' . $sheet['material'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="actual_sheet_vendor_' . $sheet['sheet_id'] . '">' . $sheet['vendor'] . '</span>
                                                            <input class="actual_sheet_vendor_' . $sheet['sheet_id'] . '"
                                                                name="actuals[sheets][' . $sheet['sheet_id'] . '][vendor]"
                                                                type="hidden"
                                                                value="' . $sheet['vendor'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="actual_sheet_size_' . $sheet['sheet_id'] . '">' . $sheet['size'] . '</span>
                                                            <input class="actual_sheet_size_' . $sheet['sheet_id'] . '"
                                                                name="actuals[sheets][' . $sheet['sheet_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $sheet['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            <span class="actual_sheet_lbs_sheet_' . $sheet['sheet_id'] . '">' . $sheet['lbs_sheet'] . '</span>
                                                            <input class="actual_sheet_lbs_sheet_' . $sheet['sheet_id'] . '"
                                                                name="actuals[sheets][' . $sheet['sheet_id'] . '][lbs_sheet]"
                                                                type="hidden"
                                                                value="' . $sheet['lbs_sheet'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            ' . $required_sheets . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $' . $sheet['cost_sheet'] . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $<span class="actual_sheet_cost_lb_' . $sheet['sheet_id'] . '">' . $sheet['cost_lb'] . '</span>
                                                            <input class="actual_sheet_cost_lb_' . $sheet['sheet_id'] . '"
                                                                name="actuals[sheets][' . $sheet['sheet_id'] . '][cost_lb]"
                                                                type="hidden"
                                                                value="' . $sheet['cost_lb'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_sheet\', \'' . $sheet['sheet_id'] . '\', \'\')">
                                                            $' . $this->model_quotes->get_shared_data('sheet_total_cost', $sheet['sheet_id']) . '
                                                            </td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Material</th>
                                            <th>Vendor</th>
                                            <th>Size</th>
                                            <th>Lbs/sheet</th>
                                            <th>Sheets Required</th>
                                            <th>Cost/Sheet</th>
                                            <th>Cost/Lbs</th>
                                            <th>Sheet Cost</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.actual_add_sheet_dialog').dialog('open')">Add Sheet</a>
                            </div>
                        </div>
                    </div> <!-- END: sheets actual -->
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <a id="blanks"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseBlanksQuote" href="#collapseBlanksQuote">
                                    Blanks Quoted
                                </a>
                            </h3>
                        </div>
                        <div id="collapseBlanksQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Sheet ID</th>
                                            <th>Size</th>
                                            <th>Blanks/sheet</th>
                                            <th>Min. blanks</th>
                                            <th>Blanks req'd</th>
                                            <th>Sheet usage</th>
                                            <th>Lbs/blank</th>
                                            <th>Cost/blank</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--<tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>48x24</td>
                                            <td>5</td>
                                            <td><!-- (sum(parts/blank) * sum(parts/job)) / job quantity 0.4</td>
                                            <td><!-- min blanks + 0.5 0.9</td>
                                            <td><!-- blanks red'd / blanks/sheet 0.18</td>
                                            <td><!-- sheet cost/lb * lbs/blank $6.51</td>
                                        </tr>-->
                                        <?php
                                        if (!empty($this->model_quotes->blanks['quoted'])) {
                                            foreach ($this->model_quotes->blanks['quoted'] as $blank) {
                                                $blanks_minimum = $this->model_quotes->get_shared_data('blanks_minimum', $blank['blank_id']);
                                                $blanks_required = $this->model_quotes->get_shared_data('blanks_minimum', $blank['blank_id']);
                                                $sheet_usage = $this->model_quotes->get_shared_data('sheet_usage', $blank['blank_id']);
                                                $total_cost = $this->model_quotes->get_shared_data('blanks_total_cost', $blank['blank_id']);
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">' . $blank['blank_id'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="quoted_blank_sheet_id_' . $blank['blank_id'] . '">' . $blank['sheet_id'] . '</span>
                                                            <input class="quoted_blank_sheet_id_' . $blank['blank_id'] . '"
                                                                name="quotes[blanks][' . $blank['blank_id'] . '][sheet_id]"
                                                                type="hidden"
                                                                value="' . $blank['sheet_id'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="quoted_blank_size_' . $blank['blank_id'] . '">' . $blank['size'] . '</span>
                                                            <input class="quoted_blank_size_' . $blank['blank_id'] . '"
                                                                name="quotes[blanks][' . $blank['blank_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $blank['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="quoted_blank_blanks_sheet_' . $blank['blank_id'] . '">' . $blank['blanks_sheet'] . '</span>
                                                            <input class="quoted_blank_blanks_sheet_' . $blank['blank_id'] . '"
                                                                name="quotes[blanks][' . $blank['blank_id'] . '][blanks_sheet]"
                                                                type="hidden"
                                                                value="' . $blank['blanks_sheet'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            ' . $blanks_minimum . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            ' . $blanks_required . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="quoted_blank_sheet_usage_' . $blank['blank_id'] . '">' . $sheet_usage . '</span>%
                                                        </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="quoted_blank_lbs_blank_' . $blank['blank_id'] . '">' . $blank['lbs_blank'] . '</span>
                                                            <input class="quoted_blank_lbs_blank_' . $blank['blank_id'] . '"
                                                                name="quotes[blanks][' . $blank['blank_id'] . '][lbs_blank]"
                                                                type="hidden"
                                                                value="' . $blank['lbs_blank'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            $' . $total_cost . '
                                                            </td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Sheet ID</th>
                                            <th>Size</th>
                                            <th>Blanks/sheet</th>
                                            <th>Min. blanks</th>
                                            <th>Blanks req'd</th>
                                            <th>Sheet usage</th>
                                            <th>Lbs/blank</th>
                                            <th>Cost/blank</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.quote_add_blank_dialog').dialog('open');">Add Blank</a>
                            </div>
                        </div>
                    </div> <!-- END: blanks quoted -->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <a id="blanks"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseBlanksActual" href="#collapseBlanksActual">
                                    Blanks Actual
                                </a>
                            </h3>
                        </div>
                        <div id="collapseBlanksActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Sheet ID</th>
                                            <th>Size</th>
                                            <th>Blanks/sheet</th>
                                            <th>Min. blanks</th>
                                            <th>Blanks req'd</th>
                                            <th>Sheet usage</th>
                                            <th>Lbs/Blank</th>
                                            <th>Cost/Blank</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--<tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>48x24</td>
                                            <td>5</td>
                                            <td><!-- min blanks + 0.5 0.9</td>
                                            <td><!-- blanks red'd / blanks/sheet 0.18</td>
                                        </tr>-->
                                        <?php
                                        if (!empty($this->model_quotes->blanks['actual'])) {
                                            foreach ($this->model_quotes->blanks['actual'] as $blank) {
                                                $blanks_minimum = $this->model_quotes->get_shared_data('blanks_minimum', $blank['blank_id'], 'actual');
                                                $blanks_required = $this->model_quotes->get_shared_data('blanks_minimum', $blank['blank_id'], 'actual');
                                                $sheet_usage = $this->model_quotes->get_shared_data('sheet_usage', $blank['blank_id'], 'actual');
                                                $total_cost = $this->model_quotes->get_shared_data('blanks_total_cost', $blank['blank_id'], 'actual');
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">' . $blank['blank_id'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="actual_blank_sheet_id_' . $blank['blank_id'] . '">' . $blank['sheet_id'] . '</span>
                                                            <input class="actual_blank_sheet_id_' . $blank['blank_id'] . '"
                                                                name="actuals[blanks][' . $blank['blank_id'] . '][sheet_id]"
                                                                type="hidden"
                                                                value="' . $blank['sheet_id'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="actual_blank_size_' . $blank['blank_id'] . '">' . $blank['size'] . '</span>
                                                            <input class="actual_blank_size_' . $blank['blank_id'] . '"
                                                                name="actuals[blanks][' . $blank['blank_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $blank['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="actual_blank_blanks_sheet_' . $blank['blank_id'] . '">' . $blank['blanks_sheet'] . '</span>
                                                            <input class="actual_blank_blanks_sheet_' . $blank['blank_id'] . '"
                                                                name="actuals[blanks][' . $blank['blank_id'] . '][blanks_sheet]"
                                                                type="hidden"
                                                                value="' . $blank['blanks_sheet'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            ' . $blanks_minimum . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            ' . $blanks_required . '
                                                            </td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="actual_blank_sheet_usage_' . $blank['blank_id'] . '">' . $sheet_usage . '</span>%
                                                        </td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            <span class="actual_blank_lbs_blank_' . $blank['blank_id'] . '">' . $blank['lbs_blank'] . '</span>
                                                            <input class="actual_blank_lbs_blank_' . $blank['blank_id'] . '"
                                                                name="actuals[blanks][' . $blank['blank_id'] . '][lbs_blank]"
                                                                type="hidden"
                                                                value="' . $blank['lbs_blank'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_blanks\', \'' . $blank['blank_id'] . '\', \'\')">
                                                            $' . $total_cost . '
                                                            </td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Sheet ID</th>
                                            <th>Size</th>
                                            <th>Blanks/sheet</th>
                                            <th>Min. blanks</th>
                                            <th>Blanks req'd</th>
                                            <th>Sheet usage</th>
                                            <th>Lbs/Blank</th>
                                            <th>Cost/Blank</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.actual_add_blank_dialog').dialog('open');">Add Blank</a>
                            </div>
                        </div>
                    </div> <!-- END: blanks actual -->
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <a id="parts"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapsePartsQuote" href="#collapsePartsQuote">
                                    Parts Quoted
                                </a>
                            </h3>
                        </div>
                        <div id="collapsePartsQuote" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Description</th>
                                            <th>Part Size</th>
                                            <th>Parts/assembly</th>
                                            <th>Parts/blank</th>
                                            <th>Parts/sheet</th>
                                            <th>Cost/part</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--<tr>
                                            <td>1</td>
                                            <td>Part A</td>
                                            <td>YYxYY</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td><!-- blanks/sheet * parts/blank 10</td>
                                            <td><!-- cost/blank / parts/blank $3.26</td>
                                        </tr>-->
                                        <?php
                                        if (!empty($this->model_quotes->parts['quoted'])) {
                                            foreach ($this->model_quotes->parts['quoted'] as $part) {
                                                $parts_sheet = $this->model_quotes->get_shared_data('parts_sheet', $part['part_id']);
                                                $total_cost = $this->model_quotes->get_shared_data('parts_total_cost', $part['part_id']);
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_blank_id_' . $part['part_id'] . '">' . $part['blank_id'] . '</span>
                                                            <input class="quoted_part_blank_id_' . $part['part_id'] . '"
                                                                name="quotes[parts][' . $part['part_id'] . '][blank_id]"
                                                                type="hidden"
                                                                value="' . $part['blank_id'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_description_' . $part['part_id'] . '">' . $part['description'] . '</span>
                                                            <input class="quoted_part_description_' . $part['part_id'] . '"
                                                                name="quotes[parts][' . $part['part_id'] . '][description]"
                                                                type="hidden"
                                                                value="' . $part['description'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_size_' . $part['part_id'] . '">' . $part['size'] . '</span>
                                                            <input class="quoted_part_size_' . $part['part_id'] . '"
                                                                name="quotes[parts][' . $part['part_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $part['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_parts_assembly_' . $part['part_id'] . '">' . $part['parts_assembly'] . '</span>
                                                            <input class="quoted_part_parts_assembly_' . $part['part_id'] . '"
                                                                name="quotes[parts][' . $part['part_id'] . '][parts_assembly]"
                                                                type="hidden"
                                                                value="' . $part['parts_assembly'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_parts_blank_' . $part['part_id'] . '">' . $part['parts_blank'] . '</span>
                                                            <input class="quoted_part_parts_blank_' . $part['part_id'] . '"
                                                                name="quotes[parts][' . $part['part_id'] . '][parts_blank]"
                                                                type="hidden"
                                                                value="' . $part['parts_blank'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="quoted_part_parts_sheet_' . $part['part_id'] . '">' . $parts_sheet . '</span>
                                                        </td>';
                                                    echo '<td onclick="updateQuote(\'quoted_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            $' . $total_cost . '
                                                            </td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Description</th>
                                            <th>Part Size</th>
                                            <th>Parts/assembly</th>
                                            <th>Parts/blank</th>
                                            <th>Parts/sheet</th>
                                            <th>Cost/part</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.quote_add_part_dialog').dialog('open')">Add Part</a>
                            </div>
                        </div>
                    </div> <!-- END: parts quoted -->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <a id="parts"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapsePartsActual" href="#collapsePartsActual">
                                    Parts Actual
                                </a>
                            </h3>
                        </div>
                        <div id="collapsePartsActual" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Description</th>
                                            <th>Part Size</th>
                                            <th>Parts/assembly</th>
                                            <th>Parts/blank</th>
                                            <th>Parts/sheet</th>
                                            <th>Cost/Part</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--<tr>
                                            <td>1</td>
                                            <td>Part A</td>
                                            <td>YYxYY</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td><!-- blanks/sheet * parts/blank 10</td>
                                        </tr>-->
                                        <?php
                                        if (!empty($this->model_quotes->parts['actual'])) {
                                            foreach ($this->model_quotes->parts['actual'] as $part) {
                                                $parts_sheet = $this->model_quotes->get_shared_data('parts_sheet', $part['part_id'], 'actual');
                                                $total_cost = $this->model_quotes->get_shared_data('parts_total_cost', $part['part_id'], 'actual');
                                                
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_blank_id_' . $part['part_id'] . '">' . $part['blank_id'] . '</span>
                                                            <input class="actual_part_blank_id_' . $part['part_id'] . '"
                                                                name="actuals[parts][' . $part['part_id'] . '][blank_id]"
                                                                type="hidden"
                                                                value="' . $part['blank_id'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_description_' . $part['part_id'] . '">' . $part['description'] . '</span>
                                                            <input class="actual_part_description_' . $part['part_id'] . '"
                                                                name="actuals[parts][' . $part['part_id'] . '][description]"
                                                                type="hidden"
                                                                value="' . $part['description'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_size_' . $part['part_id'] . '">' . $part['size'] . '</span>
                                                            <input class="actual_part_size_' . $part['part_id'] . '"
                                                                name="actuals[parts][' . $part['part_id'] . '][size]"
                                                                type="hidden"
                                                                value="' . $part['size'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_parts_assembly_' . $part['part_id'] . '">' . $part['parts_assembly'] . '</span>
                                                            <input class="actual_part_parts_assembly_' . $part['part_id'] . '"
                                                                name="actuals[parts][' . $part['part_id'] . '][parts_assembly]"
                                                                type="hidden"
                                                                value="' . $part['parts_assembly'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_parts_blank_' . $part['part_id'] . '">' . $part['parts_blank'] . '</span>
                                                            <input class="actual_part_parts_blank_' . $part['part_id'] . '"
                                                                name="actuals[parts][' . $part['part_id'] . '][parts_blank]"
                                                                type="hidden"
                                                                value="' . $part['parts_blank'] . '"
                                                            /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            <span class="actual_part_parts_sheet_' . $part['part_id'] . '">' . $parts_sheet . '</span>
                                                        </td>';
                                                    echo '<td onclick="updateQuote(\'actual_parts\', \'' . $part['part_id'] . '\', \'\')">
                                                            $' . $total_cost . '
                                                            </td>';
                                                    echo '<td onclick="removeRow(this);"><span class="ui-icon ui-icon-trash"></span></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Description</th>
                                            <th>Part Size</th>
                                            <th>Parts/assembly</th>
                                            <th>Parts/blank</th>
                                            <th>Parts/sheet</th>
                                            <th>Cost/Part</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a onclick="jQuery('.actual_add_part_dialog').dialog('open')">Add Part</a>
                            </div>
                        </div>
                    </div> <!-- END: parts actual -->
                </div> <!-- END: Sheet metal stats -->
            </form>
        </div>
    </div>
</div>
<div class="quote_hourly_value_update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Hourly Value</div>
        <form name="quote_hourly_value_update" method="post" action="">
            <input class="dialog_input" type="text" name="amount" value="0" />
        </form>
    </div>
</div>
<div class="quote_initial_time_update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Initial Time</div>
        <form name="quote_initial_time_update" method="post" action="">
            <input class="dialog_input" type="text" name="time" value="0" />
        </form>
    </div>
</div>
<div class="quote_repeat_time_update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Repeat Time</div>
        <form name="quote_repeat_time_update" method="post" action="">
            <input class="dialog_input" type="text" name="time" value="0" />
        </form>
    </div>
</div>
<div class="quote_add_material_dialog">
    <script type="text/javascript">
        quoted_material_id = <?php echo $this->sys->template->quote['max_ids']['quoted_material'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Material</div>
        <form name="quote_add_material" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="individual_quantity" value="" placeholder="Individual Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_add_material_dialog">
    <script type="text/javascript">
        quoted_material_id = <?php echo $this->sys->template->quote['max_ids']['actual_material'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Material</div>
        <form name="quote_add_material" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="individual_quantity" value="" placeholder="Individual Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="po" value="" placeholder="P.O. Number" />
                    <input class="dialog_input inline_date" type="text" name="delivery_date" value="" placeholder="Delivery Date" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_update_material_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Material</div>
        <form name="actual_update_material" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="individual_quantity" value="" placeholder="Individual Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="po" value="" placeholder="P.O. Number" />
                    <input class="dialog_input inline_date" type="text" name="delivery_date" value="" placeholder="Delivery Date" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_update_material_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Material</div>
        <form name="quote_update_material" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="individual_quantity" value="" placeholder="Individual Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_add_outsource_dialog">
    <script type="text/javascript">
        quoted_outsource_id = <?php echo $this->sys->template->quote['max_ids']['quoted_outsource'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Outsource</div>
        <form name="quote_add_outsource" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="process" value="" placeholder="Process" />
                    <input class="dialog_input" type="text" name="company" value="" placeholder="Company" />
                    <input class="dialog_input" type="text" name="quantity" value="" placeholder="Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_update_outsource_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Outsource</div>
        <form name="quote_update_outsource" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="process" value="" placeholder="Process" />
                    <input class="dialog_input" type="text" name="company" value="" placeholder="Company" />
                    <input class="dialog_input" type="text" name="quantity" value="" placeholder="Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_add_outsource_dialog">
    <script type="text/javascript">
        actual_outsource_id = <?php echo $this->sys->template->quote['max_ids']['actual_outsource'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Outsource</div>
        <form name="actual_add_outsource" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="process" value="" placeholder="Process" />
                    <input class="dialog_input" type="text" name="company" value="" placeholder="Company" />
                    <input class="dialog_input" type="text" name="quantity" value="" placeholder="Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="po" value="" placeholder="P.O. Number" />
                    <input class="dialog_input inline_date" type="text" name="delivery_date" value="" placeholder="Delivery Date" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_update_outsource_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Outsource</div>
        <form name="actual_update_outsource" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="process" value="" placeholder="Process" />
                    <input class="dialog_input" type="text" name="company" value="" placeholder="Company" />
                    <input class="dialog_input" type="text" name="quantity" value="" placeholder="Quantity" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="cost" value="" placeholder="Individual Cost" />
                    <input class="dialog_input" type="text" name="po" value="" placeholder="P.O. Number" />
                    <input class="dialog_input inline_date" type="text" name="delivery_date" value="" placeholder="Delivery Date" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_add_sheet_dialog">
    <script type="text/javascript">
        quoted_sheet_id = <?php echo $this->sys->template->quote['max_ids']['quoted_sheets'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Sheet</div>
        <form name="quote_add_sheet" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="material" value="" placeholder="Material" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="lbs_sheet" value="" placeholder="Lbs/Sheet" />
                    <input class="dialog_input" type="text" name="cost_lb" value="" placeholder="Cost/Lbs" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_update_sheet_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Sheet</div>
        <form name="quote_update_sheet" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="material" value="" placeholder="Material" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="lbs_sheet" value="" placeholder="Lbs/Sheet" />
                    <input class="dialog_input" type="text" name="cost_lb" value="" placeholder="Cost/Lbs" />
                    <input class="dialog_input" type="text" name="markup" value="" placeholder="Mark-up Percentage" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_add_sheet_dialog">
    <script type="text/javascript">
        actual_sheet_id = <?php echo $this->sys->template->quote['max_ids']['actual_sheets'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Sheet</div>
        <form name="actual_add_sheet" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="material" value="" placeholder="Material" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="lbs_sheet" value="" placeholder="Lbs/Sheet" />
                    <input class="dialog_input" type="text" name="cost_lb" value="" placeholder="Cost/Lbs" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_update_sheet_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Sheet</div>
        <form name="actual_update_sheet" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="material" value="" placeholder="Material" />
                    <input class="dialog_input" type="text" name="vendor" value="" placeholder="Vendor" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="lbs_sheet" value="" placeholder="Lbs/Sheet" />
                    <input class="dialog_input" type="text" name="cost_lb" value="" placeholder="Cost/Lbs" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_add_blank_dialog">
    <script type="text/javascript">
        quoted_blank_id = <?php echo $this->sys->template->quote['max_ids']['quoted_blanks'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Blank</div>
        <form name="quote_add_blank" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="sheet_id" value="" placeholder="Sheet ID" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="blanks_sheet" value="" placeholder="Blanks/Sheet" />
                    <input class="dialog_input" type="text" name="lbs_blank" value="" placeholder="Lbs/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_update_blank_dialog update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Blank</div>
        <form name="quote_update_blank" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <label class="dialog_label">Sheet ID</label>
                        <input class="dialog_input" type="text" name="sheet_id" value="" placeholder="Sheet ID" />
                    <label class="dialog_label">Size</label>
                        <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <label class="dialog_label">Blanks/Sheet</label>
                        <input class="dialog_input" type="text" name="blanks_sheet" value="" placeholder="Blanks/Sheet" />
                    <label class="dialog_label">Lbs/Blank</label>
                        <input class="dialog_input" type="text" name="lbs_blank" value="" placeholder="Lbs/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_add_blank_dialog">
    <script type="text/javascript">
        actual_blank_id = <?php echo $this->sys->template->quote['max_ids']['actual_blanks'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Blank</div>
        <form name="actual_add_blank" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="sheet_id" value="" placeholder="Sheet ID" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="blanks_sheet" value="" placeholder="Blanks/Sheet" />
                    <input class="dialog_input" type="text" name="lbs_blank" value="" placeholder="Lbs/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_update_blank_dialog update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Blank</div>
        <form name="actual_update_blank" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <label class="dialog_label">Sheet ID</label>
                        <input class="dialog_input" type="text" name="sheet_id" value="" placeholder="Sheet ID" />
                    <label class="dialog_label">Size</label>
                        <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <label class="dialog_label">Blanks/Sheet</label>
                        <input class="dialog_input" type="text" name="blanks_sheet" value="" placeholder="Blanks/Sheet" />
                    <label class="dialog_label">Lbs/Blank</label>
                        <input class="dialog_input" type="text" name="lbs_blank" value="" placeholder="Lbs/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_add_part_dialog">
    <script type="text/javascript">
        quoted_part_id = <?php echo $this->sys->template->quote['max_ids']['quoted_parts'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Part</div>
        <form name="quote_add_part" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="blank_id" value="" placeholder="Blank ID" />
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="parts_assembly" value="" placeholder="Parts/Assembly" />
                    <input class="dialog_input" type="text" name="parts_blank" value="" placeholder="Parts/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="quote_update_part_dialog update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Blank</div>
        <form name="quote_update_part" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <label class="dialog_label">Blank ID</label>
                        <input class="dialog_input" type="text" name="blank_id" value="" placeholder="Blank ID" />
                    <label class="dialog_label">Description</label>
                        <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <label class="dialog_label">Size</label>
                        <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <label class="dialog_label">Parts/Assembly</label>
                        <input class="dialog_input" type="text" name="parts_assembly" value="" placeholder="Parts/Assembly" />
                    <label class="dialog_label">Parts/Blank</label>
                        <input class="dialog_input" type="text" name="parts_blank" value="" placeholder="Parts/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_add_part_dialog">
    <script type="text/javascript">
        actual_part_id = <?php echo $this->sys->template->quote['max_ids']['actual_parts'] + 1; ?>;
    </script>
    <div class="dialog_text">
        <div class="bold dialog_title">Add Part</div>
        <form name="actual_add_part" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="blank_id" value="" placeholder="Blank ID" />
                    <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <input class="dialog_input" type="text" name="parts_assembly" value="" placeholder="Parts/Assembly" />
                    <input class="dialog_input" type="text" name="parts_blank" value="" placeholder="Parts/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="actual_update_part_dialog update_dialog">
    <div class="dialog_text">
        <div class="bold dialog_title">Update Blank</div>
        <form name="actual_update_part" method="post" action="">
            <div class="row">
                <div class="col-sm-6">
                    <label class="dialog_label">Blank ID</label>
                        <input class="dialog_input" type="text" name="blank_id" value="" placeholder="Blank ID" />
                    <label class="dialog_label">Description</label>
                        <input class="dialog_input" type="text" name="description" value="" placeholder="Description" />
                    <label class="dialog_label">Size</label>
                        <input class="dialog_input" type="text" name="size" value="" placeholder="Size" />
                </div>
                <div class="col-sm-6">
                    <label class="dialog_label">Parts/Assembly</label>
                        <input class="dialog_input" type="text" name="parts_assembly" value="" placeholder="Parts/Assembly" />
                    <label class="dialog_label">Parts/Blank</label>
                        <input class="dialog_input" type="text" name="parts_blank" value="" placeholder="Parts/Blank" />
                </div>
            </div>
        </form>
    </div>
</div>