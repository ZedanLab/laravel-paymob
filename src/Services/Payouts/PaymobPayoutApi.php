<?php

namespace ZedanLab\Paymob\Services\Payouts;

use Illuminate\Config\Repository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use stdClass;

class PaymobPayoutApi extends Repository
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var \ZedanLab\Paymob\Services\Payouts\PaymobPayoutConfig
     */
    protected $config;

    /**
     * Create a new payout api instance.
     *
     * @param  \ZedanLab\Paymob\Services\Payouts\PaymobPayoutConfig $config
     * @return void
     */
    public function __construct(PaymobPayoutConfig $config = null)
    {
        if (blank($config)) {
            $config = new PaymobPayoutConfig();
        }

        $this->config = $config;
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
     * @see https://stagingpayouts.paymobsolutions.com/docs/generate_and_refresh_token_api/
     *
     * @return self
     */
    public function sendAuthenticationRequest(): self
    {
        $response = Http::asForm()
            ->withBasicAuth(
                $this->config->get('client_id'),
                $this->config->get('client_secret')
            )
            ->post(
                $this->config->get('endpoints.authentication_request'),
                $this->buildAuthenticationRequestData(),
            )
            ->onError(function (Response $response) {
                throw $response->toException();
            });

        // @phpstan-ignore-next-line
        $this->accessToken = $response->object()->access_token;

        return $this;
    }

    /**
     * Build request data.
     *
     * @return array
     */
    protected function buildAuthenticationRequestData(): array
    {
        return [
            'grant_type' => 'password',
            'username' => $this->config->get('username'),
            'password' => $this->config->get('password'),
        ];
    }

    /**
     * Send an disburse request.
     *
     * @see https://stagingpayouts.paymobsolutions.com/docs/instant_cashin_api/
     *
     * @param  array      $data
     * @return stdClass
     */
    public function sendDisburseRequest(array $data): stdClass
    {
        $response = Http::asJson()
            ->withToken($this->accessToken())
            ->post($this->config->get('endpoints.disburse'), $data)
            ->onError(function (Response $response) {
                throw $response->toException();
            });

        return $response->object();
    }
}
