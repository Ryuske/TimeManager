# README #
TimeClock was developed as an open-source time management system that can interface with nearly anything.

The original idea was that it would be used with an RFID scanner for punching in & out, however, through
the development it was abstracted using a RESTFUL API and can be used by anything that can make a web
call.

TimeClock was developed by Kenyon Haliwell and can be downloaded at https://github.com/Ryuske.

# API #
The API is very simple, and for our examples we are going to assume that TimeClock was installed at
http://example.com/timeclock

## API for punching in & out ##
    http://example.com/timeclock/payperiod/tx/[uid] 
        Where [uid] can be anything you want, but the auto generator will generate a 4-byte hex ID.
        
        The responses will be in this format: Kenyon, in, 11/18/13, 8:00am

    ### Example UID ###
        0x00 0x00 0x00 0x00
        Please note that the UID for every employee MUST be different, but it can be anything you want.

## Rest of the API ##
    http://example.com/timeclock/payperiod/rx/[uid]/employee_name
        This returns the full name of the employee with a format of [firstname] [lastname]
    
    http://example.com/timeclock/payperiod/tx/[uid]/last_op
        This returns "in" or "out" (without quotes) depending on if the employee punched in or out last.
        
    http://example.com/timeclock/payperiod/tx/[uid]/last_time
        This will return the last time the employee punched in or out. Format: 5:00pm, 12:00am, etc
        
    http://example.com/timeclock/payperiod/tx/[uid]/total_hours/<pay period>
        This will return the total hours an employee worked for the given pay period. If no pay period is
        given it will return the current pay period. The response is in hours (i.e. 17.25 hours)
        
        Pay Period: This can be given in almost any format, however a date within that pay period works best.
            Example: 11/18/13 - would return the pay period 11/18/13-11/24/13
        
# Explaination of Backend #
Once you login the first page you're presented with is the full list of employees. To add a new employee all
you do is look over to the right, and click the "Add New Employee" button near the top and fill out the basic
information.

## Adding An Employee ##
    When you add an employee, if you specify a username you must enter a password. Once you've given them a
    username and password they will be able to login to the backend and perform any of the operations. If
    you don't want them to have access to the management panel, don't enter a username

    Also note, that even if the generate UID checkbox is checked, if there is something written in that box,
    it will take precedence. So if you want it to generate a UID, make sure the text box is empty.

## Editing An Employee ##
    Once you've added an employee or two, you can click on the pencil to the right of their name and edit
    their profile. The edit employee form looks exactly the same as adding a new employee, and it also works
    the same way.

## Removing An Employee ##
    You can remove an employee by clicking on the trash can to the right of their name. You'll be presented
    with a confirmation box with their name and confirm and cancel buttons.
    
## Viewing a Pay Period ##
    You can view an employees' times by clicking on their name on the home page. The first pay period you will
    see is the current pay period. Below the current pay period table is a list of all the pay periods that
    the system is aware of. Any pay period that is bold is one that the employee has time under. The italic
    pay period is the one you are currently looking at.
    
    A pay period is defined as Monday-Sunday.
    
    The total hours for the pay period are displayed up by the employees name in the blue bar.
    
    You can remove a date from a pay period if you "zero" out all the times for that date (or row, more
    specifically). Just enter blank times for all of them and the date will be removed.
    
    ### Edit a Time ###
        You can edit any time that the employee has worked by clicking on the time you wish to edit. You'll
        be presented with a box that you can enter the time into. All total hours will auto populate. You can
        also edit the next in/out fields that are blank.
    
    ### Add a Date ###
        If you click the Add Date button that is below the pay period table, you can add a date worked to the
        table. You can also add duplicate dates in-case the employee punched in and out more than 3 times. When
        you click Add Date you'll be given a calendar where the only dates you can click are the ones within the
        pay period that you are looking at.
        
    ### Print a Timecard ###
        You can view a printer friendly version of the pay period by clicking on the Print button to the right
        of the employees name in the blue bar. It just gives a very basic table with the employees name, pay
        period you're looking at, total hours and hours worked.
        
## Punching In and Out ##
    Please refer to the API on how to punch an employee in and out.

# Installation #
To install TimeClock, you will have to download https://github.com/Ryuske/PHP-Framework which is a framework that was also developed by
Kenyon Haliwell. Refer to the installation instructions of the framework for what goes where. However, the directory struture of TimeClock
is the same as the framework. So it's just copy & paste.

Once you have all the files installed. you're going to have to import timeclock.sql into a database and update the file in /configuration/
to use your MySQL information. For more information on how to do this, please refer to the frameworks README. It will explain how the
configuration files work.

After you have imported the database, you'll have to manually add a user to the employees table. The password will have to be a MD5 hash.

*** Please note that simplifying the installation is on the TODO list ***