<?php

namespace ZedanLab\Paymob\Contracts;

interface PaymobPayoutIssuer
{
    /**
     * Build disburse request data.
     *
     * @return array
     */
    public function buildDisburseRequestData(): array;

    /**
     * Set payout amount.
     *
     * @param  float  $amout
     * @return self
     */
    public function setAmount(float $amout): self;

    /**
     * Validate the required data.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Send disburse request.
     *
     * @return self
     */
    public function disburse(): self;
}
