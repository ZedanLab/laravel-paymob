<?php

namespace ZedanLab\Paymob\Services;

use Illuminate\Config\Repository;
use ZedanLab\Paymob\Paymob;

class PaymobOrder extends Repository
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobConfig
     */
    protected $config;

    /**
     * Create a new paymob instance.
     *
     * @param  \ZedanLab\Paymob\Services\PaymobConfig $config
     * @return void
     */
    public function __construct(PaymobConfig $config = null)
    {
        if (blank($config)) {
            $config = new PaymobConfig();
        }

        $this->config = $config;
    }

    /**
     * Determine whatever if order need a delivery or not.
     *
     * @param  bool   $enabled
     * @return self
     */
    public function deliveryNeeded(bool $enabled = true): self
    {
        $this->set('delivery_needed', $enabled);

        return $this;
    }

    /**
     * Set order reference id (merchant_order_id).
     *
     * @param  string $id
     * @return self
     */
    public function referenceId(string $id): self
    {
        $this->set('merchant_order_id', $id);

        return $this;
    }

    /**
     * Set order shipping_data.
     *
     * @param  array  $data
     * @return self
     */
    public function shippingData(array $data): self
    {
        $this->set('shipping_data', $data);

        return $this;
    }

    /**
     * Set order billing_data.
     *
     * @param  string  $apartment
     * @param  string  $email
     * @param  string  $floor
     * @param  string  $first_name
     * @param  string  $street
     * @param  string  $building
     * @param  string  $phone_number
     * @param  string  $shipping_method
     * @param  string  $postal_code
     * @param  string  $city
     * @param  string  $country
     * @param  string  $last_name
     * @param  string  $state
     * @return self
     */
    public function billingData(
        string $first_name,
        string $last_name,
        string $email,
        string $phone_number,
        string $apartment = 'N/A',
        string $floor = 'N/A',
        string $street = 'N/A',
        string $building = 'N/A',
        string $shipping_method = 'N/A',
        string $postal_code = 'N/A',
        string $city = 'N/A',
        string $country = 'N/A',
        string $state = 'N/A',
    ): self {
        $this->set('billing_data', compact([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'apartment',
            'floor',
            'street',
            'building',
            'shipping_method',
            'postal_code',
            'city',
            'country',
            'state',
        ]));

        return $this;
    }

    /**
     * Set order notify_user_with_email.
     *
     * @param  bool   $enabled
     * @return self
     */
    public function notifyUserWithEmail(bool $enabled): self
    {
        $this->set('notify_user_with_email', $enabled);

        return $this;
    }

    /**
     * Set order additional data.
     *
     * @param  array  $data
     * @return self
     */
    public function additionalData(array $data): self
    {
        $this->set('data', $data);

        return $this;
    }

    /**
     * Set order items
     *
     * @param  array $items
     * @return self
     */
    public function items(...$items): self
    {
        $items = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

        $this->set('items', $items);

        return $this;
    }

    /**
     * Set the order's amount.
     *
     * @param  float  $amount
     * @param  bool   $inCents
     * @return self
     */
    public function amount(float $amount, bool $inCents = false): self
    {
        if (! $inCents) {
            $amount *= 100;
        }

        $this->set('amount_cents', $amount);

        return $this;
    }

    /**
     * Set the order's currency.
     *
     * @param  string $currency
     * @return self
     */
    public function currency(string $currency): self
    {
        $this->set('currency', $currency);

        return $this;
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

    /**
     * Return order's config.
     *
     * @return \ZedanLab\Paymob\Services\PaymobConfig|null
     */
    public function config(): ?PaymobConfig
    {
        return $this->config;
    }
}
