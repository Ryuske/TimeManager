var web_root = '/timeclock/timeclock/';

jQuery(document).ready(function() {   
    jQuery('input[type=submit]').button();
    
    jQuery('.date').datepicker({
        altField: '.date_to_add',
        gotoCurrent: true
    });

    jQuery('.date').datepicker('option', 'dateFormat', 'mm/dd/y');
    jQuery('.date').datepicker('option', 'altFormat', 'mm/dd/y');
});
