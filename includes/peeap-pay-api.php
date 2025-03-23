<?php

class Peeap_Pay_API {
    private $client_id;
    private $secret_id;
    private $mode;

    public function __construct($client_id, $secret_id, $mode) {
        $this->client_id = $client_id;
        $this->secret_id = $secret_id;
        $this->mode = $mode;
    }

    public function initiate_payment($amount, $currency, $return_url, $cancel_url) {
        // Use the appropriate API URL for live or sandbox
        $api_url = $this->mode === 'live' 
            ? 'https://api.peeappay.com/v1/payment/initiate' 
            : 'https://sandbox.api.peeappay.com/v1/payment/initiate';

        // Prepare the request data
        $data = [
            'client_id' => $this->client_id,
            'secret_id' => $this->secret_id,
            'amount' => $amount,
            'currency' => $currency,
            'return_url' => $return_url,
            'cancel_url' => $cancel_url
        ];

        // Perform the API request
        $response = wp_remote_post($api_url, [
            'method'    => 'POST',
            'body'      => json_encode($data),
            'headers'   => ['Content-Type' => 'application/json'],
        ]);

        // Handle the response
        if (is_wp_error($response)) {
            return ['error' => $response->get_error_message()];
        }

        $body = wp_remote_retrieve_body($response);
        $decoded = json_decode($body, true);

        return $decoded;
    }
}
