<?php

use Illuminate\Support\Collection;
use ZedanLab\Paymob\Services\Payments\PaymobConfig;

it('throws an exception if the api_key is null', function () {
    config(['paymob.payments.api_key' => null]);
    new PaymobConfig(config('paymob.payments'));
})->expectErrorMessage("PAYMOB_API_KEY:api_key is not defined.");

it('can return all config as a collection', function () {
    $config = (new PaymobConfig(config('paymob.payments')))->collection();

    expect($config instanceof Collection)->toBeTrue();
});
