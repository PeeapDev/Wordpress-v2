<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Save settings when submitted
if ( isset($_POST['peeap_pay_save_settings']) ) {
    update_option('peeap_pay_client_id', sanitize_text_field($_POST['peeap_pay_client_id']));
    update_option('peeap_pay_secret_id', sanitize_text_field($_POST['peeap_pay_secret_id']));
    update_option('peeap_pay_mode', sanitize_text_field($_POST['peeap_pay_mode']));
    echo '<div class="updated"><p>Settings saved.</p></div>';
}

// Retrieve saved settings
$client_id = get_option('peeap_pay_client_id', '');
$secret_id = get_option('peeap_pay_secret_id', '');
$mode = get_option('peeap_pay_mode', 'sandbox');
?>

<div class="wrap">
    <h1>Peeap Pay Integration Settings</h1>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th>Client ID:</th>
                <td><input type="text" name="peeap_pay_client_id" value="<?php echo esc_attr($client_id); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th>Secret ID:</th>
                <td><input type="text" name="peeap_pay_secret_id" value="<?php echo esc_attr($secret_id); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th>Mode:</th>
                <td>
                    <label for="peeap-pay-toggle">
                        <input type="checkbox" id="peeap-pay-toggle" name="peeap_pay_mode" value="live" <?php checked($mode, 'live'); ?>>
                        Live Mode
                    </label>
                    <label for="peeap-pay-toggle">
                        <input type="checkbox" id="peeap-pay-toggle-sandbox" name="peeap_pay_mode" value="sandbox" <?php checked($mode, 'sandbox'); ?>>
                        Sandbox Mode
                    </label>
                </td>
            </tr>
        </table>
        <input type="submit" name="peeap_pay_save_settings" value="Save Settings" class="button button-primary">
    </form>
</div>

<script>
    // JavaScript to handle border color change based on mode
    const modeToggle = document.getElementById('peeap-pay-toggle');
    const sandboxToggle = document.getElementById('peeap-pay-toggle-sandbox');
    const clientIdInput = document.querySelector('input[name="peeap_pay_client_id"]');
    const secretIdInput = document.querySelector('input[name="peeap_pay_secret_id"]');

    function updateBorderColor() {
        if (modeToggle.checked) {
            clientIdInput.style.borderColor = "green";
            secretIdInput.style.borderColor = "green";
        } else if (sandboxToggle.checked) {
            clientIdInput.style.borderColor = "yellow";
            secretIdInput.style.borderColor = "yellow";
        }
    }

    // Call the function on page load
    updateBorderColor();
</script>
// The code snippet above is a settings page for the Peeap Pay plugin. The settings page allows the user to input their client ID, secret ID, and choose between live and sandbox mode. The settings are saved when the form is submitted.