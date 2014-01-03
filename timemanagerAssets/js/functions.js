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
    }
}

function jobTableClicked(action, job_id) {
    switch (action) {
        case 'view':
            window.location = web_root + 'jobs/view/' + job_id;
            break;
        case 'edit':
            window.location = web_root + 'jobs/edit/' + job_id;
            break;
        case 'trash':
            window.location = web_root + 'jobs/remove/' + job_id;
            break;
        case 'attachments':
            jQuery('.job_attachments_dialog input[name=job_id]').attr('value', job_id);
            jQuery('.attachments').html('');
            jQuery.ajax(web_root + 'jobs/get_attachments/' + job_id).done(function(msg) {
                var json = jQuery.parseJSON(msg);
                
                jQuery.each(json, function(id, object) {
                    jQuery('.attachments').append('<tr id="' + id + '"><td><a href="' + object.path + '" target="_blank">' + object.name + '</a></td><td><span class="ui-icon ui-icon-trash" onclick="removeAttachment(' + id + ')"></span></td></tr>');
                });
            });
            jQuery('.job_attachments_dialog').dialog('open');
            break;
        default:
            break;
    }
}

function removeAttachment(attachment_id) {
    jQuery.ajax(web_root + 'jobs/remove_attachment/' + attachment_id).done(function(msg) {
        if (msg == 'true') {
            jQuery('#' + attachment_id).html('');
        }
    });
}

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
}

function updateJobTime(id, object, operation) {
    jQuery('.dialog_input[name=time]').attr('value', object.text());
    jQuery('.dialog_title').text(object.parent().children('td').eq(0).html());
    
    jQuery('.update_time_dialog input[name=id]').attr('value', id);
    jQuery('.update_time_dialog input[name=operation]').attr('value', operation);
    jQuery('.update_time_dialog').dialog('open');
}

function updateJobInfo(job_id, employee_id, category_id) {
    jQuery('input[name=job_id]').attr('value', job_id);
    jQuery("select[name=employee]").children().each(function() {
        if (jQuery(this).val() == employee_id) {
            jQuery(this).attr("selected", "selected");
        }
    });
    jQuery("select[name=category]").children().each(function() {
        if (jQuery(this).val() == category_id) {
            jQuery(this).attr("selected", "selected");
        }
    });

    jQuery('.update_info_dialog').dialog('open');
}

function loadPayPeriod(employee_id, pay_period) {
    window.location = web_root + 'employee/view/' + employee_id + '/' + pay_period;
}

function addDate(/*id, operation='pay_period'*/) {
    var id = arguments[0];
    var operation='pay_period';
    
    if (arguments.length > 1) {
        operation = arguments[1];
    }
    
    switch (operation) {
        case 'pay_period':
            jQuery('.date').datepicker('option', 'minDate', new Date(jQuery('.start_date').text()));
            jQuery('.date').datepicker('option', 'maxDate', new Date(jQuery('.end_date').text()));
            break;
        default:
            //Do nothing
    }
    
    jQuery('.add_date_dialog input[name=id]').attr('value', id);
    jQuery('.add_date_dialog').dialog('open');
}

function add_date_response(response) {
    jQuery('.add_date_response_text').text = response;
    jQuery('.add_date_response').dialog('open');
}

function client_operations(operation) {
    switch (operation) {
        case 'add':
            jQuery('.client_add_dialog').dialog('open');
            break;
        case 'edit':
            jQuery('.client_edit_dialog').dialog('open');
            jQuery('.client_name').text(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_name]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_id]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').val());
            break;
        case 'remove':
            jQuery('.client_remove_dialog').dialog('open');
            jQuery('.client_name').text(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').text());
            jQuery('input[name=client_id]').val(jQuery('select[name=client] option[value=' + jQuery('select[name=client]').val() + ']').val());
            break;
        default:
            //None
    }
}

function category_operations(operation) {
    switch (operation) {
        case 'add':
            jQuery('.category_add_dialog').dialog('open');
            break;
        case 'edit':
            jQuery('.category_edit_dialog').dialog('open');
            jQuery('.category_name').text(jQuery('select[name=category] option[value=' + jQuery('select[name=category]').val() + ']').text());
            jQuery('input[name=category_name]').val(jQuery('select[name=category] option[value=' + jQuery('select[name=category]').val() + ']').text());
            jQuery('input[name=category_id]').val(jQuery('select[name=category] option[value=' + jQuery('select[name=category]').val() + ']').val());
            break;
        case 'remove':
            jQuery('.category_remove_dialog').dialog('open');
            jQuery('.category_name').text(jQuery('select[name=category] option[value=' + jQuery('select[name=category]').val() + ']').text());
            jQuery('input[name=category_id]').val(jQuery('select[name=category] option[value=' + jQuery('select[name=category]').val() + ']').val());
            break;
        default:
            //None
    }
}