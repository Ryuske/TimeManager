<div class="jumbotron">
    <h1>Time Manager</h1>
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <p>This system is designed as a management panel for external hardware.</p>
                <a href="{timemanager_root}about" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a>
            </div>
        </div>
    </div>
</div>
<div class="well about">
    <h2>About</h2>
        <p>Time Manager was developed as an open-source time/project management &amp; quoting system that can interface with nearly anything.</p>
        
        <p>The original idea was that it would be used with an RFID scanner for punching in & out, however, through
        the development it was abstracted using a RESTFUL API and can be used by anything that can make a web
        call.</p>
        
        <p>Time Manager was developed by <a href="https://plus.google.com/+KenyonHaliwell" target="_blank">Kenyon Haliwell</a> and can be downloaded at <a href="https://github.com/Ryuske/TimeManager" target="_blank">GitHub</a>.</p>

    <h2>How to Use</h2>
        <div class="indent">
            <h3>Employees</h3>
                <h4>Usernames</h4>
                    <p>When you add an employee, if you <span class="bold">specify a username you must enter a password.</span> Once you've given them a username
                    and password they will be able to login to the backend and perform any of the operations. If you don't want them to
                    have access to the management panel, don't enter a username.</p>
                <h4>UIDs</h4>
                    <p>Also note, that even if the generate UID checkbox is checked, if there is something written in that box, it will take
                    precedence. So if you want it to generate a UID, make sure the text box is empty.</p>
                <h4>Roles</h4>
                    <p>
                        When adding an employee you can set a role.
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Admin</td>
                                            <td>Can add/edit/remove employees and jobs. Can also update settings.</td>
                                        </tr>
                                        <tr>
                                            <td>Management</td>
                                            <td>Not used here, can be used with API.</td>
                                        </tr>
                                        <tr>
                                            <td>None</td>
                                            <td>Can see all employees and times, however cannot change anything.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </p>
            
            <h3>Jobs</h3>
                <h4>Quoting</h4>
                    <p>When you create or edit a job you can enter information about quotes. This is broken down by department
                    and will show the different of the actual worked time and the quoted time. You can view this information when
                    you click on a job and are viewing the punches.</p>
                    <p>
                        Worked loads are based on <span class="italic">x number</span> of employees in a department * 8 hours a day.
                    </p>
                
                <h4>Tracking</h4>
                    <p>When you are viewing the tracking table for jobs, they are sorted by soonist due date first. The table also
                    shows the last department that worked on that job.</p>
                
            <h3>Times</h3>
                <h4>Pay Periods</h4>
                    <p>You can view an employees' times by <span class="bold">clicking on their name</span> on the home page. The <span class="italic">first pay period you will see
                    is the current pay period</span>. Below the current pay period table is a list of all the pay periods that the system is aware of. Any pay period that is
                    <span class="bold">bold</span> is one that the employee has time under. The <span class="italic">italic</span> pay period is the one you are currently looking at.</p>
                    
                    <p>A <span class="bold">pay period</span> is defined as Monday-Sunday.</p>
                    
                    <p>The <span class="bold">total hours</span> for the pay period are displayed up by the employees name in the blue bar.</p>
                    
                    <p>You can <span class="bold">remove a date</span> from a pay period if you "zero" out all the times for that date (or row, more specifically). Just enter blank
                    times for all of them and the date will be removed.</p>
                    
                <h4>Editing Times</h4>
                    <p>You can edit any time by <span class="bold">clicking on the time you wish to edit</span>. You'll be presented
                    with a box that you can enter the time into. All <span class="italic">total hours will auto populate</span>.
                    You can also edit the next in/out fields that are blank.</p>
                    
                <h4>Adding Dates</h4>
                    <p>If you click the <span class="bold">Add Date</span> button, you can add a date worked to the table. You can also add
                    duplicate dates. When you click <span class="bold">Add Date</span> you'll be given a calendar
                    where the only dates you can click are the ones within the pay period that you are looking at.</p>
                    
                    <p>Also please note that for employees all previous in/out slots for that dates must be filled before adding a new
                    date.</p>
                
            <h3>Settings</h3>
                <p>There are four different options that you can current edit. Round Time By, Items Per Page,
                Sort Employees By and List Employees As. Here is a break down of the settings:</p>
                <p>
                    <table class="table">
                        <tr>
                            <td><span class="bold">Round Time By:</span></td>
                            <td>Used to determin how accurately you want to round hours.</td>
                        </tr>
                        <tr>
                            <td><span class="bold">Items Per Page:</span></td>
                            <td>
                                Determins how many employees or rows of pay periods you wish to view at once.
                                0 disables paging all together.
                            </td>
                        </tr>
                        <tr>
                            <td><span class="bold">Sort Employees By:</span></td>
                            <td>Defines how to sort employees on the home page</td>
                        </tr>
                        <tr>
                            <td><span class="bold">List Employees By:</span></td>
                            <td>Defines how to display the employees name on the home page.</td>
                        </tr>
                    </table>
                </p>
        </div>
</div>