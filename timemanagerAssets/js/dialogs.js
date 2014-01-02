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
     * Category dialogs
     */
    jQuery('.category_add_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 300,
        title: 'Add Category',
        modal: true,
        buttons: {
            'Add Category': function() {
                jQuery('.add_category_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.category_edit_dialog').dialog({
        resizable: false,
        autoOpen: false,
        height: 200,
        width: 300,
        title: 'Edit Category',
        modal: true,
        buttons: {
            'Edit Category': function() {
                jQuery('.edit_category_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    
    jQuery('.category_remove_dialog').dialog({
        resizable: false,
        autoOpen: false,
        title: 'Remove Category',
        modal: true,
        buttons: {
            'Remove Category': function() {
                jQuery('.remove_category_form').submit();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        }
    });
    /**
     * END category dialogs
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
});