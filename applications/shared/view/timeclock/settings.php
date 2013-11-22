<div class="jumbotron">
    <h1>TimeClock</h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="well">
                <p>This system is designed as a management panel for external timeclock hardware.</p>
                <a href="about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Employee Mangement</h3>
                </div>
                <div class="panel-body center">
                    <a href="employee/add">Add New Employee</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-lg-offset-2">
        <div class="well">
            <form class="form-horizontal" name="settings_form" method="post" action="">
                <div class="group">
                    <label class="control-label">Pay Period Start</label>
                    <select class="form-control" name="pay_period_start">
                        <option value="Sunday">Sunday</option>
                        <option value="Monday" selected="selected">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                    </select>
                </div>
                <div class="group">
                    <label>Pay Period End</label>
                    <select class="form-control">
                        <option value="Sunday">Sunday</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                    </select>
                </div>
                <div class="group">
                    <label>Round Time By</label>
                    <select class="form-control">
                        <option value="none">None</option>
                        <option value="1">1 Minute</option>
                        <option value="15">15 Minutes</option>
                        <option value="30">30 Minutes</option>
                    </select>
                </div>
                <div class="group">
                    <label>Sort Employees By</label>
                    <select class="form-control">
                        <option value="last_first">Last Name, First Name</option>
                        <option value="first_last">First Name, Last Name</option>
                        <option value="uid">UID</option>
                    </select>
                </div>
                <div class="group">
                    <label>List Employees As</label>
                    <select class="form-control">
                        <option value="last_first">Last Name, First Name</option>
                        <option value="first_last">First Name, Last Name</option>
                    </select>
                </div>
                {update_status}
                <button type="submit" name="update_settings" class="btn btn-primary">Submit</button>
            </form>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                </div>
            </div>
        </div>
    </div>
</div>