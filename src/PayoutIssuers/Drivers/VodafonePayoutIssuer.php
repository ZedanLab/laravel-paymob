<?php

namespace ZedanLab\Paymob\PayoutIssuers\Drivers;

use Exception;
use ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer;

class VodafonePayoutIssuer extends BasePayoutIssuer
{
    /**
     * Set payout msisdn.
     *
     * @param  string $msisdn
     * @return self
     */
    public function setMsisdn(string $msisdn): self
    {
        $this->set('msisdn', $msisdn);

        return $this;
    }

    /**
     * Build disburse request data.
     *
     * @return array
     */
    public function buildDisburseRequestData(): array
    {
        $this->validate();

        return [
            'issuer' => 'vodafone',
            'amount' => $this->get('amount'),
            'msisdn' => $this->get('msisdn'),
        ];
    }

    /**
     * Validate the required data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        parent::validate();

        throw_if(
            is_null($msisdn = $this->get('msisdn'))
            || ! is_numeric($msisdn)
            || strlen($msisdn) !== 11,
            new Exception("Invalid msisdn, '{$msisdn}' given. ex. 01023456789")
        );

        return true;
    }
}
