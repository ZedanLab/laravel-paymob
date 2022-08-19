<?php

use ZedanLab\Paymob\Contracts\PaymobPaymentMethod as ContractsPaymobPaymentMethod;
use ZedanLab\Paymob\PaymentMethods\BasePaymentMethod;
use ZedanLab\Paymob\PaymentMethods\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\Payments\PaymobApi;

it('returns the given driver instance', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi());

    $driver = PaymobPaymentMethod::driver('card', $api);

    expect($driver instanceof BasePaymentMethod)->toBeTrue();
    expect($driver instanceof ContractsPaymobPaymentMethod)->toBeTrue();
});

it('throws an exception if the given driver not found', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi());

    PaymobPaymentMethod::driver('invalid-driver', $api);
})->expectExceptionMessage("Invalid payment method, class '\ZedanLab\Paymob\PaymentMethods\Drivers\InvalidDriverPaymentMethod' not found.");
