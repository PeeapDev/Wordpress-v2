jQuery(document).ready(function($) {
    // Toggle switch change event
    $('#peeap_pay_mode').change(function() {
        var mode = $(this).is(':checked') ? 'live' : 'sandbox';
        $('#mode-label').text(mode.charAt(0).toUpperCase() + mode.slice(1));
        $(this).css('border-color', mode === 'live' ? 'green' : 'yellow');
    });
});
    
