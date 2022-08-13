<?php

use Illuminate\Support\Facades\Http;

use function Pest\Faker\faker;

use ZedanLab\Paymob\Paymob;
use ZedanLab\Paymob\Services\PaymobConfig;
use ZedanLab\Paymob\Services\PaymobOrder;

// it('can authenticate and get access token if credentials is correct', function () {
//     fakeSuccessResponse();
//     $paymob = new Paymob(config('paymob'));

//     expect($paymob->accessToken() === getApiKey());
// });

// it('throws an exception if credentials are incorrect', function () {
//     Http::fake([
//         config('paymob.endpoints.authentication_request') => Http::response([
//             'detail' => 'incorrect credentials',
//         ], 403),
//     ]);

//     new Paymob(config('paymob'));

// })->expectErrorMessage('HTTP request returned status code 403:
// {"detail":"incorrect credentials"}');

it('register a new order', function () {
    fakeSuccessResponse();

    $paymentLink = (new Paymob(config('paymob')))
        ->setOrder(makeOrder())
        ->payWith('card')
        ->paymentLink();

    $endpoint = (string) str(config('paymob.payment_methods.card.iframe_link'))->beforeLast('/');
    $paymentLink = (string) str($paymentLink)->beforeLast('/');

    expect($endpoint === $paymentLink)->toBeTrue();
});

/**
 * @param PaymobOrder $order
 */
function makeOrder(): PaymobOrder
{
    $config = new PaymobConfig(config('paymob'));
    $order = new PaymobOrder($config);

    $billingData = [
        'first_name' => faker()->firstName,
        'last_name' => faker()->lastName,
        'email' => faker()->safeEmail(),
        'phone_number' => faker()->phoneNumber,
    ];

    return $order->deliveryNeeded(isDeliveryNeeded())
                 ->billingData(...$billingData)
                 ->amount(1000)
                 ->currency('EGP');
}

function fakeSuccessResponse()
{
    Http::fake([
        config('paymob.endpoints.authentication_request') => Http::response([
            'token' => getApiKey(),
        ], 200),
        config('paymob.endpoints.order_registration') => Http::response([
            'id' => 61747044,
            'created_at' => '2022-08-10T14:47:40.146080',
            'delivery_needed' => isDeliveryNeeded(),
            'merchant' => [
                'id' => 111111111,
                'created_at' => '2022-07-24T12:07:49.478575',
                'phones' => [
                    '01272496660',
                ],
                'company_emails' => [
                    'aa@aa.com',
                ],
                'company_name' => 'Company Name',
                'state' => '',
                'country' => 'EGY',
                'city' => 'cairo',
                'postal_code' => '',
                'street' => '',
            ],
            'collector' => null,
            'amount_cents' => 232323,
            'shipping_data' => null,
            'currency' => 'EGP',
            'is_payment_locked' => false,
            'is_return' => false,
            'is_cancel' => false,
            'is_returned' => false,
            'is_canceled' => false,
            'merchant_order_id' => 'a323223',
            'wallet_notification' => null,
            'paid_amount_cents' => 0,
            'notify_user_with_email' => false,
            'items' => [],
            'order_url' => 'https://accept.paymobsolutions.com/standalone?ref=i_djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
            'commission_fees' => 0,
            'delivery_fees_cents' => 0,
            'delivery_vat_cents' => 0,
            'payment_method' => 'tbc',
            'merchant_staff_tag' => null,
            'api_source' => 'OTHER',
            'data' => [],
            'token' => 'djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
            'url' => 'https://accept.paymobsolutions.com/standalone?ref=i_djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
        ], 200),
        config('paymob.endpoints.payment_keys') => Http::response(
            [
                'id' => 61747044,
                'created_at' => '2022-08-10T14:47:40.146080',
                'delivery_needed' => false,
                'merchant' => [
                    'id' => 111111111,
                    'created_at' => '2022-07-24T12:07:49.478575',
                    'phones' => [
                        '01272496660',
                    ],
                    'company_emails' => [
                        'aa@aa.com',
                    ],
                    'company_name' => 'Company Name',
                    'state' => '',
                    'country' => 'EGY',
                    'city' => 'cairo',
                    'postal_code' => '',
                    'street' => '',
                ],
                'collector' => null,
                'amount_cents' => 232323,
                'shipping_data' => null,
                'currency' => 'EGP',
                'is_payment_locked' => false,
                'is_return' => false,
                'is_cancel' => false,
                'is_returned' => false,
                'is_canceled' => false,
                'merchant_order_id' => 'a323223',
                'wallet_notification' => null,
                'paid_amount_cents' => 0,
                'notify_user_with_email' => false,
                'items' => [],
                'order_url' => 'https://accept.paymobsolutions.com/standalone?ref=i_djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
                'commission_fees' => 0,
                'delivery_fees_cents' => 0,
                'delivery_vat_cents' => 0,
                'payment_method' => 'tbc',
                'merchant_staff_tag' => null,
                'api_source' => 'OTHER',
                'data' => [],
                'token' => 'djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
                'url' => 'https://accept.paymobsolutions.com/standalone?ref=i_djRmL1JCRWxCOVlleEt1WjZzekxKQT09X0o1bmVXMEx6NXVQTTJaeCtzN1lYd2c9PQ==',
            ],
            200
        ),
    ]);
}

function getApiKey()
{
    return 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SndjbTltYVd4bFgzQnJJam8wTWpFMExDSmxlSEFpT2pFMU5qa3lOREk1TXpVc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndhR0Z6YUNJNkltRnlaMjl1TWlSaGNtZHZiakpwSkhZOU1Ua2tiVDAxTVRJc2REMHlMSEE5TWlSUFNFSmhaVzFhZUZSSFVrMVplbWhNSkZwalNVdENUMnN2TVZKa1kwbHdWVTV2Y2pOaVJrRWlmUS56cWJfRHJoMFB4UV84ZGd1d3A0MlFmNFBxSzA3T0xEQnZ5TmVaV3hZMFlyQVZMVFF3UFRrdUNFalk4S25XWjlpSk9obTVBbk41cXhMMXNCcGdBVDFRQQ==';
}

function isDeliveryNeeded()
{
    return false;
}
