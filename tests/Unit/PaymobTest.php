<?php

use Illuminate\Support\Facades\Http;

use function Pest\Faker\faker;

use ZedanLab\Paymob\Facades\Paymob;
use ZedanLab\Paymob\Services\PaymobConfig;
use ZedanLab\Paymob\Services\PaymobOrder;

it('register a new order', function () {
    fakeSuccessResponse();

    $paymentLink = Paymob::setOrder(makeOrder())
        ->payWith('card')
        ->paymentLink();

    $endpoint = (string) str(config('paymob.payment_methods.card.iframe_link'))->beforeLast('/');
    $paymentLink = (string) str($paymentLink)->beforeLast('/');

    expect($endpoint === $paymentLink)->toBeTrue();
});

/**
 * @return PaymobOrder
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

    $order = $order->deliveryNeeded(isDeliveryNeeded())
                   ->billingData(...$billingData)
                   ->payable(['id' => 50, 'type' => 'App\\Models\\Product'])
                   ->payer(['id' => 5, 'type' => 'App\\Models\\User'])
                   //    ->referenceId('2323 23232a3')
                   ->amount(33000)
                   ->currency('EGP');

    return $order;
}

function fakeSuccessResponse()
{
    Http::fake([
        config('paymob.endpoints.pay') => Http::response(
            json_decode(stripslashes('{"id":52232931,"pending":true,"amount_cents":3300000,"success":false,"is_auth":false,"is_capture":false,"is_standalone_payment":true,"is_voided":false,"is_refunded":false,"is_3d_secure":false,"integration_id":2438611,"profile_id":245593,"has_parent_transaction":false,"order":{"id":62665213,"created_at":"2022-08-15T18:37:26.560049","delivery_needed":false,"merchant":{"id":245593,"created_at":"2022-07-24T12:07:49.478575","state":"","country":"EGY","city":"cairo","postal_code":"","street":""},"collector":null,"amount_cents":3300000,"shipping_data":{"id":33807819,"first_name":"Elza","last_name":"Kohler","street":"N/A","building":"N/A","floor":"N/A","apartment":"N/A","city":"N/A","state":"N/A","country":"N/A","email":"maybell.hills@example.com","phone_number":"+1.585.836.0942","postal_code":"N/A","extra_description":"","shipping_method":"UNK","order_id":62665213,"order":62665213},"currency":"EGP","is_payment_locked":false,"is_return":false,"is_cancel":false,"is_returned":false,"is_canceled":false,"merchant_order_id":null,"wallet_notification":null,"paid_amount_cents":0,"notify_user_with_email":false,"items":[],"order_url":"https://accept.paymobsolutions.com/standalone?ref=i_VU50cVZKTVFzbHM3T0FxNENYcVdCdz09X3ZPMGs0a01jRnRCQlFBb2pDby80Z2c9PQ==","commission_fees":0,"delivery_fees_cents":0,"delivery_vat_cents":0,"payment_method":"tbc","merchant_staff_tag":null,"api_source":"OTHER","data":{"payer":{"id":5,"type":"App\\Models\\User"},"payable":{"id":50,"type":"App\\Models\\Product"}}},"created_at":"2022-08-15T18:37:48.263068","transaction_processed_callback_responses":[],"currency":"EGP","source_data":{"owner_name":null,"pan":"01010101010","phone_number":"01010101010","type":"wallet","sub_type":"wallet"},"api_source":"OTHER","terminal_id":null,"merchant_commission":0,"installment":null,"is_void":false,"is_refund":false,"data":{"uig_txn_id":"4351324134","method":0,"amount":3300000,"upg_qrcode_ref":"4351324134","created_at":"2022-08-15T16:37:48.889763","mpg_txn_id":"213412341","order_info":"maybell.hills@example.com","currency":"EGP","upg_txn_id":null,"wallet_issuer":"VODAFONE","klass":"WalletPayment","token":"","message":"Transaction Created Successfully","txn_response_code":"200","mer_txn_ref":"2438611_7e5e7738d3ea531a8d4147b8e3084f8a","redirect_url":"https://accept.paymobsolutions.com/api/acceptance/wallet_other_test/wallet_template?token=ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklsUnlZVzV6WVdOMGFXOXVJaXdpYzNWaVgzUjVjR1VpT2lKM1lXeHNaWFFpTENKMGNtRnVjMkZqZEdsdmJsOXdheUk2TlRJeU16STVNekY5LklQRVhnZUx1WGdzdTdFNkYtVWRXbVJZcnFRNDAyc1JSMWswTVdTT2ZqM01FOVU5MVZqaThacHo0T0dWN0xBMW1nX2hlYmJIVG4yZG12S2RqR2ZxbF9R","wallet_msisdn":"01010101010","gateway_integration_pk":2438611},"is_hidden":false,"payment_key_claims":{"user_id":438020,"integration_id":2438611,"pmk_ip":"156.204.118.124","billing_data":{"building":"N/A","phone_number":"+1.585.836.0942","extra_description":"NA","first_name":"Elza","apartment":"N/A","postal_code":"N/A","city":"N/A","street":"N/A","last_name":"Kohler","floor":"N/A","country":"N/A","state":"N/A","email":"maybell.hills@example.com"},"lock_order_when_paid":false,"order_id":62665213,"currency":"EGP","exp":1660585047,"amount_cents":3300000},"error_occured":false,"is_live":false,"other_endpoint_reference":"4351324134","refunded_amount_cents":0,"source_id":-1,"is_captured":false,"captured_amount":0,"merchant_staff_tag":null,"updated_at":"2022-08-15T18:37:49.184461","owner":438020,"parent_transaction":null,"redirect_url":"https://accept.paymobsolutions.com/api/acceptance/wallet_other_test/wallet_template?token=ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklsUnlZVzV6WVdOMGFXOXVJaXdpYzNWaVgzUjVjR1VpT2lKM1lXeHNaWFFpTENKMGNtRnVjMkZqZEdsdmJsOXdheUk2TlRJeU16STVNekY5LklQRVhnZUx1WGdzdTdFNkYtVWRXbVJZcnFRNDAyc1JSMWswTVdTT2ZqM01FOVU5MVZqaThacHo0T0dWN0xBMW1nX2hlYmJIVG4yZG12S2RqR2ZxbF9R","iframe_redirection_url":"https://accept.paymobsolutions.com/api/acceptance/wallet_other_test/wallet_template?token=ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklsUnlZVzV6WVdOMGFXOXVJaXdpYzNWaVgzUjVjR1VpT2lKM1lXeHNaWFFpTENKMGNtRnVjMkZqZEdsdmJsOXdheUk2TlRJeU16STVNekY5LklQRVhnZUx1WGdzdTdFNkYtVWRXbVJZcnFRNDAyc1JSMWswTVdTT2ZqM01FOVU5MVZqaThacHo0T0dWN0xBMW1nX2hlYmJIVG4yZG12S2RqR2ZxbF9R"}'), true),
            200
        ),
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
