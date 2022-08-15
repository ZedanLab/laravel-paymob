<?php
// config for ZedanLab/Paymob
return [
    'api_key'         => env('PAYMOB_API_KEY'),
    'hmac_secret'     => env('PAYMOB_HMAC_SECRET'),
    'currency'        => env('PAYMOB_CURRENCY', 'EGP'),

    'payment_methods' => [
        'card'          => [
            'integration_id' => env('PAYMOB_CARD_INTEGRATION_ID'),
            'iframe_id'      => env('PAYMOB_CARD_IFRAME_ID'),
            'iframe_link'    => 'https://accept.paymobsolutions.com/api/acceptance/iframes/{:iframe_id}?payment_token={:payment_key_token}',
        ],
        'mobile_wallet' => [
            'integration_id' => env('PAYMOB_MOBILE_WALLET_INTEGRATION_ID'),
        ],
    ],

    'callbacks'       => [
        'transaction_processed_route' => 'payments/callback',
        'transaction_response_route'  => 'payments/callback',
    ],

    'redirects'       => [
        'success' => env('PAYMOB_SUCCESS_REDIRECT', 'http://localhost/payments/success'), // use route name or url
        'failed' => env('PAYMOB_FAILED_REDIRECT', 'http://localhost/payments/failed'), // use route name or url
    ],

    'endpoints'       => [
        'authentication_request' => 'https://accept.paymobsolutions.com/api/auth/tokens',
        'order_registration'     => 'https://accept.paymob.com/api/ecommerce/orders',
        'payment_keys'           => 'https://accept.paymobsolutions.com/api/acceptance/payment_keys',
        'pay'                    => 'https://accept.paymobsolutions.com/api/acceptance/payments/pay',
    ],
];
