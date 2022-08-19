<?php

namespace ZedanLab\Paymob;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for paymob.
     *
     * @return void
     */
    public function all()
    {
        $this->processedCallback();
        $this->responseCallback();
    }

    /**
     * Register the routes needed for paymob processed callback.
     *
     * @return void
     */
    public function processedCallback()
    {
        $this->router->group(['as' => config('paymob.payments.callbacks.transaction_processed_route.as')], function (Router $router) {
            $router->post(
                config('paymob.payments.callbacks.transaction_processed_route.uri'),
                config('paymob.payments.callbacks.transaction_processed_route.action')
            );
        });
    }

    /**
     * Register the routes needed for paymob response callback.
     *
     * @return void
     */
    public function responseCallback()
    {
        $this->router->group(['as' => config('paymob.payments.callbacks.transaction_response_route.as')], function (Router $router) {
            $router->get(
                config('paymob.payments.callbacks.transaction_response_route.uri'),
                config('paymob.payments.callbacks.transaction_response_route.action')
            );
        });
    }
}
