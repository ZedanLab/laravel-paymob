<?php

namespace ZedanLab\Paymob\PaymentMethods\Drivers;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use ZedanLab\Paymob\PaymentMethods\BasePaymentMethod;

class MobileWalletPaymentMethod extends BasePaymentMethod
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
        if (is_null(optional($this->api->order()->get('paymob_payment_keys'))->token)) {
            throw new Exception('Payment keys request should called first.');
        }

        $this->sendPayRequest('0101010101');

        return $this->api->order()->get('paymob_pay')->redirect_url;
    }

    /**
     * Send a pay request.
     *
     * @see https://docs.paymob.com/docs/mobile-wallets#pay-request
     *
     * @param  string $walletIdentifier
     * @return void
     */
    protected function sendPayRequest(string $walletIdentifier): void
    {
        $response = Http::post(
            $this->api->order()->config()->get('endpoints.pay'),
            [
                'source' => [
                    'identifier' => $walletIdentifier,
                    'subtype' => 'WALLET',
                ],
                'payment_token' => optional($this->api->order()->get('paymob_payment_keys'))->token,
            ]
        )->onError(function (Response $response) {
            throw $response->toException();
        });

        $this->api->order()->set('paymob_pay', $response->object());
    }
}
