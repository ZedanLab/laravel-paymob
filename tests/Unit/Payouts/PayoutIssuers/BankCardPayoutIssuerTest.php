<?php

use ZedanLab\Paymob\Models\PaymobPayout;
use ZedanLab\Paymob\PayoutIssuers\PaymobPayoutIssuer;

it('can sets an amount', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');
    $payoutIssuer->set('amount', 300.50);

    expect($payoutIssuer->get('amount') == 300.50)->toBeTrue();
});

it('can sets a full_name', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setFullName('Mohamed A. Zedan');

    expect($payoutIssuer->get('full_name') == 'Mohamed A. Zedan')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');
    $payoutIssuer->setFullName('Mohamed A. Zedan');

    expect($payoutIssuer->get('full_name') == 'Mohamed A. Zedan')->toBeTrue();
});

it('can sets a bank_card_number', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setBankCardNumber('EG829299835722904511873050307');

    expect($payoutIssuer->get('bank_card_number') == 'EG829299835722904511873050307')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');
    $payoutIssuer->setBankCardNumber('1111-2222-3333-4444');

    expect($payoutIssuer->get('bank_card_number') == '1111-2222-3333-4444')->toBeTrue();
});

it('can sets a bank_code', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setBankCode('QNB');

    expect($payoutIssuer->get('bank_code') == 'QNB')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');
    $payoutIssuer->setBankCode('CIB');

    expect($payoutIssuer->get('bank_code') == 'CIB')->toBeTrue();
});

it('can sets a bank_transaction_type', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setBankTransactionType('credit_card');

    expect($payoutIssuer->get('bank_transaction_type') == 'credit_card')->toBeTrue();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');
    $payoutIssuer->setBankTransactionType('cash_transfer');

    expect($payoutIssuer->get('bank_transaction_type') == 'cash_transfer')->toBeTrue();
});

it('throws exception if the given amount is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card');

    $payoutIssuer->set('amount', 'invalid-amount');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid amount, 'invalid-amount' given instead of float.");

it('throws exception if the given full name is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.0);

    $payoutIssuer->set('full_name', 12345);

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid full_name, '12345' given.");

it('throws exception if the given bank_card_number is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan');

    $payoutIssuer->set('bank_card_number', 12345);

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid bank_card_number, '12345' given. ex. 1111-2222-3333-4444, EG829299835722904511873050307");

it('throws exception if the given bank code is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444');

    $payoutIssuer->set('bank_code', 12345);

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid bank_code, '12345' given.");

it('throws exception if the given bank_transaction_type is invalid', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444')
        ->setBankCode('CIB');

    $payoutIssuer->set('bank_transaction_type', 'invalid-bank-transaction-type');

    $payoutIssuer->validate();
})->expectErrorMessage("Invalid bank_transaction_type, 'invalid-bank-transaction-type' given. ex. salary, credit_card, prepaid_card, cash_transfer");

it('validates the given data', function () {
    fakeSuccessResponse();

    $payoutIssuer = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444')
        ->setBankCode('CIB')
        ->setBankTransactionType('cash_transfer');

    expect($payoutIssuer->validate())->toBeTrue();
});

it('builds the disburse request data', function () {
    fakeSuccessResponse();

    $disburseRequestData = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444')
        ->setBankCode('CIB')
        ->setBankTransactionType('cash_transfer')
        ->buildDisburseRequestData();

    expect($disburseRequestData === [
        'issuer'                => 'bank_card',
        'amount'                => 100.00,
        'full_name'             => 'Mohamed A. Zedan',
        'bank_card_number'      => '1111-2222-3333-4444',
        'bank_code'             => 'CIB',
        'bank_transaction_type' => 'cash_transfer',
    ])->toBeTrue();
});

it('sends the disburse request', function () {
    fakeSuccessResponse();

    $response = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444')
        ->setBankCode('CIB')
        ->setBankTransactionType('cash_transfer')
        ->disburse()
        ->getDisburseResponse();

    expect(is_array($response))->toBeTrue();
});

it('returns the disburse response as PaymobPayout model', function () {
    fakeSuccessResponse();

    $paymobPayout = PaymobPayoutIssuer::driver('bank_card')
        ->setAmount(100.00)
        ->setFullName('Mohamed A. Zedan')
        ->setBankCardNumber('1111-2222-3333-4444')
        ->setBankCode('CIB')
        ->setBankTransactionType('cash_transfer')
        ->disburse()
        ->asPaymobPayout(['receiver_id' => 12345]);

    expect($paymobPayout instanceof PaymobPayout)->toBeTrue();
    expect($paymobPayout->receiver_id === 12345)->toBeTrue();
});
