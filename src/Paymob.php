<?php

namespace ZedanLab\Paymob;

use Closure;
use Exception;
use ZedanLab\Paymob\Contracts\PaymobPaymentMethod as PaymentMethodContract;
use ZedanLab\Paymob\PaymentMethod\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\PaymobApi;
use ZedanLab\Paymob\Services\PaymobConfig;
use ZedanLab\Paymob\Services\PaymobOrder;

class Paymob
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobConfig
     */
    protected $config;

    /**
     * @var \ZedanLab\Paymob\Services\PaymobApi
     */
    protected $api;

    /**
     * @var \ZedanLab\Paymob\Services\PaymobOrder
     */
    protected $order;

    /**
     * Create a new paymob instance.
     *
     * @param  \ZedanLab\Paymob\Services\PaymobConfig|array $config
     * @return void
     */
    public function __construct(PaymobConfig | array $config)
    {
        if (is_array($config)) {
            $config = new PaymobConfig($config);
        }

        $this->config = $config;

        $this->api = new PaymobApi($this->order());
    }

    /**
     * @return \ZedanLab\Paymob\Contracts\PaymobPaymentMethod
     */
    public function payWith(string $method): PaymentMethodContract
    {
        throw_if(is_null($this->order()), new Exception("You should set the order object first, please use 'makeOrder()' or 'setOrder()' methods."));

        $this->api->setOrder($this->order());

        $payment = PaymobPaymentMethod::driver($method, $this->api);

        $payment->api()
                ->sendAuthenticationRequest()
                ->sendOrderRegistrationRequest()
                ->sendPaymentKeysRequest($method);

        return $payment;
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

    /**
     * Making a new paymob order.
     *
     * @param  \Closure $callback
     * @return self
     */
    public function makeOrder(Closure $callback): self
    {
        $order = new PaymobOrder($this->config);

        $callback($order);

        $this->order = $order;

        return $this;
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
}
