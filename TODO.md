# TODO #
* Add error handling
* Figure out how to split payPeriod into more models or something.
* There is an issue in editing times to where sometimes it will edit the time in-front of it instead. Need to figure out what causes it.
* Figure out why installation isn't working

## Job Tracking ##
* Figure out if I am actually going to be adding job tracking
* Add a system for assigning a category to employees
** Add/Edit/Remove
** Category Name
** Category UID
* Create an add/edit/remove system for jobs, like employees.
** Client name
** Job name
** Job ID (UID)
* Create a table that displays all jobs
* Once clicked on a job, display a timelog (similar to pay period)
** Show date
** Show time in/time out
** Show employee employee name/UID
** Allow adding time
*** Date
*** Time in/out
*** Employee UID
*** Category worked - by employee
*** Total time
* RESTFUL API for jobs
** job id
** employee uid
** track time, (calculate if in/out depending on last_op)
** return total time
** return job name
** return client name