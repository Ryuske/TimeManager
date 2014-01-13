var web_root = '/timemanager/timemanager/';
    
jQuery(document).ready(function() {
    jQuery('input[type=submit]').button();
    
    jQuery('.date').datepicker({
        altField: '.date_to_add',
        gotoCurrent: true
    });
    
    jQuery('.inline_date').datepicker();

    jQuery('.date, .inline_date').datepicker('option', 'dateFormat', 'mm/dd/y');
    jQuery('.date, .inline_date').datepicker('option', 'altFormat', 'mm/dd/y');
    
    if (jQuery('#response').length == 1) {
        var response = document.getElementById('response');
        response.scrollIntoViewIfNeeded();
    }
});
