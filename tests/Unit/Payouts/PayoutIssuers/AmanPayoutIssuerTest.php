<?php

use ZedanLab\Paymob\Models\PaymobPayout;
use ZedanLab\Paymob\PayoutIssuers\PaymobPayoutIssuer;

it('can sets an amount', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');
    $payoutIssuer->set('amount', 300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();
});

it('can sets a msisdn', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setMsisdn('01092737975');

    expect($payoutIssuer->get('msisdn') == '01092737975')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');
    $payoutIssuer->setMsisdn('01092737975');

    expect($payoutIssuer->get('msisdn') == '01092737975')->toBeTrue();
});

it('can sets a first name', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setFirstName('Mohamed');

    expect($payoutIssuer->get('first_name') == 'Mohamed')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');
    $payoutIssuer->setFirstName('Mohamed');

    expect($payoutIssuer->get('first_name') == 'Mohamed')->toBeTrue();
});

it('can sets a last name', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setLastName('Zedan');

    expect($payoutIssuer->get('last_name') == 'Zedan')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');
    $payoutIssuer->setLastName('Zedan');

    expect($payoutIssuer->get('last_name') == 'Zedan')->toBeTrue();
});

it('can sets a email', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setEmail('Zedan');

    expect($payoutIssuer->get('email') == 'Zedan')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');
    $payoutIssuer->setEmail('Zedan');

    expect($payoutIssuer->get('email') == 'Zedan')->toBeTrue();
});

it('throws exception if the given amount is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman');

    $payoutIssuer->set('amount', 'invalid-amount');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid amount, 'invalid-amount' given instead of float.");

it('throws exception if the given msisdn is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00);

    $payoutIssuer->set('msisdn', 'invalid-msisdn');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid msisdn, 'invalid-msisdn' given. ex. 01092737975");

it('throws exception if the given first name is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setMsisdn('01092737975');

    $payoutIssuer->set('first_name', 12345);

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid first_name, '12345' given.");

it('throws exception if the given last name is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setMsisdn('01092737975');

    $payoutIssuer->set('last_name', 12345);

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid last_name, '12345' given.");

it('throws exception if the given email is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setLastName('Zedan')
        ->setMsisdn('01092737975');

    $payoutIssuer->set('email', 'invalid-email');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid email, 'invalid-email' given.");

it('validates the given data', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setLastName('Zedan')
        ->setEmail('mo@zedan.me')
        ->setMsisdn('01092737975');

    expect($payoutIssuer->validate())->toBeTrue();
});

it('builds the disburse request data', function () {
    fakeSuccessResponse();

    $disburseRequestData = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setLastName('Zedan')
        ->setEmail('mo@zedan.me')
        ->setMsisdn('01092737975')
        ->buildDisburseRequestData();

    expect($disburseRequestData === [
        'issuer' => 'aman',
        'amount' => 100.00,
        'msisdn' => '01092737975',
        'first_name' => 'Mohamed',
        'last_name' => 'Zedan',
        'email' => 'mo@zedan.me',
    ])->toBeTrue();
});

it('sends the disburse request', function () {
    fakeSuccessResponse();

    $response = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setLastName('Zedan')
        ->setEmail('mo@zedan.me')
        ->setMsisdn('01092737975')
        ->disburse()
        ->getDisburseResponse();

    expect(is_array($response))->toBeTrue();
});

it('returns the disburse response as PaymobPayout model', function () {
    fakeSuccessResponse();

    $paymobPayout = PaymobPayoutIssuer::driver('aman')
        ->setAmount(100.00)
        ->setFirstName('Mohamed')
        ->setLastName('Zedan')
        ->setEmail('mo@zedan.me')
        ->setMsisdn('01092737975')
        ->disburse()
        ->asPaymobPayout(['receiver_id' => 12345]);

    expect($paymobPayout instanceof PaymobPayout)->toBeTrue();
    expect($paymobPayout->receiver_id === 12345)->toBeTrue();
});
