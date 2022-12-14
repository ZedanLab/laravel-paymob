<?php

use Illuminate\Http\RedirectResponse;
use ZedanLab\Paymob\PaymentMethods\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\Payments\PaymobApi;

it('returns the payment link', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->setPaymentMethodData(['wallet_identifier' => '01010101010'])
        ->sendPaymentKeysRequest('mobile_wallet');

    $paymentMethod = PaymobPaymentMethod::driver('mobile_wallet', $api);

    expect(is_string($paymentMethod->paymentLink()))->toBeTrue();
});

it('redirects to the payment link', function () {
    fakeSuccessResponse();

    $api = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->sendOrderRegistrationRequest()
        ->setPaymentMethodData(['wallet_identifier' => '01010101010'])
        ->sendPaymentKeysRequest('mobile_wallet');

    $redirect = PaymobPaymentMethod::driver('mobile_wallet', $api)->redirect();

    expect($redirect instanceof RedirectResponse)->toBeTrue();
});
