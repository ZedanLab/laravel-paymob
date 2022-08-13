<?php

namespace ZedanLab\Paymob\PaymentMethod;

use Exception;
use ZedanLab\Paymob\Services\PaymobApi;

class PaymobPaymentMethod
{
    /**
     * @param  string                                           $paymentMethod
     * @param  \ZedanLab\Paymob\Services\PaymobApi              $api
     * @return \ZedanLab\Paymob\PaymentMethod\BasePaymentMethod
     */
    public static function driver(string $paymentMethod, PaymobApi $api): BasePaymentMethod
    {
        $paymentMethod = str($paymentMethod)->camel()->ucfirst()->value();
        $paymentMethod = "\ZedanLab\Paymob\PaymentMethod\Drivers\\" . $paymentMethod . "PaymentMethod";

        throw_unless(class_exists($paymentMethod), new Exception("Invalid payment method, class '$paymentMethod' not found."));

        return new $paymentMethod($api);
    }
}
