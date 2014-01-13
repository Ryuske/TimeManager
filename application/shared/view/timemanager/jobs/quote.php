<?php
$quoted_time = $this->model_quotes->get_time_quote($this->sys->template->departments, $this->sys->template->quote['quote']['quoted_time']);
$quoted_material = $this->model_quotes->get_material($this->sys->template->quote['quote']['quoted_material'], 'quoted');
$actual_material = $this->model_quotes->get_material($this->sys->template->quote['quote']['actual_material'], 'actual');
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
            Quoted Total: $133.33
            &bull;
            Actual Total: $76.94
            &bull; 
            Profit: <span class="green">$56.39</span>
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
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'hourly_value\')">$<span class="time_hourly_value_' . $time['department_id'] . '">' . $time['hourly_value'] . '</span> <input class="time_hourly_value_' . $time['department_id'] . '" name="quotes[time][' . $time['department_id'] . '][hourly_value]" type="hidden" value="' . $time['hourly_value'] . '" /></td>';
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'initial_time\')"><span class="time_initial_time_' . $time['department_id'] . '">' . $time['initial_time'] . '</span> hours<input class="time_initial_time_' . $time['department_id'] . '" name="quotes[time][' . $time['department_id'] . '][initial_time]" type="hidden" value="' . $time['initial_time'] . '" /></td>';
                                                echo '<td>$' . $time['initial_cost'] . '</td>';
                                                echo '<td onclick="updateQuote(\'time\', \'' . $time['department_id'] . '\', \'repeat_time\')"><span class="time_repeat_time_' . $time['department_id'] . '">' . $time['repeat_time'] . '</span> hours<input class="time_repeat_time_' . $time['department_id'] . '" name="quotes[time][' . $time['department_id'] . '][repeat_time]" type="hidden" value="' . $time['repeat_time'] . '" /></td>';
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
                                    Time Actual | Total Time: 0.25 hours | Total Cost: $7.50
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
                                        <tr>
                                            <td>Engineering</td>
                                            <td>$30</td>
                                            <td>.5</td>
                                            <td>.25</td>
                                            <td><span class="green">.25</span></td>
                                            <td><span class="green">$22.5</span></td>
                                        </tr>
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
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')"><span class="quoted_material_description_' . $material['material_id'] . '">' . $material['description'] . '</span> <input class="quoted_material_description_' . $material['material_id'] . '" name="quotes[material][' . $material['material_id'] . '][description]" type="hidden" value="' . $material['description'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')"><span class="quoted_material_vendor_' . $material['material_id'] . '">' . $material['vendor'] . '</span> <input class="quoted_material_vendor_' . $material['material_id'] . '" name="quotes[material][' . $material['material_id'] . '][vendor]" type="hidden" value="' . $material['vendor'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')"><span class="quoted_material_individual_quantity_' . $material['material_id'] . '">' . $material['individual_quantity'] . '</span> <input class="quoted_material_individual_quantity_' . $material['material_id'] . '" name="quotes[material][' . $material['material_id'] . '][individual_quantity]" type="hidden" value="' . $material['individual_quantity'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">' . $material['total_quantity'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">$<span class="quoted_material_cost_' . $material['material_id'] . '">' . $material['cost'] . '</span> <input class="quoted_material_cost_' . $material['material_id'] . '" name="quotes[material][' . $material['material_id'] . '][cost]" type="hidden" value="' . $material['cost'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')"><span class="quoted_material_markup_' . $material['material_id'] . '">' . $material['markup'] . '</span>% <input class="quoted_material_markup_' . $material['material_id'] . '" name="quotes[material][' . $material['material_id'] . '][markup]" type="hidden" value="' . $material['markup'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'quoted_material\', \'' . $material['material_id'] . '\', \'\')">$' . $material['total_cost'] . '</td>';
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
                                        <!--<tr>
                                            <td>Screws</td>
                                            <td>Company</td>
                                            <td>8</td>
                                            <td><!--Individual quantity * job quantity40</td>
                                            <td>$0.01/ea</td>
                                            <td><!--(Material Cost * Total Quantity) * (1 + percentage_in_decimal)$0.40</td>
                                            <td>xxxxxxxx</td>
                                            <td>1/10/14</td>
                                        </tr>-->
                                        <?php
                                        if (!empty($actual_material)) {
                                            foreach ($actual_material as $material) {
                                                echo '<tr>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')"><span class="actual_material_description_' . $material['material_id'] . '">' . $material['description'] . '</span> <input class="actual_material_description_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][description]" type="hidden" value="' . $material['description'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')"><span class="actual_material_vendor_' . $material['material_id'] . '">' . $material['vendor'] . '</span> <input class="actual_material_vendor_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][vendor]" type="hidden" value="' . $material['vendor'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')"><span class="actual_material_individual_quantity_' . $material['material_id'] . '">' . $material['individual_quantity'] . '</span> <input class="actual_material_individual_quantity_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][individual_quantity]" type="hidden" value="' . $material['individual_quantity'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">' . $material['total_quantity'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">$<span class="actual_material_cost_' . $material['material_id'] . '">' . $material['cost'] . '</span> <input class="actual_material_cost_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][cost]" type="hidden" value="' . $material['cost'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')">$' . $material['total_cost'] . '</td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')"><span class="actual_material_po_' . $material['material_id'] . '">' . $material['po'] . '</span> <input class="actual_material_po_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][po]" type="hidden" value="' . $material['po'] . '" /></td>';
                                                    echo '<td onclick="updateQuote(\'actual_material\', \'' . $material['material_id'] . '\', \'\')"><span class="actual_material_delivery_date_' . $material['material_id'] . '">' . $material['delivery_date'] . '</span> <input class="actual_material_delivery_date_' . $material['material_id'] . '" name="actuals[material][' . $material['material_id'] . '][delivery_date]" type="hidden" value="' . $material['delivery_date'] . '" /></td>';
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
                                    Out-source Quote | Quoted Total: $44.40 | Original Total: $37
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Plate</td>
                                            <td>Plating Company</td>
                                            <td>10</td>
                                            <td>$3</td>
                                            <td>20%</td>
                                            <td><!-- quantity * individual cost -->$30</td>
                                        </tr>
                                        <tr>
                                            <td>Delivery</td>
                                            <td>UPS</td>
                                            <td>10</td>
                                            <td>$0.70</td>
                                            <td>20%</td>
                                            <td><!-- quantity * individual cost -->$7</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Cost of each</th>
                                            <th>Mark-up</th>
                                            <th>Total</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Process</a>
                            </div>
                        </div>
                    </div> <!-- END: out source quote -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <a id="parts"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseOutsourceActual" href="#collapseOutsourceActual">
                                    Out-source Actual | Total: $37
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Plate</td>
                                            <td>Plating Company</td>
                                            <td>10</td>
                                            <td>$3</td>
                                            <td><!-- quantity * individual cost -->$30</td>
                                            <td>xxxxxxxx</td>
                                            <td>1/10/14</td>
                                        </tr>
                                        <tr>
                                            <td>Delivery</td>
                                            <td>UPS</td>
                                            <td>10</td>
                                            <td>$0.70</td>
                                            <td><!-- quantity * individual cost -->$7</td>
                                            <td>xxxxxxxx</td>
                                            <td>1/10/14</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Process</th>
                                            <th>Company</th>
                                            <th>Quantity</th>
                                            <th>Cost of each</th>
                                            <th>Total</th>
                                            <th>P.O. #</th>
                                            <th>Completion Date</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Process</a>
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
                                    Sheets Quoted | Quoted Total: $37.25 | Actual Total: $31.04
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
                                            <th>Sheet Cost</th>
                                            <th>Mark-up</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>18 GA G-30</td>
                                            <td>Company</td>
                                            <td>48x120</td>
                                            <td>80</td>
                                            <td><!-- Sum of sheet usage -->0.68</td>
                                            <td>$45.65</td>
                                            <td><!-- (lbs/sheet / cost/sheet) -->$0.57</td>
                                            <td>$45.65</td>
                                            <td>20%</td>
                                        </tr>
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
                                            <th>Mark-up</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Sheet</a>
                            </div>
                        </div>
                    </div> <!-- END: sheets quoted -->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <a id="sheets"></a>
                            <h3 class="panel-title">
                                <a data-toggle="collapse" data-target="#collapseSheetsActual" href="#collapseSheetsActual">
                                    Sheets Actual | Total: $31.04
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>18 GA G-30</td>
                                            <td>Company</td>
                                            <td>48x120</td>
                                            <td>80</td>
                                            <td><!-- Sum of sheet usage -->0.68</td>
                                            <td>$45.65</td>
                                            <td><!-- (lbs/sheet / cost/sheet) -->$0.57</td>
                                            <td>$45.65</td>
                                        </tr>
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
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Sheet</a>
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
                                            <th>Cost/blank</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>48x24</td>
                                            <td>5</td>
                                            <td><!-- (sum(parts/blank) * sum(parts/job)) / job quantity -->0.4</td>
                                            <td><!-- min blanks + 0.5-->0.9</td>
                                            <td><!-- blanks red'd / blanks/sheet -->0.18</td>
                                            <td><!-- sheet cost/lb * lbs/blank-->$6.51</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>48x40</td>
                                            <td>3</td>
                                            <td><!-- (sum(parts/blank) * sum(parts/job)) / job quantity -->1</td>
                                            <td><!-- min blanks + 0.5-->1.5</td>
                                            <td><!-- blanks red'd / blanks/sheet -->0.5</td>
                                            <td><!-- sheet cost/lb * lbs/blank-->$11.12</td>
                                        </tr>
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
                                            <th>Cost/blank</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Blank</a>
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
                                            <th># of blanks</th>
                                            <th>Sheet usage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>48x24</td>
                                            <td>5</td>
                                            <td><!-- min blanks + 0.5-->0.9</td>
                                            <td><!-- blanks red'd / blanks/sheet -->0.18</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>1</td>
                                            <td>48x40</td>
                                            <td>3</td>
                                            <td><!-- min blanks + 0.5-->1.5</td>
                                            <td><!-- blanks red'd / blanks/sheet -->0.5</td>
                                        </tr>
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
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Blank</a>
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
                                        <tr>
                                            <td>1</td>
                                            <td>Part A</td>
                                            <td>YYxYY</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td><!-- blanks/sheet * parts/blank -->10</td>
                                            <td><!-- cost/blank / parts/blank -->$3.26</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Part B</td>
                                            <td>YYxYY</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td><!-- blanks/sheet * parts/blank -->6</td>
                                            <td><!-- cost/blank / parts/blank -->$3.71</td>
                                        </tr>
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
                                <a href="#">Add Part</a>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Part A</td>
                                            <td>YYxYY</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td><!-- blanks/sheet * parts/blank -->10</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Part B</td>
                                            <td>YYxYY</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td><!-- blanks/sheet * parts/blank -->6</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Blank ID</th>
                                            <th>Description</th>
                                            <th>Part Size</th>
                                            <th>Parts/assembly</th>
                                            <th>Parts/blank</th>
                                            <th>Parts/sheet</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <a href="#">Add Part</a>
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