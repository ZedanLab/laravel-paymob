<?php

namespace ZedanLab\Paymob\PayoutIssuers\Drivers;

use Exception;
use ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer;

class AmanPayoutIssuer extends BasePayoutIssuer
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
     * Set payout first_name.
     *
     * @param  string $firstName
     * @return self
     */
    public function setFirstName(string $firstName): self
    {
        $this->set('first_name', $firstName);

        return $this;
    }

    /**
     * Set payout last_name.
     *
     * @param  string $lastName
     * @return self
     */
    public function setLastName(string $lastName): self
    {
        $this->set('last_name', $lastName);

        return $this;
    }

    /**
     * Set payout email.
     *
     * @param  string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->set('email', $email);

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
            'issuer' => 'aman',
            'amount' => $this->get('amount'),
            'msisdn' => $this->get('msisdn'),
            'first_name' => $this->get('first_name'),
            'last_name' => $this->get('last_name'),
            'email' => $this->get('email'),
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
            new Exception("Invalid msisdn, '{$msisdn}' given. ex. 01092737975")
        );

        throw_if(
            is_null($firstName = $this->get('first_name'))
            || ! is_string($firstName),
            new Exception("Invalid first_name, '{$firstName}' given.")
        );

        throw_if(
            is_null($lastName = $this->get('last_name'))
            || ! is_string($lastName),
            new Exception("Invalid last_name, '{$lastName}' given.")
        );

        throw_if(
            is_null($email = $this->get('email'))
            || ! is_string($email)
            || ! filter_var($email, FILTER_VALIDATE_EMAIL),
            new Exception("Invalid email, '{$email}' given.")
        );

        return true;
    }
}
