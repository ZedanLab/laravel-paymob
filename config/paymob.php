<?php
// config for ZedanLab/Paymob
return [
    'api_key'         => env('PAYMOB_API_KEY'),
    'currency'        => env('PAYMOB_CURRENCY', 'EGP'),

    'payment_methods' => [
        'card' => [
            'integration_id' => env('PAYMOB_CARD_INTEGRATION_ID'),
            'iframe_id'      => env('PAYMOB_CARD_IFRAME_ID'),
            'iframe_link'     => 'https://accept.paymobsolutions.com/api/acceptance/iframes/{:iframe_id}?payment_token={:payment_key_token}',
        ],
    ],

    'endpoints'       => [
        'authentication_request' => 'https://accept.paymobsolutions.com/api/auth/tokens',
        'order_registration'     => 'https://accept.paymob.com/api/ecommerce/orders',
        'payment_keys'           => 'https://accept.paymobsolutions.com/api/acceptance/payment_keys',
    ],
];
