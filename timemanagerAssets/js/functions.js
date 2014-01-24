var quoteId;

function employeeTableClicked(action, employee_id, page_id) {
    switch (action) {
        case 'view':
            window.location = web_root + 'employee/view/' + employee_id;
            break;
        case 'edit':
            window.location = web_root + 'employee/edit/' + employee_id;
            break;
        case 'trash':
            window.location = web_root + 'employee/remove/' + page_id + '/' + employee_id;
            break;
        default:
            break;
    }
}

function jobTableClicked(action, job_id, page_id) {
    switch (action) {
        case 'view':
            window.location = web_root + 'jobs/view/' + job_id;
            break;
        case 'quote':
            window.location = web_root + 'jobs/quote/' + job_id;
            break;
        case 'edit':
            window.location = web_root + 'jobs/edit/' + job_id;
            break;
        case 'trash':
            window.location = web_root + 'jobs/remove/' + page_id + '/' + job_id;
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

function updateQuote(operation, id, value) {
    quoteId = id;
    
    if (operation == 'time') {
        if (value == 'hourly_value') {
            jQuery('.quote_hourly_value_update_dialog .dialog_input').val(jQuery('.time_hourly_value_' + id).eq(1).val());
            jQuery('.quote_hourly_value_update_dialog').dialog('open');
        }
        if (value == 'initial_time') {
            jQuery('.quote_initial_time_update_dialog .dialog_input').val(jQuery('.time_initial_time_' + id).eq(1).val());
            jQuery('.quote_initial_time_update_dialog').dialog('open');
        }
        if (value == 'repeat_time') {
            jQuery('.quote_repeat_time_update_dialog .dialog_input').val(jQuery('.time_repeat_time_' + id).eq(1).val());
            jQuery('.quote_repeat_time_update_dialog').dialog('open');
        }
    } else if (operation == 'quoted_material') {
        quoted_material_id = id;
        
        jQuery('.quote_update_material_dialog .dialog_input[name=description]').val(jQuery('.quoted_material_description_' + id).eq(1).val());
        jQuery('.quote_update_material_dialog .dialog_input[name=vendor]').val(jQuery('.quoted_material_vendor_' + id).eq(1).val());
        jQuery('.quote_update_material_dialog .dialog_input[name=individual_quantity]').val(jQuery('.quoted_material_individual_quantity_' + id).eq(1).val());
        jQuery('.quote_update_material_dialog .dialog_input[name=cost]').val(jQuery('.quoted_material_cost_' + id).eq(1).val());
        jQuery('.quote_update_material_dialog .dialog_input[name=markup]').val(jQuery('.quoted_material_markup_' + id).eq(1).val());
        
        jQuery('.quote_update_material_dialog').dialog('open');
    } else if (operation == 'actual_material') {
        quoted_material_id = id;
        
        jQuery('.actual_update_material_dialog .dialog_input[name=description]').val(jQuery('.actual_material_description_' + id).eq(1).val());
        jQuery('.actual_update_material_dialog .dialog_input[name=vendor]').val(jQuery('.actual_material_vendor_' + id).eq(1).val());
        jQuery('.actual_update_material_dialog .dialog_input[name=individual_quantity]').val(jQuery('.actual_material_individual_quantity_' + id).eq(1).val());
        jQuery('.actual_update_material_dialog .dialog_input[name=cost]').val(jQuery('.actual_material_cost_' + id).eq(1).val());
        jQuery('.actual_update_material_dialog .dialog_input[name=po]').val(jQuery('.actual_material_po_' + id).eq(1).val());
        jQuery('.actual_update_material_dialog .dialog_input[name=delivery_date]').val(jQuery('.actual_material_delivery_date_' + id).eq(1).val());
        
        jQuery('.actual_update_material_dialog').dialog('open');
    } else if (operation == 'quoted_outsource') {
        quoted_outsource_id = id;
        
        jQuery('.quote_update_outsource_dialog .dialog_input[name=process]').val(jQuery('.quoted_outsource_process_' + id).eq(1).val());
        jQuery('.quote_update_outsource_dialog .dialog_input[name=company]').val(jQuery('.quoted_outsource_company_' + id).eq(1).val());
        jQuery('.quote_update_outsource_dialog .dialog_input[name=quantity]').val(jQuery('.quoted_outsource_quantity_' + id).eq(1).val());
        jQuery('.quote_update_outsource_dialog .dialog_input[name=cost]').val(jQuery('.quoted_outsource_cost_' + id).eq(1).val());
        jQuery('.quote_update_outsource_dialog .dialog_input[name=markup]').val(jQuery('.quoted_outsource_markup_' + id).eq(1).val());
        
        jQuery('.quote_update_outsource_dialog').dialog('open');
    } else if (operation == 'actual_outsource') {
        actual_outsource_id = id;
        
        jQuery('.actual_update_outsource_dialog .dialog_input[name=process]').val(jQuery('.actual_outsource_process_' + id).eq(1).val());
        jQuery('.actual_update_outsource_dialog .dialog_input[name=company]').val(jQuery('.actual_outsource_company_' + id).eq(1).val());
        jQuery('.actual_update_outsource_dialog .dialog_input[name=quantity]').val(jQuery('.actual_outsource_quantity_' + id).eq(1).val());
        jQuery('.actual_update_outsource_dialog .dialog_input[name=cost]').val(jQuery('.actual_outsource_cost_' + id).eq(1).val());
        jQuery('.actual_update_outsource_dialog .dialog_input[name=po]').val(jQuery('.actual_outsource_po_' + id).eq(1).val());
        jQuery('.actual_update_outsource_dialog .dialog_input[name=delivery_date]').val(jQuery('.actual_outsource_delivery_date_' + id).eq(1).val());
        
        jQuery('.actual_update_outsource_dialog').dialog('open');
    } else if (operation == 'quoted_sheet') {
        quoted_sheet_id = id;
        
        jQuery('.quote_update_sheet_dialog .dialog_input[name=material]').val(jQuery('.quoted_sheet_material_' + id).eq(1).val());
        jQuery('.quote_update_sheet_dialog .dialog_input[name=vendor]').val(jQuery('.quoted_sheet_vendor_' + id).eq(1).val());
        jQuery('.quote_update_sheet_dialog .dialog_input[name=size]').val(jQuery('.quoted_sheet_size_' + id).eq(1).val());
        jQuery('.quote_update_sheet_dialog .dialog_input[name=lbs_sheet]').val(jQuery('.quoted_sheet_lbs_sheet_' + id).eq(1).val());
        jQuery('.quote_update_sheet_dialog .dialog_input[name=cost_lb]').val(jQuery('.quoted_sheet_cost_lb_' + id).eq(1).val());
        jQuery('.quote_update_sheet_dialog .dialog_input[name=markup]').val(jQuery('.quoted_sheet_markup_' + id).eq(1).val());
        
        jQuery('.quote_update_sheet_dialog').dialog('open');
    } else if (operation == 'actual_sheet') {
        actual_sheet_id = id;
        
        jQuery('.actual_update_sheet_dialog .dialog_input[name=material]').val(jQuery('.actual_sheet_material_' + id).eq(1).val());
        jQuery('.actual_update_sheet_dialog .dialog_input[name=vendor]').val(jQuery('.actual_sheet_vendor_' + id).eq(1).val());
        jQuery('.actual_update_sheet_dialog .dialog_input[name=size]').val(jQuery('.actual_sheet_size_' + id).eq(1).val());
        jQuery('.actual_update_sheet_dialog .dialog_input[name=lbs_sheet]').val(jQuery('.actual_sheet_lbs_sheet_' + id).eq(1).val());
        jQuery('.actual_update_sheet_dialog .dialog_input[name=cost_lb]').val(jQuery('.actual_sheet_cost_lb_' + id).eq(1).val());
        jQuery('.actual_update_sheet_dialog .dialog_input[name=markup]').val(jQuery('.actual_sheet_markup_' + id).eq(1).val());
        
        jQuery('.actual_update_sheet_dialog').dialog('open');
    } else if (operation == 'quoted_blanks') {
        quoted_blank_id = id;
        
        jQuery('.quote_update_blank_dialog .dialog_input[name=sheet_id]').val(jQuery('.quoted_blank_sheet_id_' + id).eq(1).val());
        jQuery('.quote_update_blank_dialog .dialog_input[name=size]').val(jQuery('.quoted_blank_size_' + id).eq(1).val());
        jQuery('.quote_update_blank_dialog .dialog_input[name=blanks_sheet]').val(jQuery('.quoted_blank_blanks_sheet_' + id).eq(1).val());
        jQuery('.quote_update_blank_dialog .dialog_input[name=lbs_blank]').val(jQuery('.quoted_blank_lbs_blank_' + id).eq(1).val());
        
        jQuery('.quote_update_blank_dialog').dialog('open');
    } else if (operation == 'actual_blanks') {
        actual_blank_id = id;
        
        jQuery('.actual_update_blank_dialog .dialog_input[name=sheet_id]').val(jQuery('.actual_blank_sheet_id_' + id).eq(1).val());
        jQuery('.actual_update_blank_dialog .dialog_input[name=size]').val(jQuery('.actual_blank_size_' + id).eq(1).val());
        jQuery('.actual_update_blank_dialog .dialog_input[name=blanks_sheet]').val(jQuery('.actual_blank_blanks_sheet_' + id).eq(1).val());
        jQuery('.actual_update_blank_dialog .dialog_input[name=lbs_blank]').val(jQuery('.actual_blank_lbs_blank_' + id).eq(1).val());
        
        jQuery('.actual_update_blank_dialog').dialog('open');
    } else if (operation == 'quoted_parts') {
        quoted_part_id = id;
        
        jQuery('.quote_update_part_dialog .dialog_input[name=blank_id]').val(jQuery('.quoted_part_blank_id_' + id).eq(1).val());
        jQuery('.quote_update_part_dialog .dialog_input[name=description]').val(jQuery('.quoted_part_description_' + id).eq(1).val());
        jQuery('.quote_update_part_dialog .dialog_input[name=size]').val(jQuery('.quoted_part_size_' + id).eq(1).val());
        jQuery('.quote_update_part_dialog .dialog_input[name=parts_assembly]').val(jQuery('.quoted_part_parts_assembly_' + id).eq(1).val());
        jQuery('.quote_update_part_dialog .dialog_input[name=parts_blank]').val(jQuery('.quoted_part_parts_blank_' + id).eq(1).val());
        
        jQuery('.quote_update_part_dialog').dialog('open');
    } else if (operation == 'actual_parts') {
        actual_part_id = id;
        
        jQuery('.actual_update_part_dialog .dialog_input[name=blank_id]').val(jQuery('.actual_part_blank_id_' + id).eq(1).val());
        jQuery('.actual_update_part_dialog .dialog_input[name=description]').val(jQuery('.actual_part_description_' + id).eq(1).val());
        jQuery('.actual_update_part_dialog .dialog_input[name=size]').val(jQuery('.actual_part_size_' + id).eq(1).val());
        jQuery('.actual_update_part_dialog .dialog_input[name=parts_assembly]').val(jQuery('.actual_part_parts_assembly_' + id).eq(1).val());
        jQuery('.actual_update_part_dialog .dialog_input[name=parts_blank]').val(jQuery('.actual_part_parts_blank_' + id).eq(1).val());
        
        jQuery('.actual_update_part_dialog').dialog('open');
    }
}

function removeRow(object) {
    jQuery(object).parent().html('');
    jQuery('form[name=update_quote_form]').submit();
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

function updateJobInfo(job_id, employee_id, department_id) {
    jQuery('input[name=job_id]').attr('value', job_id);
    jQuery("select[name=employee]").children().each(function() {
        if (jQuery(this).val() == employee_id) {
            jQuery(this).attr("selected", "selected");
        }
    });
    jQuery("select[name=department]").children().each(function() {
        if (jQuery(this).val() == department_id) {
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

function department_operations(operation) {
    switch (operation) {
        case 'add':
            jQuery('.department_add_dialog').dialog('open');
            break;
        case 'edit':
            jQuery('.department_edit_dialog').dialog('open');
            jQuery('.department_name').text(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_name]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_id]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').val());
            break;
        case 'remove':
            jQuery('.department_remove_dialog').dialog('open');
            jQuery('.department_name').text(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').text());
            jQuery('input[name=department_id]').val(jQuery('select[name=department] option[value=' + jQuery('select[name=department]').val() + ']').val());
            break;
        default:
            //None
    }
}