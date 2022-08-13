<?php

use ZedanLab\Paymob\Services\PaymobApi;

it('sends an authentication request', function () {
    fakeSuccessResponse();

    $accessToken = (new PaymobApi())
        ->setOrder(makeOrder())
        ->sendAuthenticationRequest()
        ->accessToken();

    expect($accessToken === getApiKey())->toBeTrue();
});
