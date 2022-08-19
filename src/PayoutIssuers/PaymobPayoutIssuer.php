<?php

namespace ZedanLab\Paymob\PayoutIssuers;

use Exception;
use ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi;

class PaymobPayoutIssuer
{
    /**
     * @param  string                                            $payoutIssuer
     * @param  \ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi $api
     * @return \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer
     */
    public static function driver(string $payoutIssuer, PaymobPayoutApi $api = null): BasePayoutIssuer
    {
        $payoutIssuer = str($payoutIssuer)->camel()->ucfirst()->value();
        $payoutIssuer = "\ZedanLab\Paymob\PayoutIssuers\Drivers\\" . $payoutIssuer . "PayoutIssuer";

        throw_unless(class_exists($payoutIssuer), new Exception("Invalid payout issuer, class '$payoutIssuer' not found."));

        if (is_null($api)) {
            $api = new PaymobPayoutApi();
        }

        return new $payoutIssuer($api);
    }
}
