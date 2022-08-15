<?php

use Illuminate\Http\RedirectResponse;
use ZedanLab\Paymob\PaymentMethods\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\PaymobApi;

it('returns the payment link', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->sendPaymentKeysRequest('card');

    $paymentMethod = PaymobPaymentMethod::driver('card', $api);

    expect(is_string($paymentMethod->paymentLink()))->toBeTrue();
});

it('redirects to the payment link', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->sendPaymentKeysRequest('card');

    $redirect = PaymobPaymentMethod::driver('card', $api)->redirect();

    expect($redirect instanceof RedirectResponse)->toBeTrue();
});
