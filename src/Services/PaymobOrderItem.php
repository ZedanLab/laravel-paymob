<?php

namespace ZedanLab\Paymob\Services;

use Illuminate\Contracts\Support\Arrayable;

class PaymobOrderItem implements Arrayable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $amount_cents;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $quantity;

    /**
     * Create a new paymob instance.
     *
     * @param  string $name
     * @param  string $amount_cents
     * @param  string $description
     * @param  string $quantity
     * @return void
     */
    public function __construct(
        string $name,
        string $amount_cents,
        string $description,
        string $quantity,
    ) {
        $this->setName($name);
        $this->setAmountCents($amount_cents);
        $this->setDescription($description);
        $this->setQuantity($quantity);
    }

    /**
     * Make a new instance from the given data.
     *
     * @param  string                                      $name
     * @param  string                                      $amount_cents
     * @param  string                                      $description
     * @param  string                                      $quantity
     * @return \ZedanLab\Paymob\Services\PaymobOrderItem
     */
    public static function make(
        string $name,
        string $amount_cents,
        string $description,
        string $quantity,
    ): PaymobOrderItem {
        return new self($name, $amount_cents, $description, $quantity);
    }

    /**
     * Set item's name.
     *
     * @param  string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set item's amount_cents.
     *
     * @param  string $amount_cents
     * @return self
     */
    public function setAmountCents(string $amount_cents): self
    {
        $this->amount_cents = $amount_cents;

        return $this;
    }

    /**
     * Set item's description.
     *
     * @param  string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set item's quantity.
     *
     * @param  string $quantity
     * @return self
     */
    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get item's name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get item's amount_cents.
     *
     * @return string
     */
    public function getAmountCents(): string
    {
        return $this->amount_cents;
    }

    /**
     * Get item's description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get item's quantity.
     *
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'amount_cents' => $this->getAmountCents(),
            'description' => $this->getDescription(),
            'quantity' => $this->getQuantity(),
        ];
    }
}
