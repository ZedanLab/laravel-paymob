<?php

namespace ZedanLab\Paymob\PaymentMethod\Drivers;

use Exception;
use Illuminate\Http\RedirectResponse;
use ZedanLab\Paymob\PaymentMethod\BasePaymentMethod;

class CardPaymentMethod extends BasePaymentMethod
{
    /**
     * Redirect to payment link.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function redirect(): ?RedirectResponse
    {
        return redirect()->to($this->paymentLink());
    }

    /**
     * Build and retrieve payment link.
     *
     * @return string
     */
    public function paymentLink(): string
    {
        if (is_null($token = optional($this->api->order()->get('paymob_payment_keys'))->token)) {
            throw new Exception("Payment keys request should called first.");
        }

        $iFrameId = $this->api->order()->config()->get('payment_methods.card.iframe_id');
        $iFrameLink = $this->api->order()->config()->get('payment_methods.card.iframe_link');

        return strtr($iFrameLink, [
            '{:iframe_id}' => $iFrameId,
            '{:payment_key_token}' => $token,
        ]);
    }
}
