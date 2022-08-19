<?php

use ZedanLab\Paymob\Contracts\PaymobPayoutIssuer as ContractsPaymobPayoutIssuer;
use ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer;
use ZedanLab\Paymob\PayoutIssuers\PaymobPayoutIssuer;
use ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi;

it('returns the given driver instance', function () {
    fakeSuccessResponse();

    $driver = PaymobPayoutIssuer::driver('vodafone');

    expect($driver instanceof BasePayoutIssuer)->toBeTrue();
    expect($driver instanceof ContractsPaymobPayoutIssuer)->toBeTrue();
});

it('throws an exception if the given driver not found', function () {
    fakeSuccessResponse();

    $api = (new PaymobPayoutApi());

    PaymobPayoutIssuer::driver('invalid-driver', $api);
})->expectExceptionMessage("Invalid payout issuer, class '\ZedanLab\Paymob\PayoutIssuers\Drivers\InvalidDriverPayoutIssuer' not found.");
