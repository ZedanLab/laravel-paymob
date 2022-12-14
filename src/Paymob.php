<?php

namespace ZedanLab\Paymob;

use Closure;
use Exception;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use ZedanLab\Paymob\Contracts\PaymobPaymentMethod as PaymentMethodContract;
use ZedanLab\Paymob\PaymentMethods\PaymobPaymentMethod;
use ZedanLab\Paymob\Services\Payments\PaymobApi;
use ZedanLab\Paymob\Services\Payments\PaymobConfig;
use ZedanLab\Paymob\Services\Payments\PaymobOrder;

class Paymob
{
    /**
     * @var \ZedanLab\Paymob\Services\Payments\PaymobConfig
     */
    protected $config;

    /**
     * @var \ZedanLab\Paymob\Services\Payments\PaymobApi
     */
    protected $api;

    /**
     * @var \ZedanLab\Paymob\Services\Payments\PaymobOrder
     */
    protected $order;

    /**
     * Create a new paymob instance.
     *
     * @param  \ZedanLab\Paymob\Services\Payments\PaymobConfig|array $config
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
     * Proceed the payment with the given driver.
     *
     * @param  string                                           $method
     * @param  array                                            $paymentMethodData
     * @return \ZedanLab\Paymob\Contracts\PaymobPaymentMethod
     */
    public function payWith(string $method, array $paymentMethodData = []): PaymentMethodContract
    {
        throw_if(is_null($this->order()), new Exception("You should set the order object first, please use 'makeOrder()' or 'setOrder()' methods."));

        $this->api->setOrder($this->order());

        $payment = PaymobPaymentMethod::driver($method, $this->api);

        $payment->api()
                ->sendAuthenticationRequest()
                ->sendOrderRegistrationRequest()
                ->setPaymentMethodData($paymentMethodData)
                ->sendPaymentKeysRequest($method);

        return $payment;
    }

    /**
     * Return current order.
     *
     * @return \ZedanLab\Paymob\Services\Payments\PaymobOrder|null
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
     * @param  \ZedanLab\Paymob\Services\Payments\PaymobOrder $order
     * @return self
     */
    public function setOrder(PaymobOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Binds the Paymob callback routes into the controller.
     *
     * @param  callable|null $callback
     * @param  array         $options
     * @return void
     */
    public static function routes($callback = null, array $options = [])
    {
        if (app() instanceof CachesRoutes && app()->routesAreCached()) {
            return;
        }

        $callback = $callback ?: function (RouteRegistrar $router) {
            $router->all();
        };

        $defaultOptions = [
            'as' => 'paymob.',
            'middleware' => ['web'],
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}
