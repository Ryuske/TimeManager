var quoted_material_id;

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
        width: 400,
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
    jQuery('.quote_update_material_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Material',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.quoted_material_description_' + quoted_material_id).eq(0).text(jQuery('.quote_update_material_dialog .dialog_input[name=description]').val());
                jQuery('.quoted_material_vendor_' + quoted_material_id).eq(0).text(jQuery('.quote_update_material_dialog .dialog_input[name=vendor]').val());
                jQuery('.quoted_material_individual_quantity_' + quoted_material_id).eq(0).text(jQuery('.quote_update_material_dialog .dialog_input[name=individual_quantity]').val());
                jQuery('.quoted_material_cost_' + quoted_material_id).eq(0).text(jQuery('.quote_update_material_dialog .dialog_input[name=cost]').val());
                jQuery('.quoted_material_markup_' + quoted_material_id).eq(0).text(jQuery('.quote_update_material_dialog .dialog_input[name=markup]').val());
                
                jQuery('.quoted_material_description_' + quoted_material_id).eq(1).val(jQuery('.quote_update_material_dialog .dialog_input[name=description]').val());
                jQuery('.quoted_material_vendor_' + quoted_material_id).eq(1).val(jQuery('.quote_update_material_dialog .dialog_input[name=vendor]').val());
                jQuery('.quoted_material_individual_quantity_' + quoted_material_id).eq(1).val(jQuery('.quote_update_material_dialog .dialog_input[name=individual_quantity]').val());
                jQuery('.quoted_material_cost_' + quoted_material_id).eq(1).val(jQuery('.quote_update_material_dialog .dialog_input[name=cost]').val());
                jQuery('.quoted_material_markup_' + quoted_material_id).eq(1).val(jQuery('.quote_update_material_dialog .dialog_input[name=markup]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_add_material_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Material',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseMaterialsActual table tbody').append(
                    '<tr>' +
                    '<td><span class="actual_material_description_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input').val() + '</span> <input class="actual_material_description_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][description]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=description]').val() + '" /></td>' +
                    '<td><span class="actual_material_vendor_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input[name=vendor]').val() + '</span> <input class="actual_material_vendor_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][vendor]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=vendor]').val() + '" /></td>' +
                    '<td><span class="actual_material_individual_quantity_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input[name=individual_quantity]').val() + '</span> <input class="actual_material_individual_quantity_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][individual_quantity]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=individual_quantity]').val() + '" /></td>' +
                    '<td></td>' +
                    '<td>$<span class="actual_material_cost_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input[name=cost]').val() + '</span> <input class="actual_material_cost_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][cost]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=cost]').val() + '" /></td>' +
                    '<td></td>' +
                    '<td><span class="actual_material_po_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input[name=po]').val() + '</span> <input class="actual_material_po_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][po]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=po]').val() + '" /></td>' +
                    '<td><span class="actual_material_delivery_date_' + quoted_material_id + '">' + jQuery('.actual_add_material_dialog .dialog_input[name=delivery_date]').val() + '</span> <input class="actual_material_delivery_date_' + quoted_material_id + '" name="actuals[material][' + quoted_material_id + '][delivery_date]" type="hidden" value="' + jQuery('.actual_add_material_dialog .dialog_input[name=delivery_date]').val() + '" /></td>' +
                    '</tr>'
                );
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_update_material_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Material',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.actual_material_description_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=description]').val());
                jQuery('.actual_material_vendor_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=vendor]').val());
                jQuery('.actual_material_individual_quantity_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=individual_quantity]').val());
                jQuery('.actual_material_cost_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=cost]').val());
                jQuery('.actual_material_po_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=po]').val());
                jQuery('.actual_material_delivery_date_' + quoted_material_id).eq(0).text(jQuery('.actual_update_material_dialog .dialog_input[name=delivery_date]').val());
                
                jQuery('.actual_material_description_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=description]').val());
                jQuery('.actual_material_vendor_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=vendor]').val());
                jQuery('.actual_material_individual_quantity_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=individual_quantity]').val());
                jQuery('.actual_material_cost_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=cost]').val());
                jQuery('.actual_material_po_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=po]').val());
                jQuery('.actual_material_delivery_date_' + quoted_material_id).eq(1).val(jQuery('.actual_update_material_dialog .dialog_input[name=delivery_date]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_add_outsource_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Outsource',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseOutsourceQuote table tbody').append(
                    '<tr>' +
                        '<td><span class="quoted_outsource_process' + quoted_outsource_id + '">' +jQuery('.quote_add_outsource_dialog .dialog_input').val() + '</span>' +
                            '<input class="quoted_outsource_process_' + quoted_outsource_id +
                            '" name="quotes[outsource][' + quoted_outsource_id + '][process]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_outsource_dialog .dialog_input[name=process]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_outsource_company' + quoted_outsource_id + '">' + jQuery('.quote_add_outsource_dialog .dialog_input[name=company]').val() + '</span>' +
                            '<input class="quoted_outsource_vendor_' + quoted_outsource_id +
                            '" name="quotes[outsource][' + quoted_outsource_id + '][company]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_outsource_dialog .dialog_input[name=company]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_outsource_quantity' + quoted_outsource_id + '">' + jQuery('.quote_add_outsource_dialog .dialog_input[name=quantity]').val() + '</span>' +
                            '<input class="quoted_outsource_quantity_' + quoted_outsource_id + '"' +
                            ' name="quotes[outsource][' + quoted_outsource_id + '][quantity]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_outsource_dialog .dialog_input[name=quantity]').val() +
                        '" /></td>' +
                        '<td>$<span class="quoted_outsource_cost_' + quoted_outsource_id + '">' + jQuery('.quote_add_outsource_dialog .dialog_input[name=cost]').val() + '</span>' +
                            '<input class="quoted_outsource_cost_' + quoted_outsource_id + '"' +
                            ' name="quotes[outsource][' + quoted_outsource_id + '][cost]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_outsource_dialog .dialog_input[name=cost]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_outsource_markup_' + quoted_outsource_id + '">' + jQuery('.quote_add_outsource_dialog .dialog_input[name=markup]').val() + '</span>%' +
                            '<input class="quoted_outsource_markup_' + quoted_outsource_id + '"' +
                            ' name="quotes[outsource][' + quoted_outsource_id + '][markup]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_outsource_dialog .dialog_input[name=markup]').val() +
                        '" /></td>' +
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
    jQuery('.quote_update_outsource_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Outsource',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.quoted_outsource_process_' + quoted_outsource_id).eq(0).text(jQuery('.quote_update_outsource_dialog .dialog_input[name=process]').val());
                jQuery('.quoted_outsource_company_' + quoted_outsource_id).eq(0).text(jQuery('.quote_update_outsource_dialog .dialog_input[name=company]').val());
                jQuery('.quoted_outsource_quantity_' + quoted_outsource_id).eq(0).text(jQuery('.quote_update_outsource_dialog .dialog_input[name=quantity]').val());
                jQuery('.quoted_outsource_cost_' + quoted_outsource_id).eq(0).text(jQuery('.quote_update_outsource_dialog .dialog_input[name=cost]').val());
                jQuery('.quoted_outsource_markup_' + quoted_outsource_id).eq(0).text(jQuery('.quote_update_outsource_dialog .dialog_input[name=markup]').val());
                
                jQuery('.quoted_outsource_process_' + quoted_outsource_id).eq(1).val(jQuery('.quote_update_outsource_dialog .dialog_input[name=process]').val());
                jQuery('.quoted_outsource_company_' + quoted_outsource_id).eq(1).val(jQuery('.quote_update_outsource_dialog .dialog_input[name=company]').val());
                jQuery('.quoted_outsource_quantity_' + quoted_outsource_id).eq(1).val(jQuery('.quote_update_outsource_dialog .dialog_input[name=quantity]').val());
                jQuery('.quoted_outsource_cost_' + quoted_outsource_id).eq(1).val(jQuery('.quote_update_outsource_dialog .dialog_input[name=cost]').val());
                jQuery('.quoted_outsource_markup_' + quoted_outsource_id).eq(1).val(jQuery('.quote_update_outsource_dialog .dialog_input[name=markup]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_add_outsource_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Outsource',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseOutsourceActual table tbody').append(
                    '<tr>' +
                        '<td><span class="actual_outsource_process_' + actual_outsource_id + '">' +jQuery('.actual_add_outsource_dialog .dialog_input').val() + '</span>' +
                            '<input class="actual_outsource_process_' + actual_outsource_id +
                            '" name="actuals[outsource][' + actual_outsource_id + '][process]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=process]').val() +
                        '" /></td>' +
                        '<td><span class="actual_outsource_company_' + actual_outsource_id + '">' + jQuery('.actual_add_outsource_dialog .dialog_input[name=company]').val() + '</span>' +
                            '<input class="actual_outsource_vendor_' + actual_outsource_id +
                            '" name="actuals[outsource][' + actual_outsource_id + '][company]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=company]').val() +
                        '" /></td>' +
                        '<td><span class="actual_outsource_quantity_' + actual_outsource_id + '">' + jQuery('.actual_add_outsource_dialog .dialog_input[name=quantity]').val() + '</span>' +
                            '<input class="actual_outsource_quantity_' + actual_outsource_id + '"' +
                            ' name="actuals[outsource][' + actual_outsource_id + '][quantity]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=quantity]').val() +
                        '" /></td>' +
                        '<td>$<span class="actual_outsource_cost_' + actual_outsource_id + '">' + jQuery('.actual_add_outsource_dialog .dialog_input[name=cost]').val() + '</span>' +
                            '<input class="actual_outsource_cost_' + actual_outsource_id + '"' +
                            ' name="actuals[outsource][' + actual_outsource_id + '][cost]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=cost]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td><span class="actual_outsource_po_' + actual_outsource_id + '">' + jQuery('.actual_add_outsource_dialog .dialog_input[name=po]').val() + '</span>' +
                            '<input class="actual_outsource_po_' + actual_outsource_id + '"' +
                            ' name="actuals[outsource][' + actual_outsource_id + '][po]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=po]').val() +
                        '" /></td>' +
                        '<td><span class="actual_outsource_delivery_date_' + actual_outsource_id + '">' + jQuery('.actual_add_outsource_dialog .dialog_input[name=delivery_date]').val() + '</span>' +
                            '<input class="actual_outsource_delivery_date_' + actual_outsource_id + '"' +
                            ' name="actuals[outsource][' + actual_outsource_id + '][delivery_date]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_outsource_dialog .dialog_input[name=delivery_date]').val() +
                        '" /></td>' +
                    '</tr>'
                );
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_update_outsource_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Outsource',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.actual_outsource_process_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=process]').val());
                jQuery('.actual_outsource_company_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=company]').val());
                jQuery('.actual_outsource_quantity_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=quantity]').val());
                jQuery('.actual_outsource_cost_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=cost]').val());
                jQuery('.actual_outsource_po_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=po]').val());
                jQuery('.actual_outsource_delivery_date_' + actual_outsource_id).eq(0).text(jQuery('.actual_update_outsource_dialog .dialog_input[name=delivery_date]').val());
                
                jQuery('.actual_outsource_process_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=process]').val());
                jQuery('.actual_outsource_company_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=company]').val());
                jQuery('.actual_outsource_quantity_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=quantity]').val());
                jQuery('.actual_outsource_cost_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=cost]').val());
                jQuery('.actual_outsource_po_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=po]').val());
                jQuery('.actual_outsource_delivery_date_' + actual_outsource_id).eq(1).val(jQuery('.actual_update_outsource_dialog .dialog_input[name=delivery_date]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_add_sheet_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Sheet',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseSheetsQuote table tbody').append(
                    '<tr>' +
                        '<td>' + quoted_sheet_id + '</td>' +
                        '<td><span class="quoted_sheet_material' + quoted_sheet_id + '">' +jQuery('.quote_add_sheet_dialog .dialog_input').val() + '</span>' +
                            '<input class="quoted_sheet_material_' + quoted_sheet_id +
                            '" name="quotes[sheets][' + quoted_sheet_id + '][material]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=material]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_sheet_vendor' + quoted_sheet_id + '">' + jQuery('.quote_add_sheet_dialog .dialog_input[name=vendor]').val() + '</span>' +
                            '<input class="quoted_sheet_vendor_' + quoted_sheet_id +
                            '" name="quotes[sheets][' + quoted_sheet_id + '][vendor]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=vendor]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_sheet_size' + quoted_sheet_id + '">' + jQuery('.quote_add_sheet_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="quoted_sheet_size_' + quoted_sheet_id + '"' +
                            ' name="quotes[sheets][' + quoted_sheet_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_sheet_lbs_sheet_' + quoted_sheet_id + '">' + jQuery('.quote_add_sheet_dialog .dialog_input[name=lbs_sheet]').val() + '</span>' +
                            '<input class="quoted_sheet_lbs_sheet_' + quoted_sheet_id + '"' +
                            ' name="quotes[sheets][' + quoted_sheet_id + '][lbs_sheet]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=lbs_sheet]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td>$<span class="quoted_sheet_cost_lb_' + quoted_sheet_id + '">' + jQuery('.quote_add_sheet_dialog .dialog_input[name=cost_lb]').val() + '</span>' +
                            '<input class="quoted_sheet_cost_lb_' + quoted_sheet_id + '"' +
                            ' name="quotes[sheets][' + quoted_sheet_id + '][cost_lb]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=cost_lb]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td><span class="quoted_sheet_markup_' + quoted_sheet_id + '">' + jQuery('.quote_add_sheet_dialog .dialog_input[name=markup]').val() + '</span>%' +
                            '<input class="quoted_sheet_markup_' + quoted_sheet_id + '"' +
                            ' name="quotes[sheets][' + quoted_sheet_id + '][markup]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_sheet_dialog .dialog_input[name=markup]').val() +
                        '" /></td>' +
                    '</tr>'
                );
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_update_sheet_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Sheet',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.quoted_sheet_material_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=material]').val());
                jQuery('.quoted_sheet_vendor_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=vendor]').val());
                jQuery('.quoted_sheet_size_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_sheet_lbs_sheet_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=lbs_sheet]').val());
                jQuery('.quoted_sheet_cost_lb_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=cost_lb]').val());
                jQuery('.quoted_sheet_markup_' + quoted_sheet_id).eq(0).text(jQuery('.quote_update_sheet_dialog .dialog_input[name=markup]').val());
                
                jQuery('.quoted_sheet_material_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=material]').val());
                jQuery('.quoted_sheet_vendor_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=vendor]').val());
                jQuery('.quoted_sheet_size_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_sheet_lbs_sheet_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=lbs_sheet]').val());
                jQuery('.quoted_sheet_cost_lb_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=cost_lb]').val());
                jQuery('.quoted_sheet_markup_' + quoted_sheet_id).eq(1).val(jQuery('.quote_update_sheet_dialog .dialog_input[name=markup]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_add_sheet_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Sheet',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseSheetsActual table tbody').append(
                    '<tr>' +
                        '<td>' + actual_sheet_id + '</td>' +
                        '<td><span class="actual_sheet_material' + actual_sheet_id + '">' +jQuery('.actual_add_sheet_dialog .dialog_input').val() + '</span>' +
                            '<input class="actual_sheet_material_' + actual_sheet_id +
                            '" name="actuals[sheets][' + actual_sheet_id + '][material]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_sheet_dialog .dialog_input[name=material]').val() +
                        '" /></td>' +
                        '<td><span class="actual_sheet_vendor' + actual_sheet_id + '">' + jQuery('.actual_add_sheet_dialog .dialog_input[name=vendor]').val() + '</span>' +
                            '<input class="actual_sheet_vendor_' + actual_sheet_id +
                            '" name="actuals[sheets][' + actual_sheet_id + '][vendor]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_sheet_dialog .dialog_input[name=vendor]').val() +
                        '" /></td>' +
                        '<td><span class="actual_sheet_size' + actual_sheet_id + '">' + jQuery('.actual_add_sheet_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="actual_sheet_size_' + actual_sheet_id + '"' +
                            ' name="actuals[sheets][' + actual_sheet_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_sheet_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="actual_sheet_lbs_sheet_' + actual_sheet_id + '">' + jQuery('.actual_add_sheet_dialog .dialog_input[name=lbs_sheet]').val() + '</span>' +
                            '<input class="actual_sheet_lbs_sheet_' + actual_sheet_id + '"' +
                            ' name="actuals[sheets][' + actual_sheet_id + '][lbs_sheet]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_sheet_dialog .dialog_input[name=lbs_sheet]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td>$<span class="actual_sheet_cost_lb_' + actual_sheet_id + '">' + jQuery('.actual_add_sheet_dialog .dialog_input[name=cost_lb]').val() + '</span>' +
                            '<input class="actual_sheet_cost_lb_' + actual_sheet_id + '"' +
                            ' name="actuals[sheets][' + actual_sheet_id + '][cost_lb]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_sheet_dialog .dialog_input[name=cost_lb]').val() +
                        '" /></td>' +
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
    jQuery('.actual_update_sheet_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Sheet',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.actual_sheet_material_' + actual_sheet_id).eq(0).text(jQuery('.actual_update_sheet_dialog .dialog_input[name=material]').val());
                jQuery('.actual_sheet_vendor_' + actual_sheet_id).eq(0).text(jQuery('.actual_update_sheet_dialog .dialog_input[name=vendor]').val());
                jQuery('.actual_sheet_size_' + actual_sheet_id).eq(0).text(jQuery('.actual_update_sheet_dialog .dialog_input[name=size]').val());
                jQuery('.actual_sheet_lbs_sheet_' + actual_sheet_id).eq(0).text(jQuery('.actual_update_sheet_dialog .dialog_input[name=lbs_sheet]').val());
                jQuery('.actual_sheet_cost_lb_' + actual_sheet_id).eq(0).text(jQuery('.actual_update_sheet_dialog .dialog_input[name=cost_lb]').val());
                
                jQuery('.actual_sheet_material_' + actual_sheet_id).eq(1).val(jQuery('.actual_update_sheet_dialog .dialog_input[name=material]').val());
                jQuery('.actual_sheet_vendor_' + actual_sheet_id).eq(1).val(jQuery('.actual_update_sheet_dialog .dialog_input[name=vendor]').val());
                jQuery('.actual_sheet_size_' + actual_sheet_id).eq(1).val(jQuery('.actual_update_sheet_dialog .dialog_input[name=size]').val());
                jQuery('.actual_sheet_lbs_sheet_' + actual_sheet_id).eq(1).val(jQuery('.actual_update_sheet_dialog .dialog_input[name=lbs_sheet]').val());
                jQuery('.actual_sheet_cost_lb_' + actual_sheet_id).eq(1).val(jQuery('.actual_update_sheet_dialog .dialog_input[name=cost_lb]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_add_blank_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Blank',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseBlanksQuote table tbody').append(
                    '<tr>' +
                        '<td>' + quoted_blank_id + '</td>' +
                        '<td><span class="quoted_blank_sheet_id' + quoted_blank_id + '">' +jQuery('.quote_add_blank_dialog .dialog_input').val() + '</span>' +
                            '<input class="quoted_blank_sheet_id_' + quoted_blank_id +
                            '" name="quotes[blanks][' + quoted_blank_id + '][sheet_id]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_blank_dialog .dialog_input[name=sheet_id]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_blank_size' + quoted_blank_id + '">' + jQuery('.quote_add_blank_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="quoted_blank_size_' + quoted_blank_id + '"' +
                            ' name="quotes[blanks][' + quoted_blank_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_blank_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_blank_blanks_sheet_' + quoted_blank_id + '">' + jQuery('.quote_add_blank_dialog .dialog_input[name=blanks_sheet]').val() + '</span>' +
                            '<input class="quoted_blank_blanks_sheet_' + quoted_blank_id + '"' +
                            ' name="quotes[blanks][' + quoted_blank_id + '][blanks_sheet]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_blank_dialog .dialog_input[name=blanks_sheet]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td><span class="quoted_blank_lbs_blank_' + quoted_blank_id + '">' + jQuery('.quote_add_blank_dialog .dialog_input[name=lbs_blank]').val() + '</span>' +
                            '<input class="quoted_blank_lbs_blank_' + quoted_blank_id + '"' +
                            ' name="quotes[blanks][' + quoted_blank_id + '][lbs_blank]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_blank_dialog .dialog_input[name=lbs_blank]').val() +
                        '" /></td>' +
                        '<td></td>' +
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
    jQuery('.quote_update_blank_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Blank',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.quoted_blank_sheet_id_' + quoted_blank_id).eq(0).text(jQuery('.quote_update_blank_dialog .dialog_input[name=sheet_id]').val());
                jQuery('.quoted_blank_size_' + quoted_blank_id).eq(0).text(jQuery('.quote_update_blank_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_blank_blanks_sheet_' + quoted_blank_id).eq(0).text(jQuery('.quote_update_blank_dialog .dialog_input[name=blanks_sheet]').val());
                jQuery('.quoted_blank_lbs_blank_' + quoted_blank_id).eq(0).text(jQuery('.quote_update_blank_dialog .dialog_input[name=lbs_blank]').val());
                
                jQuery('.quoted_blank_sheet_id_' + quoted_blank_id).eq(1).val(jQuery('.quote_update_blank_dialog .dialog_input[name=sheet_id]').val());
                jQuery('.quoted_blank_size_' + quoted_blank_id).eq(1).val(jQuery('.quote_update_blank_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_blank_blanks_sheet_' + quoted_blank_id).eq(1).val(jQuery('.quote_update_blank_dialog .dialog_input[name=blanks_sheet]').val());
                jQuery('.quoted_blank_lbs_blank_' + quoted_blank_id).eq(1).val(jQuery('.quote_update_blank_dialog .dialog_input[name=lbs_blank]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_add_blank_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Blank',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapseBlanksActual table tbody').append(
                    '<tr>' +
                        '<td>' + actual_blank_id + '</td>' +
                        '<td><span class="actual_blank_sheet_id' + actual_blank_id + '">' +jQuery('.actual_add_blank_dialog .dialog_input').val() + '</span>' +
                            '<input class="actual_blank_sheet_id_' + actual_blank_id +
                            '" name="actuals[blanks][' + actual_blank_id + '][sheet_id]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_blank_dialog .dialog_input[name=sheet_id]').val() +
                        '" /></td>' +
                        '<td><span class="actual_blank_size' + actual_blank_id + '">' + jQuery('.actual_add_blank_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="actual_blank_size_' + actual_blank_id + '"' +
                            ' name="actuals[blanks][' + actual_blank_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_blank_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="actual_blank_blanks_sheet_' + actual_blank_id + '">' + jQuery('.actual_add_blank_dialog .dialog_input[name=blanks_sheet]').val() + '</span>' +
                            '<input class="actual_blank_blanks_sheet_' + actual_blank_id + '"' +
                            ' name="actuals[blanks][' + actual_blank_id + '][blanks_sheet]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_blank_dialog .dialog_input[name=blanks_sheet]').val() +
                        '" /></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td><span class="actual_blank_lbs_blank_' + actual_blank_id + '">' + jQuery('.actual_add_blank_dialog .dialog_input[name=lbs_blank]').val() + '</span>' +
                            '<input class="actual_blank_lbs_blank_' + actual_blank_id + '"' +
                            ' name="actuals[blanks][' + actual_blank_id + '][lbs_blank]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_blank_dialog .dialog_input[name=lbs_blank]').val() +
                        '" /></td>' +
                        '<td></td>' +
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
    jQuery('.actual_update_blank_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Blank',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.actual_blank_sheet_id_' + actual_blank_id).eq(0).text(jQuery('.actual_update_blank_dialog .dialog_input[name=sheet_id]').val());
                jQuery('.actual_blank_size_' + actual_blank_id).eq(0).text(jQuery('.actual_update_blank_dialog .dialog_input[name=size]').val());
                jQuery('.actual_blank_blanks_sheet_' + actual_blank_id).eq(0).text(jQuery('.actual_update_blank_dialog .dialog_input[name=blanks_sheet]').val());
                jQuery('.actual_blank_lbs_blank_' + actual_blank_id).eq(0).text(jQuery('.actual_update_blank_dialog .dialog_input[name=lbs_blank]').val());
                
                jQuery('.actual_blank_sheet_id_' + actual_blank_id).eq(1).val(jQuery('.actual_update_blank_dialog .dialog_input[name=sheet_id]').val());
                jQuery('.actual_blank_size_' + actual_blank_id).eq(1).val(jQuery('.actual_update_blank_dialog .dialog_input[name=size]').val());
                jQuery('.actual_blank_blanks_sheet_' + actual_blank_id).eq(1).val(jQuery('.actual_update_blank_dialog .dialog_input[name=blanks_sheet]').val());
                jQuery('.actual_blank_lbs_blank_' + actual_blank_id).eq(1).val(jQuery('.actual_update_blank_dialog .dialog_input[name=lbs_blank]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.quote_add_part_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Part',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapsePartsQuote table tbody').append(
                    '<tr>' +
                        '<td><span class="quoted_part_blank_id' + quoted_part_id + '">' +jQuery('.quote_add_part_dialog .dialog_input').val() + '</span>' +
                            '<input class="quoted_part_blank_id_' + quoted_part_id +
                            '" name="quotes[parts][' + quoted_part_id + '][blank_id]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_part_dialog .dialog_input[name=blank_id]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_part_description' + quoted_part_id + '">' + jQuery('.quote_add_part_dialog .dialog_input[name=description]').val() + '</span>' +
                            '<input class="quoted_part_description_' + quoted_part_id + '"' +
                            ' name="quotes[parts][' + quoted_part_id + '][description]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_part_dialog .dialog_input[name=description]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_part_size' + quoted_part_id + '">' + jQuery('.quote_add_part_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="quoted_part_size_' + quoted_part_id + '"' +
                            ' name="quotes[parts][' + quoted_part_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_part_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_part_parts_assembly_' + quoted_part_id + '">' + jQuery('.quote_add_part_dialog .dialog_input[name=parts_assembly]').val() + '</span>' +
                            '<input class="quoted_part_parts_assembly_' + quoted_part_id + '"' +
                            ' name="quotes[parts][' + quoted_part_id + '][parts_assembly]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_part_dialog .dialog_input[name=parts_assembly]').val() +
                        '" /></td>' +
                        '<td><span class="quoted_part_parts_blank_' + quoted_part_id + '">' + jQuery('.quote_add_part_dialog .dialog_input[name=parts_blank]').val() + '</span>' +
                            '<input class="quoted_part_parts_blank_' + quoted_part_id + '"' +
                            ' name="quotes[parts][' + quoted_part_id + '][parts_blank]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.quote_add_part_dialog .dialog_input[name=parts_blank]').val() +
                        '" /></td>' +
                        '<td></td>' +
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
    jQuery('.quote_update_part_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Part',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.quoted_part_blank_id_' + quoted_part_id).eq(0).text(jQuery('.quote_update_part_dialog .dialog_input[name=blank_id]').val());
                jQuery('.quoted_part_description_' + quoted_part_id).eq(0).text(jQuery('.quote_update_part_dialog .dialog_input[name=description]').val());
                jQuery('.quoted_part_size_' + quoted_part_id).eq(0).text(jQuery('.quote_update_part_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_part_parts_assembly_' + quoted_part_id).eq(0).text(jQuery('.quote_update_part_dialog .dialog_input[name=parts_assembly]').val());
                jQuery('.quoted_part_parts_blank_' + quoted_part_id).eq(0).text(jQuery('.quote_update_part_dialog .dialog_input[name=parts_blank]').val());
                
                jQuery('.quoted_part_blank_id_' + quoted_part_id).eq(1).val(jQuery('.quote_update_part_dialog .dialog_input[name=blank_id]').val());
                jQuery('.quoted_part_description_' + quoted_part_id).eq(1).val(jQuery('.quote_update_part_dialog .dialog_input[name=description]').val());
                jQuery('.quoted_part_size_' + quoted_part_id).eq(1).val(jQuery('.quote_update_part_dialog .dialog_input[name=size]').val());
                jQuery('.quoted_part_parts_assembly_' + quoted_part_id).eq(1).val(jQuery('.quote_update_part_dialog .dialog_input[name=parts_assembly]').val());
                jQuery('.quoted_part_parts_blank_' + quoted_part_id).eq(1).val(jQuery('.quote_update_part_dialog .dialog_input[name=parts_blank]').val());
                
                jQuery('form[name=update_quote_form]').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery('.actual_add_part_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Add Part',
        modal: true,
        buttons: {
            'Add': function() {
                jQuery('#collapsePartsActual table tbody').append(
                    '<tr>' +
                        '<td><span class="actual_part_blank_id' + actual_part_id + '">' +jQuery('.actual_add_part_dialog .dialog_input').val() + '</span>' +
                            '<input class="actual_part_blank_id_' + actual_part_id +
                            '" name="actuals[parts][' + actual_part_id + '][blank_id]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_part_dialog .dialog_input[name=blank_id]').val() +
                        '" /></td>' +
                        '<td><span class="actual_part_description' + actual_part_id + '">' + jQuery('.actual_add_part_dialog .dialog_input[name=description]').val() + '</span>' +
                            '<input class="actual_part_description_' + actual_part_id + '"' +
                            ' name="actuals[parts][' + actual_part_id + '][description]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_part_dialog .dialog_input[name=description]').val() +
                        '" /></td>' +
                        '<td><span class="actual_part_size' + actual_part_id + '">' + jQuery('.actual_add_part_dialog .dialog_input[name=size]').val() + '</span>' +
                            '<input class="actual_part_size_' + actual_part_id + '"' +
                            ' name="actuals[parts][' + actual_part_id + '][size]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_part_dialog .dialog_input[name=size]').val() +
                        '" /></td>' +
                        '<td><span class="actual_part_parts_assembly_' + actual_part_id + '">' + jQuery('.actual_add_part_dialog .dialog_input[name=parts_assembly]').val() + '</span>' +
                            '<input class="actual_part_parts_assembly_' + actual_part_id + '"' +
                            ' name="actuals[parts][' + actual_part_id + '][parts_assembly]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_part_dialog .dialog_input[name=parts_assembly]').val() +
                        '" /></td>' +
                        '<td><span class="actual_part_parts_blank_' + actual_part_id + '">' + jQuery('.actual_add_part_dialog .dialog_input[name=parts_blank]').val() + '</span>' +
                            '<input class="actual_part_parts_blank_' + actual_part_id + '"' +
                            ' name="actuals[parts][' + actual_part_id + '][parts_blank]"' +
                            ' type="hidden"' +
                            ' value="' + jQuery('.actual_add_part_dialog .dialog_input[name=parts_blank]').val() +
                        '" /></td>' +
                        '<td></td>' +
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
    jQuery('.actual_update_part_dialog').dialog({
        autoOpen: false,
        width: 400,
        title: 'Update Part',
        modal: true,
        buttons: {
            'Update': function() {
                jQuery('.actual_part_blank_id_' + actual_part_id).eq(0).text(jQuery('.actual_update_part_dialog .dialog_input[name=blank_id]').val());
                jQuery('.actual_part_description_' + actual_part_id).eq(0).text(jQuery('.actual_update_part_dialog .dialog_input[name=description]').val());
                jQuery('.actual_part_size_' + actual_part_id).eq(0).text(jQuery('.actual_update_part_dialog .dialog_input[name=size]').val());
                jQuery('.actual_part_parts_assembly_' + actual_part_id).eq(0).text(jQuery('.actual_update_part_dialog .dialog_input[name=parts_assembly]').val());
                jQuery('.actual_part_parts_blank_' + actual_part_id).eq(0).text(jQuery('.actual_update_part_dialog .dialog_input[name=parts_blank]').val());
                
                jQuery('.actual_part_blank_id_' + actual_part_id).eq(1).val(jQuery('.actual_update_part_dialog .dialog_input[name=blank_id]').val());
                jQuery('.actual_part_description_' + actual_part_id).eq(1).val(jQuery('.actual_update_part_dialog .dialog_input[name=description]').val());
                jQuery('.actual_part_size_' + actual_part_id).eq(1).val(jQuery('.actual_update_part_dialog .dialog_input[name=size]').val());
                jQuery('.actual_part_parts_assembly_' + actual_part_id).eq(1).val(jQuery('.actual_update_part_dialog .dialog_input[name=parts_assembly]').val());
                jQuery('.actual_part_parts_blank_' + actual_part_id).eq(1).val(jQuery('.actual_update_part_dialog .dialog_input[name=parts_blank]').val());
                
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