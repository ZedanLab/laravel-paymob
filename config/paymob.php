<?php
// config for ZedanLab/Paymob

return [
    'payments' => [

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
            'transaction_processed_route' => [
                'uri'    => 'payments/callback',
                'action' => [\ZedanLab\Paymob\Http\Controllers\PaymobCallbackController::class, 'transactionProcessed'],
                'as'     => 'transaction.processed',
            ],
            'transaction_response_route'  => [
                'uri'    => 'payments/callback',
                'action' => [\ZedanLab\Paymob\Http\Controllers\PaymobCallbackController::class, 'transactionResponse'],
                'as'     => 'transaction.response',
            ],
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
    ],

    'payouts'  => [
        'username'      => env('PAYMOB_PAYOUTS_USERNAME'),
        'password'      => env('PAYMOB_PAYOUTS_PASSWORD'),
        'client_id'     => env('PAYMOB_PAYOUTS_CLIENT_ID'),
        'client_secret' => env('PAYMOB_PAYOUTS_CLIENT_SECRET'),
        'endpoints'     => [
            'authentication_request' => 'https://stagingpayouts.paymobsolutions.com/api/secure/o/token/',
            'disburse'               => 'https://stagingpayouts.paymobsolutions.com/api/secure/disburse/',
        ],
    ],
];
