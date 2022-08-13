<?php

namespace ZedanLab\Paymob\Services;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class PaymobApi
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobOrder|null
     */
    protected $order;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * Create a new paymob instance.
     *
     * @param  \ZedanLab\Paymob\Services\PaymobOrder $order (optional)
     * @return void
     */
    public function __construct(?PaymobOrder $order = null)
    {
        $this->order = $order;
    }

    /**
     * Return authentication token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Send an authentication request to retrieve access token.
     *
     * @see https://docs.paymob.com/docs/accept-standard-redirect#1-authentication-request
     *
     * @return self
     */
    public function sendAuthenticationRequest(): self
    {
        $response = Http::post(
            $this->order->config()->get('endpoints.authentication_request'),
            $this->buildAuthenticationRequestData()
        )->onError(function (Response $response) {
            throw $response->toException();
        });

        // @phpstan-ignore-next-line
        $this->accessToken = $response->object()->token;

        return $this;
    }

    /**
     * Send an order registration request paymob.
     *
     * @see https://docs.paymob.com/docs/accept-standard-redirect#2-order-registration-api
     *
     * @return self
     */
    public function sendOrderRegistrationRequest(): self
    {
        $response = Http::post(
            $this->order->config()->get('endpoints.order_registration'),
            $this->buildOrderRegistrationRequestData()
        )->onError(function (Response $response) {
            throw $response->toException();
        });

        $this->order->set('paymob_order', $response->object());

        return $this;
    }

    /**
     * Send a payment keys request.
     *
     * @see https://docs.paymob.com/docs/accept-standard-redirect#3-payment-key-request
     *
     * @return self
     */
    public function sendPaymentKeysRequest(string $paymentMethod): self
    {
        $response = Http::post(
            $this->order->config()->get('endpoints.payment_keys'),
            $this->buildPaymentKeysRequestData($paymentMethod)
        )->onError(function (Response $response) {
            throw $response->toException();
        });

        $this->order->set('paymob_payment_keys', $response->object());

        return $this;
    }

    protected function buildAuthenticationRequestData(): array
    {
        return [
            'api_key' => $this->order->config()->get('api_key'),
        ];
    }

    /**
     * @param string $paymentMethod
     */
    protected function buildPaymentKeysRequestData(string $paymentMethod): array
    {
        $required = [
            'auth_token',
            'amount_cents',
            'expiration',
            'order_id',
            'billing_data',
            'currency',
            'integration_id',
        ];

        $optional = [
            'lock_order_when_paid',
        ];

        $defaults = [
            'expiration' => 3600,
            'currency' => $this->order->config()->get('currency'),
            'auth_token' => $this->accessToken(),
            'order_id' => $this->order->get('paymob_order')->id,
            'integration_id' => $this->order->config()->get("payment_methods.{$paymentMethod}.integration_id"),
        ];

        $data = [...$defaults, ...Arr::only($this->order->all(), $required + $optional)];

        throw_if(! Arr::has($data, $required), new Exception("Paymob Order: please check and set the following attributes values: " . implode(', ', $required)));

        return $data;
    }

    /**
     *
     */
    protected function buildOrderRegistrationRequestData(): array
    {
        $required = [
            'auth_token',
            'delivery_needed',
            'amount_cents',
        ];

        $optional = [
            'merchant_order_id',
            'items',
            'shipping_data',
            'shipping_details',
            'currency',
        ];

        $defaults = [
            'delivery_needed' => false,
            'currency' => $this->order->config()->get('currency'),
            'auth_token' => $this->accessToken(),
        ];

        $data = [...$defaults, ...Arr::only($this->order->all(), $required + $optional)];

        throw_if(! Arr::has($data, $required), new Exception("Paymob Order: please check and set the following attributes values" . implode(', ', $required)));

        return $data;
    }

    /**
     * Set paymob order.
     *
     * @param  \ZedanLab\Paymob\Services\PaymobOrder $order
     * @return self
     */
    public function setOrder(PaymobOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Return current order.
     *
     * @return \ZedanLab\Paymob\Services\PaymobOrder|null
     */
    public function order(): ?PaymobOrder
    {
        return $this->order;
    }
}
