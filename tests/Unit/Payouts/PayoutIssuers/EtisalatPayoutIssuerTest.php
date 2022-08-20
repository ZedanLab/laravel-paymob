<?php

use ZedanLab\Paymob\Models\PaymobPayout;
use ZedanLab\Paymob\PayoutIssuers\PaymobPayoutIssuer;

it('can sets an amount', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat');
    $payoutIssuer->set('amount', 300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();
});

it('can sets a msisdn', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat')
        ->setMsisdn('01123456789');

    expect($payoutIssuer->get('msisdn') == '01123456789')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat');
    $payoutIssuer->setMsisdn('01123456789');

    expect($payoutIssuer->get('msisdn') == '01123456789')->toBeTrue();
});

it('throws exception if the given amount is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat');

    $payoutIssuer->set('amount', 'invalid-amount');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid amount, 'invalid-amount' given instead of float.");

it('throws exception if the given msisdn is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(100.00);

    $payoutIssuer->set('msisdn', 'invalid-msisdn');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid msisdn, 'invalid-msisdn' given. ex. 01123456789");

it('validates the given amount', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(100.00)
        ->setMsisdn('01123456789');

    expect($payoutIssuer->validate())->toBeTrue();
});

it('builds the disburse request data', function () {
    fakeSuccessResponse();

    $disburseRequestData = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(100.00)
        ->setMsisdn('01123456789')
        ->buildDisburseRequestData();

    expect($disburseRequestData === [
        'issuer' => 'etisalat',
        'amount' => 100.00,
        'msisdn' => '01123456789',
    ])->toBeTrue();
});

it('sends the disburse request', function () {
    fakeSuccessResponse();

    $response = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(100.00)
        ->setMsisdn('01123456789')
        ->disburse()
        ->getDisburseResponse();

    expect($response instanceof stdClass)->toBeTrue();
});

it('returns the disburse response as PaymobPayout model', function () {
    fakeSuccessResponse();

    $paymobPayout = PaymobPayoutIssuer::driver('etisalat')
        ->setAmount(100.00)
        ->setMsisdn('01123456789')
        ->disburse()
        ->asPaymobPayout(['receiver_id' => 12345]);

    expect($paymobPayout instanceof PaymobPayout)->toBeTrue();
    expect($paymobPayout->receiver_id === 12345)->toBeTrue();
});
