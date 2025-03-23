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
        // API call logic here
        return [
            'data' => [
                'payment_url' => 'https://example.com/payment?amount=' . $amount
            ]
        ];
    }
}
