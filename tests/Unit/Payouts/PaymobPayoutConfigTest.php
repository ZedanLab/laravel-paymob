<?php

use Illuminate\Support\Collection;
use ZedanLab\Paymob\Services\Payouts\PaymobPayoutConfig;

it('throws an exception if the api_key is null', function () {
    config(['paymob.payouts.username' => null]);
    new PaymobPayoutConfig(config('paymob.payouts'));
})->expectErrorMessage("Please check the Paymob Payouts credentials.");

it('can return all config as a collection', function () {
    $config = (new PaymobPayoutConfig(config('paymob.payouts')))->collection();

    expect($config instanceof Collection)->toBeTrue();
});
