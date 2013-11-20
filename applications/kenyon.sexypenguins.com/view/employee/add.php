<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>Add a new employee</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
        <form class="form" method="post" action="">
                <input class="form-control" name="firstname" type="text" placeholder="Employees' First Name" required="required" />
                <input class="form-control" name="lastname" type="text" placeholder="Employees' Last Name" required="required" />
                <hr />
                <input class="form-control" name="uid" type="text" placeholder="Employees' Badge UID" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" checked="checked" />
                    Generate 4-byte hex UID
                </label>
                <hr />
                <input class="form-control" name="username" type="text" placeholder="Optional Username" />
                <input class="form-control" name="password" type="password" placeholder="Password (Required With Username)" />
                {response}
                <input class="form-control" name="add_employee" type="submit" value="Add Employee" />
            </form>
        </div>
    </div>
</div>
