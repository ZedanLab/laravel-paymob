<?php

namespace ZedanLab\Paymob\Services\Payouts;

use Exception;
use Illuminate\Config\Repository;

class PaymobPayoutConfig extends Repository
{
    /**
     * Create a new configuration repository.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct(array $items = [])
    {
        if (empty($items)) {
            $items = config('paymob.payouts');
        }

        parent::__construct($items);

        throw_unless($this->isApiKeysDefined(), new Exception("Please check the Paymob Payouts credentials."));
    }

    /**
     * Determine whatever the credentials has a value or not.
     *
     * @return bool
     */
    public function isApiKeysDefined(): bool
    {
        return ! blank($this->get('username'))
        && ! blank($this->get('password'))
        && ! blank($this->get('client_id'))
        && ! blank($this->get('client_secret'));
    }

    /**
     * Return all configuration as a collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->all());
    }
}
