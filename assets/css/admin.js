jQuery(document).ready(function($) {
    let modeToggle = $('#peeap_mode_toggle');
    let modeText = $('#mode_text');
    let liveFields = $('#live_credentials');
    let sandboxFields = $('#sandbox_credentials');

    function updateModeUI() {
        if (modeToggle.is(':checked')) {
            modeText.text('Live');
            liveFields.show();
            sandboxFields.hide();
        } else {
            modeText.text('Sandbox');
            liveFields.hide();
            sandboxFields.show();
        }
    }

    // Set initial state on page load
    updateModeUI();

    // Change event for toggle
    modeToggle.on('change', function() {
        let newMode = modeToggle.is(':checked') ? 'live' : 'sandbox';

        // Update option via AJAX (to save settings without refresh)
        $.post(ajaxurl, {
            action: 'save_peeap_mode',
            peeap_mode: newMode
        });

        // Update UI instantly
        updateModeUI();
    });
});
