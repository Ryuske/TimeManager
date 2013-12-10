### 12/10/13 - Commit 1 ###
* As per Issue #5 you can now edit jobs
* Removing jobs are about 1/2 done
* Also made the removing employees view including pagination

### 12/9/13 - Commit 1 ###
* https://github.com/Ryuske/TimeClock/issues/5 <-- Started integrating issue #5
* Can add/update/remove clients
* Can add jobs
* Updated main controller comments

### 12/6/13 - Commit 2 ###
* https://github.com/Ryuske/TimeClock/issues/6 <-- Integrated issue #6

### 12/6/13 - Commit 1 ###
* https://github.com/Ryuske/TimeClock/issues/4 <-- Fixed issue #4

### 12/5/13 - Commit 4 ###
* Broke payPeriods.php out into traits

### 12/5/13 - Commit 3 ###
* Got rid of TODO.md in place of GitHub Issues

### 12/5/13 - Commit 2 ###
* Realized that I had to .gitignore timeclockAssets because there is a config value at the top of main.js

### 12/5/13 - Commit 1 ###
* Fixed issue when adding an employee that all usernames would say "username already exists"
* Began moving payPeriod.php into multiple files

### 12/4/13 - Commit 1 ###
* Updated index.php to support framework revisions

### 12/3/13 - Commit 3 ###
* Added a brainstorm section to TODO.md

### 12/3/13 - Commit 2 ###
* Updated login page forms action
* Updated file datestamps
* Refactored rendering pages into the way I actually intended the framework to be used

### 12/3/13 - Commit 1 ###
* Forgot to update application/shared/controller/home.php & userErrors.php to use $this->sys instead of $this->system_di

### 12/2/13 - Commit 5 ###
* Fixed issue with adding date where the correct default date wasn't being selected

### 12/2/13 - Commit 4 ###
* Refactored employees array to be id, firstname, etc instead of employee_id, employee_firstname, etc
* Fixed problem with settings form with options not pre-loading properly
* Fixed issue with adding date where you could end up with two dates (12/2/13 and 12/02/13).

### 12/2/13 - Commit 3 ###
* Updating .gitignore

### 12/2/13 - Commit 2 ###
* Updated file datestamps
* Removed unnessecary methods
* Fixed issues with adding pay periods

### 12/2/13 - Commit 1 ###
* Changed views to use token arrays instead of class::writeout()

### 11/27/13 - Commit 1-2 ###
* Updated TimeClock to be compatible with new version of framework

### 11/26/13 - Commit 7 ###
* Added previous pay periods to the total_hours API

### 11/26/13 - Commit 6 ###
* Made part of the installation easier to follow

### 11/26/11 - Commit 5 ###
* Fixed issue with .htaccess where timeclock.sql was being read instead of index.php?route=$1
* Corrected renderPage USAGE comments
* Simplified installation & updated README.md to reflect the changes

### 11/26/13 - Commit 4  ###
* Updated README.md and About GitHub links to go to the repo instead of my account
* Fixed slight issue with payPeriod model; $date = strtolower($date); would error when a date was entered instead of current or all
* Added a check to model_timeclock_payPeriod->add_pay_period() so that it won't add multiple pay periods that are the same
* Forgot to add settings to payperiod->printer_friendly() which caused rounding hours to fail
* Fixed Issue in model_timeclock_payPeriod->is_time_editable() where the 2nd 'in' would be editable even if the first wasn't set
* Exported the database, timeclock.sql

### 11/26/13 - Commit 3 ###
* Added strtolower() to string based parameters
* Updated USAGE examples on models. Hopefully they're a little easier to understand now.

### 11/26/13 - Commit 2 ###
* Changed controller/employee.php to used renderPage within each method like the rest of the controllers
* Updated some comments
* Added pagination to previous pay periods

### 11/26/13 - Commit 1 ###
* Fixed issues with getting existing pay period & adding new ones
* Added information about settings to README.me and About page
* Decided to page previous pay periods by rows. So 10 rows is 40 pay periods

### 11/25/13 - Commit 1 ###
* Updated README format
* Paginated employees on home page
* Added setting to paginate by, any number works, 0 disabled pagination
* Updated framework - check framework changelog of details

### 11/22/13 - Commit 4 ###
* Updated README and About with information about last date
* Fixed an infinite loop in editing a profile when generating a random UID
* Started working on pagination

### 11/22/13 - Commit 3 ###
* Added last_date to API
* Integrated settings into everything else
* Changed load_dependencies to also add them to $this->system_di->template
* Changed employee listings to work off $this->system_di->template instead of passing variables through functions
* Decided not to make variable pay period start and end days

### 11/22/13 - Commit 2 ###
* Updated TODO.md
* Updated model naming convention
* Refactored model loading to parent function and loads models via dependency array

### 11/22/13 - Commit 1 ###
* Fixed issue when adding dates under a pay period
* Fixed issue where if the out time was 0 the total hours would be a crazy negative number
* Changed it Add Date so that it will only add a date if all the previous in/out slots have been filled
* Updated comments
* Updated README.md and /applications/shared/view/timeclock/about.php

### 11/21/13 - Commits 2-5 ###
* Formating README.md
* Converting TODO & CHANGELOG to TODO.md & CHANGELOG.md

### 11/21/13 - Commit 1 (since starting CHANGELOG) ###
* Refactored comments
* Added settings, still need to implement the settings into the rest of the TimeClock
* Converted README to README.md for readability on GitHub
