<?php

namespace ZedanLab\Paymob\PayoutIssuers;

use Exception;
use Illuminate\Config\Repository;
use ZedanLab\Paymob\Contracts\PaymobPayoutIssuer;
use ZedanLab\Paymob\Enums\PaymobPayoutStatus;
use ZedanLab\Paymob\Models\PaymobPayout;
use ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi;

/**
 * ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer
 *
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setMsisdn(string $msisdn)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setFirstName(string $firstName)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setLastName(string $lastName)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setFullName(string $fullName)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setBankCardNumber(string $bankCardNumber)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setBankCode(string $bankCode)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setBankTransactionType(string $bankTransactionType)
 * @method \ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer setEmail(string $email)
 */
abstract class BasePayoutIssuer extends Repository implements PaymobPayoutIssuer
{
    /**
     * @var \ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi
     */
    protected $api;

    /**
     * @param \ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi $api
     */
    public function __construct(PaymobPayoutApi $api)
    {
        $this->api = $api;
    }

    /**
     * @return \ZedanLab\Paymob\Services\Payouts\PaymobPayoutApi
     */
    public function api(): PaymobPayoutApi
    {
        return $this->api;
    }

    /**
     * Retrieve the disburse response.
     *
     * @return array|null
     */
    public function getDisburseResponse(): ?array
    {
        return $this->get('disburse_response');
    }

    /**
     * Set payout amount.
     *
     * @param  float  $amout
     * @return self
     */
    public function setAmount(float $amout): self
    {
        $this->set('amount', $amout);

        return $this;
    }

    /**
     * Build disburse request data.
     *
     * @return array
     */
    public function buildDisburseRequestData(): array
    {
        return [];
    }

    /**
     * Validate the required data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        throw_if(
            is_null($amount = $this->get('amount'))
            || ! is_numeric($amount),
            new Exception("Invalid amount, '{$amount}' given instead of float.")
        );

        return true;
    }

    /**
     * Send disburse request.
     *
     * @return self
     */
    public function disburse(): self
    {
        $response = $this->api()
                         ->sendAuthenticationRequest()
                         ->sendDisburseRequest($this->buildDisburseRequestData());

        $this->set('disburse_response', $response);

        return $this;
    }

    /**
     * Return disburse response as PaymobPayout model.
     *
     * @param  array                                  $attriibutes
     * @return \ZedanLab\Paymob\Models\PaymobPayout
     */
    public function asPaymobPayout($attriibutes = []): PaymobPayout
    {
        $data = [
            'transaction_id' => $this->get('disburse_response.transaction_id'),
            'amount' => $this->get('disburse_response.amount'),
            'issuer' => $this->get('disburse_response.issuer'),
            'status' => in_array($status = $this->get('disburse_response.disbursement_status'), ['success', 'successful']) ? PaymobPayoutStatus::SUCCESS : $status,
            'data' => $this->get('disburse_response'),
            'callback' => null,
            'receiver_id' => null,
            'receiver_type' => null,
        ];

        return new PaymobPayout(array_merge($data, $attriibutes));
    }

    /**
     * Save disburse response as PaymobPayout model.
     *
     * @return \ZedanLab\Paymob\Models\PaymobPayout
     */
    public function saveAsPaymobPayout(): PaymobPayout
    {
        $this->asPaymobPayout()->save();

        return $this->asPaymobPayout();
    }
}
