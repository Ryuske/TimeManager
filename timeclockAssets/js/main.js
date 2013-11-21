var web_root = '/tnbtimeclock/timeclock/';

jQuery(document).ready(function() {   
    jQuery('input[type=submit]').button();
    
    jQuery('.remove_employee_dialog').dialog({
        resizable: false,
        autoOpen: true,
        height: 175,
        title: 'Remove Employee',
        modal: true,
        buttons: {
            'Remove Employee': function() {
                jQuery('.remove_employee_form').submit();
            },
            'Cancel': function() {
                window.location = web_root;
            }
        }
    });
    
    jQuery('.update_time_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        title: 'Update Time',
        modal: true,
        buttons: {
            'Update Time': function() {
                jQuery('.update_time_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.add_date_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 365,
        width: 350,
        title: 'Add Date',
        modal: true,
        buttons: {
            'Add Date': function() {
                jQuery('.add_date_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.date').datepicker({
        altField: '.date_to_add',
        altFormat: 'm/d/y',
        gotoCurrent: true,
        minDate: createMinDate(jQuery('.start_date').text()),
        maxDate: createMaxDate(jQuery('.start_date').text())
    });
    jQuery('.date').datepicker('setDate', jQuery('.start_date').text());
}); //End doucment.ready

function employeeTableClicked(action, employee_id) {
    switch (action) {
        case 'view':
            window.location = web_root + 'employee/view/' + employee_id;
            break;
        case 'edit':
            window.location = web_root + 'employee/edit/' + employee_id;
            break;
        case 'trash':
            window.location = web_root + 'employee/remove/' + employee_id;
            break;
        default:
            break;
    } //End switch action
} //End employeeTableClicked

function updateTime(date, time_index, time_operation) {
    var multiplier;
    
    if (time_operation == 'in') {
        if (time_index > 2) {
            multiplier = (time_index+2)*2 - 1;
        } else {
            multiplier = (time_index+1)*2 - 1;
        }
    } else {
        if (time_index > 2) {
            multiplier = (time_index+2)*2;
        } else {
            multiplier = (time_index+1)*2;
        }
    }
    
    jQuery('.dialog_input[name=time]').attr('value', jQuery('table tbody tr:contains(\'' + date + '\') td').eq(multiplier).text());
    jQuery('.dialog_title').text(date);
    
    jQuery('.update_time_dialog input[name=date]').attr('value', date);
    jQuery('.update_time_dialog input[name=time_index]').attr('value', time_index);
    jQuery('.update_time_dialog input[name=time_operation]').attr('value', time_operation);
    jQuery('.update_time_dialog').dialog('open');
} //End updateTime

function loadPayPeriod(employee_id, pay_period) {
    window.location = web_root + 'employee/view/' + employee_id + '/' + pay_period;
} //ENd loadPayPeriod

function addDate(pay_period) {   
    jQuery('.add_date_dialog input[name=pay_period]').attr('value', pay_period);
    jQuery('.add_date_dialog').dialog('open');
} //End addDate

/**
 * createMinDate & createMaxDate are both used by the jQuery UI datepicker
 * to make it so you can only select dates within the current pay period
 */
function createMinDate(date) {
    date = date.split('/');
    if (date[2].length == 2) {
        date[2] = '20' + date[2];
    }
    
    return new Date(date[2], date[0] - 1, date[1]);
} //End createMinDate

function createMaxDate(date) {
    date = date.split('/');
    date[1] = parseInt(date[1]) + 6;
    
    if (date[2].length == 2) {
        date[2] = '20' + date[2];
    }
    
    return new Date(date[2], date[0] - 1, date[1]);
} //End createMaxDate