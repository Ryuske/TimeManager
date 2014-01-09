<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="well">
        <p>
            {job['client_name']} {job['job_name']} | Quantity: {job['job_quantity']} | Job #: {job['job_uid']}
        </p>
        <p>
            Quoted Total: $133.33
            &bull;
            Actual Total: <span class="green">$76.94</span>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel-group" id="accordion">
            <div class="well well-sm"> <!-- Time Stats -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <a id="time"></a>
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-target="#collapseTimeQuote" href="#collapseTimeQuote">
                                Time Quote | Total Time: 0.5 hours | Total Cost: $30
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
                                        <th>Initial Individual Cost</th>
                                        <th>Repeat Time</th>
                                        <th>Repeat Individual Cost</th>
                                        <th>Total Time</th>
                                        <th>Individual Cost</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Engineering</td>
                                        <td>$60</td>
                                        <td>.5</td>
                                        <td><!--(Initial Time / Quantity) * Cost Per Hour-->$30</td>
                                        <td>0</td>
                                        <td><!--(Repeat Time / Quantity) * Cost Per Hour-->$0</td>
                                        <td><!--Initial time + repeat time-->.5</td>
                                        <td><!--(Initial Individual Cost + Repeat Individual Cost == Total Cost) ? N/A : .-->N/A</td>
                                        <td><!--Total time * cost per hour-->$30</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Department</th>
                                        <th>Cost Per Hour</th>
                                        <th>Initial Time</th>
                                        <th>Initial Individual Cost</th>
                                        <th>Repeat Time</th>
                                        <th>Repeat Individual Cost</th>
                                        <th>Total Time</th>
                                        <th>Individual Cost</th>
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
                                Materials Quoted | Quoted Total: $1.68 | Original Total: $1.40
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Screws</td>
                                        <td>Company</td>
                                        <td>8</td>
                                        <td><!--Individual quantity * job quantity-->40</td>
                                        <td>$0.01/ea</td>
                                        <td>20%</td>
                                        <td><!--(Material Cost * Total Quantity) * (1 + percentage_in_decimal)-->$0.48</td>
                                    </tr>
                                    <tr>
                                        <td>Hinges</td>
                                        <td>Company</td>
                                        <td>2</td>
                                        <td><!--Individual quantity * job quantity-->10</td>
                                        <td>$0.10/ea</td>
                                        <td>20%</td>
                                        <td><!--(Material Cost * Total Quantity) * (1 + percentage_in_decimal)-->$1.20</td>
                                    </tr>
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
                                    </tr>
                                </tfoot>
                            </table>
                            <a href="#">Add Material</a>
                        </div>
                    </div>
                </div> <!-- END: materials quoted -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <a id="materials"></a>
                        <h3 class="panel-title">
                            <a data-toggle="collapse" data-target="#collapseMaterialsActual" href="#collapseMaterialsActual">
                                Materials Actual | Total: $1.40
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Screws</td>
                                        <td>Company</td>
                                        <td>8</td>
                                        <td><!--Individual quantity * job quantity-->40</td>
                                        <td>$0.01/ea</td>
                                        <td><!--(Material Cost * Total Quantity) * (1 + percentage_in_decimal)-->$0.40</td>
                                        <td>xxxxxxxx</td>
                                        <td>1/10/14</td>
                                    </tr>
                                    <tr>
                                        <td>Hinges</td>
                                        <td>Company</td>
                                        <td>2</td>
                                        <td><!--Individual quantity * job quantity-->10</td>
                                        <td>$0.10/ea</td>
                                        <td><!--(Material Cost * Total Quantity) * (1 + percentage_in_decimal)-->$1.00</td>
                                        <td>xxxxxxxx</td>
                                        <td>1/10/14</td>
                                    </tr>
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
                                    </tr>
                                </tfoot>
                            </table>
                            <a href="#">Add Material</a>
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
        </div>
    </div>
</div>