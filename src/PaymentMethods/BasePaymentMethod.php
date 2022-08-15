<?php

namespace ZedanLab\Paymob\PaymentMethods;

use Illuminate\Http\RedirectResponse;
use ZedanLab\Paymob\Contracts\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\PaymobApi;

class BasePaymentMethod implements PaymobPaymentMethod
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobApi
     */
    protected $api;

    /**
     * @param \ZedanLab\Paymob\Services\PaymobApi $api
     */
    public function __construct(PaymobApi $api)
    {
        $this->api = $api;
    }

    /**
     * @return \ZedanLab\Paymob\Services\PaymobApi
     */
    public function api(): PaymobApi
    {
        return $this->api;
    }

    /**
     * Redirect to payment link.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function redirect(): ?RedirectResponse
    {
        return null;
    }

    /**
     * Build and retrieve payment link.
     *
     * @return string
     */
    public function paymentLink(): string
    {
        return '';
    }
}
