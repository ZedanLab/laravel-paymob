<?php

namespace ZedanLab\Paymob\Services;

use Exception;
use Illuminate\Config\Repository;

class PaymobConfig extends Repository
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
            $items = config('paymob');
        }

        parent::__construct($items);

        throw_unless($this->isApiKeyDefined(), new Exception("PAYMOB_API_KEY:api_key is not defined."));
    }

    /**
     * Determine whatever the API key has a value or not.
     *
     * @return bool
     */
    public function isApiKeyDefined(): bool
    {
        return ! blank($this->get('api_key'));
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
