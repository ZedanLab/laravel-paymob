<?php

use ZedanLab\Paymob\Services\Payments\PaymobApi;

it('sends an authentication request', function () {
    fakeSuccessResponse();

    $accessToken = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->accessToken();

    expect(! is_null($accessToken))->toBeTrue();
});

it('sends an order registration request', function () {
    fakeSuccessResponse();

    $paymobOrder = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->order()
        ->get('paymob_order');

    expect(! is_null($paymobOrder))->toBeTrue();
});

it('sends an payment keys request', function () {
    fakeSuccessResponse();

    $paymentKey = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->sendPaymentKeysRequest('card')
        ->order()
        ->get('paymob_payment_keys');

    expect(! is_null($paymentKey))->toBeTrue();
});
