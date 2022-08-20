<?php

use ZedanLab\Paymob\Models\PaymobPayout;
use ZedanLab\Paymob\PayoutIssuers\PaymobPayoutIssuer;

it('can sets an amount', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet');
    $payoutIssuer->set('amount', 300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();
});

it('can sets a msisdn', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet')
        ->setMsisdn('01092737975');

    expect($payoutIssuer->get('msisdn') == '01092737975')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet');
    $payoutIssuer->setMsisdn('01092737975');

    expect($payoutIssuer->get('msisdn') == '01092737975')->toBeTrue();
});

it('throws exception if the given amount is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet');

    $payoutIssuer->set('amount', 'invalid-amount');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid amount, 'invalid-amount' given instead of float.");

it('throws exception if the given msisdn is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(100.00);

    $payoutIssuer->set('msisdn', 'invalid-msisdn');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid msisdn, 'invalid-msisdn' given. ex. 01092737975");

it('validates the given data', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(100.00)
        ->setMsisdn('01092737975');

    expect($payoutIssuer->validate())->toBeTrue();
});

it('builds the disburse request data', function () {
    fakeSuccessResponse();

    $disburseRequestData = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(100.00)
        ->setMsisdn('01092737975')
        ->buildDisburseRequestData();

    expect($disburseRequestData === [
        'issuer' => 'bank_wallet',
        'amount' => 100.00,
        'msisdn' => '01092737975',
    ])->toBeTrue();
});

it('sends the disburse request', function () {
    fakeSuccessResponse();

    $response = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(100.00)
        ->setMsisdn('01092737975')
        ->disburse()
        ->getDisburseResponse();

    expect(is_array($response))->toBeTrue();
});

it('returns the disburse response as PaymobPayout model', function () {
    fakeSuccessResponse();

    $paymobPayout = PaymobPayoutIssuer::driver('bank_wallet')
        ->setAmount(100.00)
        ->setMsisdn('01092737975')
        ->disburse()
        ->asPaymobPayout(['receiver_id' => 12345]);

    expect($paymobPayout instanceof PaymobPayout)->toBeTrue();
    expect($paymobPayout->receiver_id === 12345)->toBeTrue();
});
