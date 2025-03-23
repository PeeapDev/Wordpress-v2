<?php
/*
Plugin Name: Peeap Pay Integration
Description: Integrates Peeap Pay API for payments on your WordPress site.
Version: 2.1
Author: Mohamed Abdul Kabia
*/

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
require_once plugin_dir_path( __FILE__ ) . 'includes/peeap-pay-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/peeap-pay-settings.php';

// Add a menu item to the WordPress admin menu
function peeap_pay_add_admin_menu() {
    add_menu_page( 
        'Peeap Pay Settings', 
        'Peeap Pay', 
        'manage_options', 
        'peeap-pay-settings', 
        'peeap_pay_settings_page', 
        'dashicons-admin-generic', 
        50
    );
}
add_action( 'admin_menu', 'peeap_pay_add_admin_menu' );

// Initialize the plugin
function peeap_pay_init() {
    // Plugin initialization logic here
}
add_action( 'plugins_loaded', 'peeap_pay_init' );

// Load plugin assets
function peeap_pay_enqueue_scripts() {
    wp_enqueue_style( 'peeap-pay-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
    wp_enqueue_script( 'peeap-pay-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array('jquery'), null, true );
}
add_action( 'admin_enqueue_scripts', 'peeap_pay_enqueue_scripts' );

// Shortcode to display the payment button
function peeap_pay_payment_button( $atts ) {
    $atts = shortcode_atts( [
        'amount' => '10.00',
        'currency' => 'USD',
        'return_url' => site_url( '/payment-success' ),
        'cancel_url' => site_url( '/payment-cancel' ),
    ], $atts );

    $client_id = get_option( 'peeap_pay_client_id' );
    $secret_id = get_option( 'peeap_pay_secret_id' );
    $mode = get_option( 'peeap_pay_mode' );
    
    $api = new Peeap_Pay_API( $client_id, $secret_id, $mode );

    $response = $api->initiate_payment( $atts['amount'], $atts['currency'], $atts['return_url'], $atts['cancel_url'] );

    if ( isset( $response['data']['payment_url'] ) ) {
        return '<a href="' . esc_url( $response['data']['payment_url'] ) . '" class="peeap-pay-button">Pay Now</a>';
    } else {
        return 'Payment initiation failed.';
    }
}
add_shortcode( 'peeap_pay_button', 'peeap_pay_payment_button' );
