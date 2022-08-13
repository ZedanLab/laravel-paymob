<?php

use Illuminate\Support\Collection;
use ZedanLab\Paymob\Services\PaymobConfig;

it('throws an exception if the api_key is null', function () {
    config(['paymob.api_key' => null]);
    new PaymobConfig(config('paymob'));
})->expectErrorMessage("PAYMOB_API_KEY:api_key is not defined.");

it('can return all config as a collection', function () {
    $config = (new PaymobConfig(config('paymob')))->collection();

    expect($config instanceof Collection)->toBeTrue();
});
