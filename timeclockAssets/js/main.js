var web_root = '/tnbtimeclock/timeclock/';

jQuery(document).ready(function() {   
    jQuery('input[type=submit]').button();
    
    jQuery('.date').datepicker({
        altField: '.date_to_add',
        gotoCurrent: true,
        minDate: new Date(jQuery('.start_date').text()),
        maxDate: new Date(jQuery('.end_date').text()),
    });

    jQuery('.date').datepicker('option', 'dateFormat', 'mm/dd/y');
    jQuery('.date').datepicker('option', 'altFormat', 'mm/dd/y');
});