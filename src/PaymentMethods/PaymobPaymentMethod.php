<?php

namespace ZedanLab\Paymob\PaymentMethods;

use Exception;
use ZedanLab\Paymob\Services\Payments\PaymobApi;

class PaymobPaymentMethod
{
    /**
     * @param  string                                           $paymentMethod
     * @param  \ZedanLab\Paymob\Services\Payments\PaymobApi              $api
     * @return \ZedanLab\Paymob\PaymentMethods\BasePaymentMethod
     */
    public static function driver(string $paymentMethod, PaymobApi $api): BasePaymentMethod
    {
        $paymentMethod = str($paymentMethod)->camel()->ucfirst()->value();
        $paymentMethod = "\ZedanLab\Paymob\PaymentMethods\Drivers\\" . $paymentMethod . "PaymentMethod";

        throw_unless(class_exists($paymentMethod), new Exception("Invalid payment method, class '$paymentMethod' not found."));

        return new $paymentMethod($api);
    }
}
