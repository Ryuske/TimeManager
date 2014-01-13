jQuery(document).ready(function() {
    /**
     * Employee Dialogs
     */
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
    /**
     * END employee dialogs
     */
    
    /**
     * Date/time dialogs
     */
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
    
    jQuery('.update_info_dialog').dialog({
        resizable: false,
        autoOpen: false,
        width: 350,
        title: 'Update Info',
        modal: true,
        buttons: {
            'Update Info': function() {
                jQuery('.update_info_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.add_date_response').dialog({
        resizable: false,
        autoOpen: false,
        width: 350,
        title: 'Add Date Response',
        modal: true,
        buttons: {
            'OK': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END date/time dialogs
     */
    
    /**
     * Client dialogs
     */
    jQuery('.client_add_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 225,
        width: 250,
        title: 'Add Client',
        modal: true,
        buttons: {
            'Add Client': function() {
                jQuery('.add_client_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.client_edit_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 225,
        width: 250,
        title: 'Edit Client',
        modal: true,
        buttons: {
            'Edit Client': function() {
                jQuery('.edit_client_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.client_remove_dialog').dialog({
        resizable: false,
        autoOpen: false,
        title: 'Remove Client',
        modal: true,
        buttons: {
            'Remove Client': function() {
                jQuery('.remove_client_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END client dialogs
     */
    
    /**
     * Department dialogs
     */
    jQuery('.department_add_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 300,
        title: 'Add Department',
        modal: true,
        buttons: {
            'Add Department': function() {
                jQuery('.add_department_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.department_edit_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 225,
        width: 300,
        title: 'Edit Department',
        modal: true,
        buttons: {
            'Edit Department': function() {
                jQuery('.edit_department_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.department_remove_dialog').dialog({
        resizable: false,
        autoOpen: false,
        title: 'Remove Department',
        modal: true,
        buttons: {
            'Remove': function() {
                jQuery('.remove_department_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END department dialogs
     */
    
    /**
     * Jobs dialogs
     */
    jQuery('.remove_job_dialog').dialog({
        resizable: false,
        autoOpen: true,
        height: 200,
        title: 'Remove Job',
        modal: true,
        buttons: {
            'Remove Job': function() {
                jQuery('.remove_job_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.job_attachments_dialog').dialog({
        autoOpen: false,
        width: 345,
        title: 'Job Attachments',
        modal: true,
        buttons: {
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END jobs dialogs
     */
    
    /**
     * Quotes dialogs
     */
    jQuery('.quote_hourly_value_update_dialog').dialog({
        autoOpen: false,
        width: 345,
        title: 'Update Hourly Value',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.time_hourly_value_' + quoteId).eq(0).html(jQuery('.quote_hourly_value_update_dialog .dialog_input').val());
                jQuery('.time_hourly_value_' + quoteId).eq(1).val(jQuery('.quote_hourly_value_update_dialog .dialog_input').val());
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_initial_time_update_dialog').dialog({
        autoOpen: false,
        width: 345,
        title: 'Update Initial Time',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.time_initial_time_' + quoteId).eq(0).html(jQuery('.quote_initial_time_update_dialog .dialog_input').val());
                jQuery('.time_initial_time_' + quoteId).eq(1).val(jQuery('.quote_initial_time_update_dialog .dialog_input').val());
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_repeat_time_update_dialog').dialog({
        autoOpen: false,
        width: 345,
        title: 'Update Repeat Time',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.time_repeat_time_' + quoteId).eq(0).html(jQuery('.quote_repeat_time_update_dialog .dialog_input').val());
                jQuery('.time_repeat_time_' + quoteId).eq(1).val(jQuery('.quote_repeat_time_update_dialog .dialog_input').val());
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_add_material_dialog').dialog({
        autoOpen: false,
        width: 345,
        title: 'Add Material',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseMaterialsQuote table tbody').append(
                    '<tr>' +
                    '<td><span class="quoted_material_description_' + quoted_material_id + '">' + jQuery('.quote_add_material_dialog .dialog_input').val() + '</span> <input class="quoted_material_description_' + quoted_material_id + '" name="quotes[material][' + quoted_material_id + '][description]" type="hidden" value="' + jQuery('.quote_add_material_dialog .dialog_input[name=description]').val() + '" /></td>' +
                    '<td><span class="quoted_material_vendor_' + quoted_material_id + '">' + jQuery('.quote_add_material_dialog .dialog_input[name=vendor]').val() + '</span> <input class="quoted_material_vendor_' + quoted_material_id + '" name="quotes[material][' + quoted_material_id + '][vendor]" type="hidden" value="' + jQuery('.quote_add_material_dialog .dialog_input[name=vendor]').val() + '" /></td>' +
                    '<td><span class="quoted_material_individual_quantity_' + quoted_material_id + '">' + jQuery('.quote_add_material_dialog .dialog_input[name=individual_quantity]').val() + '</span> <input class="quoted_material_individual_quantity_' + quoted_material_id + '" name="quotes[material][' + quoted_material_id + '][individual_quantity]" type="hidden" value="' + jQuery('.quote_add_material_dialog .dialog_input[name=individual_quantity]').val() + '" /></td>' +
                    '<td></td>' +
                    '<td>$<span class="quoted_material_cost_' + quoted_material_id + '">' + jQuery('.quote_add_material_dialog .dialog_input[name=cost]').val() + '</span> <input class="quoted_material_cost_' + quoted_material_id + '" name="quotes[material][' + quoted_material_id + '][cost]" type="hidden" value="' + jQuery('.quote_add_material_dialog .dialog_input[name=cost]').val() + '" /></td>' +
                    '<td><span class="quoted_material_markup_' + quoted_material_id + '">' + jQuery('.quote_add_material_dialog .dialog_input[name=markup]').val() + '</span>% <input class="quoted_material_markup_' + quoted_material_id + '" name="quotes[material][' + quoted_material_id + '][markup]" type="hidden" value="' + jQuery('.quote_add_material_dialog .dialog_input[name=markup]').val() + '" /></td>' +
                    '<td></td>' +
                    '</tr>'
                );
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END jobs dialogs
     */
});