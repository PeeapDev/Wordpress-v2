<?php
/*
Plugin Name: Peeap Pay Integration
Description: Integrates Peeap Pay API for payments on your WordPress site.
Version: 1.1
Author: Your Name
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/peeap-pay-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/peeap-pay-settings.php';

// Initialize plugin
function peeap_pay_init() {
    // Plugin initialization logic (if needed)
}
add_action('plugins_loaded', 'peeap_pay_init');

// Load styles and scripts
function peeap_pay_enqueue_scripts() {
    wp_enqueue_style('peeap-pay-style', plugin_dir_url(__FILE__) . 'assets/css/peeap-pay.css');
    wp_enqueue_script('peeap-pay-script', plugin_dir_url(__FILE__) . 'assets/js/peeap-pay.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'peeap_pay_enqueue_scripts');

// Shortcode for payment button
function peeap_pay_payment_button($atts) {
    $atts = shortcode_atts([
        'amount' => '10.00',
        'currency' => 'USD',
        'return_url' => site_url('/payment-success'),
        'cancel_url' => site_url('/payment-cancel'),
    ], $atts);

    // Get settings
    $mode = get_option('peeap_mode', 'sandbox');
    $client_id = ($mode === 'live') ? get_option('peeap_live_client_id') : get_option('peeap_sandbox_client_id');
    $secret_id = ($mode === 'live') ? get_option('peeap_live_secret_id') : get_option('peeap_sandbox_secret_id');

    // Initialize API
    $api = new Peeap_Pay_API($client_id, $secret_id, $mode);
    $response = $api->initiate_payment($atts['amount'], $atts['currency'], $atts['return_url'], $atts['cancel_url']);

    if (isset($response['data']['payment_url'])) {
        return '<a href="' . esc_url($response['data']['payment_url']) . '" class="peeap-pay-button">Pay Now</a>';
    } else {
        return 'Payment initiation failed.';
    }
}
add_shortcode('peeap_pay_button', 'peeap_pay_payment_button');

// Admin Menu
add_action('admin_menu', 'peeap_pay_add_admin_menu');

function peeap_pay_add_admin_menu() {
    add_menu_page(
        'Peeap Pay Settings',
        'Peeap Pay',
        'manage_options',
        'peeap-pay-settings',
        'peeap_pay_settings_page',
        'dashicons-admin-generic',
        60
    );
}

// Settings Page
function peeap_pay_settings_page() {
    ?>
    <div class="wrap">
        <h1>Peeap Pay Integration</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('peeap_pay_options_group');
            do_settings_sections('peeap-pay-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'peeap_pay_register_settings');

function peeap_pay_register_settings() {
    register_setting('peeap_pay_options_group', 'peeap_mode');
    register_setting('peeap_pay_options_group', 'peeap_live_client_id');
    register_setting('peeap_pay_options_group', 'peeap_live_secret_id');
    register_setting('peeap_pay_options_group', 'peeap_sandbox_client_id');
    register_setting('peeap_pay_options_group', 'peeap_sandbox_secret_id');

    add_settings_section('peeap_pay_main_section', 'API Credentials', null, 'peeap-pay-settings');

    add_settings_field('peeap_mode', 'Mode', 'peeap_pay_mode_callback', 'peeap-pay-settings', 'peeap_pay_main_section');
    add_settings_field('peeap_live_client_id', 'Live Client ID', 'peeap_live_client_id_callback', 'peeap-pay-settings', 'peeap_pay_main_section');
    add_settings_field('peeap_live_secret_id', 'Live Secret ID', 'peeap_live_secret_id_callback', 'peeap-pay-settings', 'peeap_pay_main_section');
    add_settings_field('peeap_sandbox_client_id', 'Sandbox Client ID', 'peeap_sandbox_client_id_callback', 'peeap-pay-settings', 'peeap_pay_main_section');
    add_settings_field('peeap_sandbox_secret_id', 'Sandbox Secret ID', 'peeap_sandbox_secret_id_callback', 'peeap-pay-settings', 'peeap_pay_main_section');
}

// Callback for mode selection
function peeap_pay_mode_callback() {
    $mode = get_option('peeap_mode', 'sandbox');
    ?>
    <select name="peeap_mode">
        <option value="sandbox" <?php selected($mode, 'sandbox'); ?>>Sandbox</option>
        <option value="live" <?php selected($mode, 'live'); ?>>Live</option>
    </select>
    <p>Select the API mode. Use <strong>Sandbox</strong> for testing and <strong>Live</strong> for real transactions.</p>
    <?php
}

// Callbacks for API fields
function peeap_live_client_id_callback() {
    $value = get_option('peeap_live_client_id');
    echo '<input type="text" name="peeap_live_client_id" value="' . esc_attr($value) . '" class="regular-text">';
}

function peeap_live_secret_id_callback() {
    $value = get_option('peeap_live_secret_id');
    echo '<input type="text" name="peeap_live_secret_id" value="' . esc_attr($value) . '" class="regular-text">';
}

function peeap_sandbox_client_id_callback() {
    $value = get_option('peeap_sandbox_client_id');
    echo '<input type="text" name="peeap_sandbox_client_id" value="' . esc_attr($value) . '" class="regular-text">';
}

function peeap_sandbox_secret_id_callback() {
    $value = get_option('peeap_sandbox_secret_id');
    echo '<input type="text" name="peeap_sandbox_secret_id" value="' . esc_attr($value) . '" class="regular-text">';
}
?>
