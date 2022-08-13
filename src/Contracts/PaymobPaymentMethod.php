<?php

namespace ZedanLab\Paymob\Contracts;

use Illuminate\Http\RedirectResponse;

interface PaymobPaymentMethod
{
    /**
     * Redirect to payment link.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function redirect(): ?RedirectResponse;

    /**
     * Build and retrieve payment link.
     *
     * @return string
     */
    public function paymentLink(): string;
}
