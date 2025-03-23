<?php
/**
 * Plugin Name: Peeap Pay
 * Description: Integrate Peeap Pay Payment Gateway into WordPress.
 * Version: 2.1.1
 * Author: Mohamed Abdul Kabia
 * Author URI: https://account.peeap.com
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PEEAP_PAY_VERSION', '1.0.0');
define('PEEAP_PAY_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include necessary files
require_once PEEAP_PAY_PLUGIN_DIR . 'includes/class-peeap-api.php';
require_once PEEAP_PAY_PLUGIN_DIR . 'includes/class-peeap-payment.php';

// Hook to initialize plugin
function peeap_pay_init() {
    if (!is_admin()) {
        // Enqueue frontend styles and scripts
        wp_enqueue_style('peeap-pay-style', plugins_url('assets/css/style.css', __FILE__));
        wp_enqueue_script('peeap-pay-script', plugins_url('assets/js/script.js', __FILE__), array('jquery'), '', true);
    }
}
add_action('wp_enqueue_scripts', 'peeap_pay_init');

// Add shortcode for displaying payment form
function peeap_pay_shortcode($atts) {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'templates/peeap-payment-form.php');
    return ob_get_clean();
}
add_shortcode('peeap_pay_form', 'peeap_pay_shortcode');

function peeap_pay_ajax() {
    if (isset($_POST['amount'], $_POST['currency'], $_POST['custom'])) {
        $client_id = 'YOUR_CLIENT_ID';
        $secret_id = 'YOUR_SECRET_ID';
        $payment = new Peeap_Payment($client_id, $secret_id);
        
        $payment_url = $payment->create_payment(
            $_POST['amount'],
            $_POST['currency'],
            'https://yourwebsite.com/success',
            'https://yourwebsite.com/cancel',
            $_POST['custom']
        );
        
        if ($payment_url) {
            wp_send_json_success(array('payment_url' => $payment_url));
        } else {
            wp_send_json_error();
        }
    }
    wp_die();
}
add_action('wp_ajax_peeap_process_payment', 'peeap_pay_ajax');
add_action('wp_ajax_nopriv_peeap_process_payment', 'peeap_pay_ajax');
