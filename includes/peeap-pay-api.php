<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Peeap_Pay_API {
    private $client_id;
    private $secret_id;
    private $mode;
    private $api_url;

    public function __construct($client_id, $secret_id, $mode = 'sandbox') {
        $this->client_id = $client_id;
        $this->secret_id = $secret_id;
        $this->mode = $mode;
        
        // Set API URL based on mode
        if ($this->mode === 'live') {
            $this->api_url = 'https://api.peeap.com/v1/';
        } else {
            $this->api_url = 'https://sandbox.peeap.com/v1/';
        }
    }

    public function initiate_payment($amount, $currency, $return_url, $cancel_url) {
        // Example implementation - replace with actual API integration
        $endpoint = $this->api_url . 'payments/create';
        
        $args = [
            'method' => 'POST',
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->get_access_token(),
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'amount' => $amount,
                'currency' => $currency,
                'return_url' => $return_url,
                'cancel_url' => $cancel_url
            ])
        ];
        
        $response = wp_remote_post($endpoint, $args);
        
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message()
            ];
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        // Mock response for development
        if (empty($body)) {
            return [
                'success' => true,
                'data' => [
                    'payment_id' => 'mock_' . time(),
                    'payment_url' => 'https://example.com/pay?id=mock_' . time()
                ]
            ];
        }
        
        return $body;
    }
    
    private function get_access_token() {
        // Implement token retrieval/generation logic
        // This is a placeholder
        return base64_encode($this->client_id . ':' . $this->secret_id);
    }
}