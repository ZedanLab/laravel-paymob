<?php

use ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi;

it('sends an authentication request', function () {
    fakeSuccessResponse();

    $accessToken = (new PaymobPayoutApi())
        ->sendAuthenticationRequest()
        ->accessToken();

    expect(! is_null($accessToken))->toBeTrue();
});

it('sends a disburse request', function () {
    fakeSuccessResponse();

    $response = (new PaymobPayoutApi())
        ->sendAuthenticationRequest()
        ->sendDisburseRequest([
            'issuer' => 'vodafone',
            'amount' => '100.0',
            'msisdn' => '01023456789',
        ]);

    expect(is_object($response))->toBeTrue();
});
