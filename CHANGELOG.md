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