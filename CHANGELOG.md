### 1/2/14 - Commit 1 ###
* Updated configuration, apparently I forgot about it

### 12/31/13 - Commit 7 ###
* Fixed issue with updating password (wasn't md5 hashing)
* Fixed issue where trying to update settings deleted all settings
* Fixed divsion by zero error in pagination if paginate by was set to 0

### 12/31/13 - Commit 6 ###
* https://github.com/Ryuske/TimeManager/issues/30 <-- Added job tracking information

### 12/31/13 - Commit 5 ###
* https://github.com/Ryuske/TimeManager/issues/39 <-- Updated jobs delete view table
* https://github.com/Ryuske/TimeManager/issues/38 <-- Fixed Display As when deleting an employee

### 12/31/13 - Commit 4 ###
* Forgot to remove version from interface
* https://github.com/Ryuske/TimeClock/issues/31 <-- Renamed project
* Pushed to Version 2.5

### 12/31/13 - Commit 3 ###
* https://github.com/Ryuske/TimeClock/issues/36 <-- Fixed foreign keys

### 12/31/13 - Commit 2 ###
* https://github.com/Ryuske/TimeClock/issues/28 <-- Enforced types
* Got rid of file versions, and now it's only in README.md

### 12/31/13 - Commit 1 ###
* https://github.com/Ryuske/TimeClock/issues/35 <- Fixed pagination
* https://github.com/Ryuske/TimeClock/issues/29 <-- Renamed Jobs to Jobs - Quotes

### 12/27/13 - Commit 2 ###
* Updated format on PayPeriod API to match wiki

### 12/27/13 - Commit 1 ###
* Fixed issue with payperiod API not showing `Error` when an incorrect ID was supplied

### 12/23/13 - Commit 2 ###
* Moved time to /timeclock/current_time instead of being under the employee API

### 12/23/13 - Commit 1 ###
* https://github.com/Ryuske/TimeClock/issues/33 <-- Fixed API errors
* Added `uid` to employees API
* Added getting `current_time` to employees API

### 12/19/13 - Commit 7 ###
* Fixed some typos

### 12/19/13 - Commit 5-6 ###
* Fixed printable version of jobs. Forgot to add the new hours breakdown.
* Categories were being ordered wrong on the printable version

### 12/19/13 - Commit 4 ###
* Fixing styling issue a little bit until I can impliment https://github.com/Ryuske/TimeClock/issues/28

### 12/19/13 - Commit 2-3 ###
* Not sure if it's the right decision but now if there are no employees in a quoted category the load defaults to 100% instead of 0%
* Whoops - did it backwards

### 12/19/13 - Commit 1 ###
* Fixed a couple of bugs with adding/editing employees and jobs
* Now rounding work load percentages to the 10ths place
* Moved the project into a different directory

### 12/18/13 - Commit 8 ###
* Removed screenshots in favor of live example. Check README.md

### 12/18/13 - Commit 7 ###
* The user that installer now creates is flagged as admin

### 12/18/13 - Commit 6 ###
* Removed a function from install.php on a previous commit. Whoops.

### 12/18/13 - Commit 5 ###
* https://github.com/Ryuske/TimeClock/issues/19 <-- Created wiki https://github.com/Ryuske/TimeClock/wiki
* Moved OLD.md to Previous changelogs in the wiki

### 12/18/13 - Commit 4 ###
* https://github.com/Ryuske/TimeClock/issues/15 <-- Created check_input() function under general_actions interface

### 12/18/13 - Commit 3 ###
* Forgot to update table when deleting employee
* https://github.com/Ryuske/TimeClock/issues/13 <-- Created interfaces for categories/clients/employees/jobs
* Updated comments styling

### 12/18/13 - Commit 2 ###
* https://github.com/Ryuske/TimeClock/issues/26 <-- Split Pay Period and Employee into two different APIs

### 12/18/13 - Commit 1 ###
* There was still an issue with $this->sys->session['user'] from 12/17/13 - Commit 3
* https://github.com/Ryuske/TimeClock/issues/27 <-- Updated SQL file

### 12/17/13 - Commit 4 ###
* Displayed total_hours on jobs home page
* Changed it so that adding a date to a job changes the status to wip
* https://github.com/Ryuske/TimeClock/issues/24 <-- Added work loads

### 12/17/13 - Commit 3 ###
* https://github.com/Ryuske/TimeClock/issues/9 <-- Missed a part - jobs were vulnerable to exploits
* Fixed error where 'user' wasn't set in $this->sys->session
* https://github.com/Ryuske/TimeClock/issues/23 <-- Added quoted times

### 12/17/13 - Commit 2 ###
* https://github.com/Ryuske/TimeClock/issues/9 <-- Added employee roles - admin & management
* PayPeriod API updates - added `role` payperiod/employee API

### 12/17/13 - Commit 1 ###
* Created OLD.md to keep track of CHANGELOGs from previous versions.
* Updated a couple comments
* https://github.com/Ryuske/TimeClock/issues/22 <-- Added Employee ID to home page
* https://github.com/Ryuske/TimeClock/issues/21 <-- Updated API to work with employee_id

### 12/12/13 - Commit 2 ###
* Made installer a little more rebust and you can manually import the database

### 12/12/13 - Commit 1 ###
* https://github.com/Ryuske/TimeClock/issues/20 <-- Fixed installer.

### 12/11/13 - Commit 6 ###
* https://github.com/Ryuske/TimeClock/issues/18 <-- Updated API. Also changed employee_name to name

### 12/11/13 - Commit 5 ###
* https://github.com/Ryuske/TimeClock/issues/16 <-- Updated database

### 12/11/13 - Commit 4 ###
* Pushed to version 2.0
* Added SQL file for job tracking
