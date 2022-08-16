<?php

namespace ZedanLab\Paymob\Services;

use Illuminate\Config\Repository;
use ZedanLab\Paymob\Enums\PaymobTransactionStatus;
use ZedanLab\Paymob\Enums\PaymobTransactionType;
use ZedanLab\Paymob\Models\PaymobTransaction;

class PaymobProcessed extends Repository
{
    /**
     * @var \ZedanLab\Paymob\Services\PaymobConfig
     */
    protected $config;

    /**
     * Create a new paymob response instance.
     *
     * @param  array                                  $response
     * @param  \ZedanLab\Paymob\Services\PaymobConfig $config
     * @return void
     */
    public function __construct(array $response, PaymobConfig $config = null)
    {
        $this->set($response);

        $this->config = blank($config) ? new PaymobConfig() : $config;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        /**
         * @var \ZedanLab\Paymob\Models\PaymobTransaction
         */
        $paymobTransaction = PaymobTransaction::create([
            'paymob_id' => $this->get('obj.id'),
            'hmac' => $this->get('hmac'),
            'data' => $this->get('obj'),
            'status' => $this->getStatus(),
            'type' => $this->getType(),
            'payer_type' => $this->get('obj.order.data.payer.type'),
            'payer_id' => $this->get('obj.order.data.payer.id'),
            'payable_type' => $this->get('obj.order.data.payable.type'),
            'payable_id' => $this->get('obj.order.data.payable.id'),
        ]);

        return $paymobTransaction;
    }

    /**
     * Get transaction's payer.
     *
     * @return array|null
     */
    public function getPayer(): ?array
    {
        return $this->get('obj.order.data.payer');
    }

    /**
     * Get transaction's payable.
     *
     * @return array|null
     */
    public function getPayable(): ?array
    {
        return $this->get('obj.order.data.payable');
    }

    /**
     * Get transaction's status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        if (filter_var($this->get('obj.pending'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionStatus::PENDING;
        }

        return filter_var($this->get('obj.success'), FILTER_VALIDATE_BOOLEAN)
        ? PaymobTransactionStatus::SUCCESS
        : PaymobTransactionStatus::DECLINED;
    }

    /**
     * Get transaction's type.
     *
     * @return string
     */
    public function getType(): string
    {
        if (filter_var($this->get('obj.is_auth'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::AUTHORIZE;
        }

        if (filter_var($this->get('obj.is_capture'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::CAPTURE;
        }

        if (filter_var($this->get('obj.is_void'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::VOID;
        }

        if (filter_var($this->get('obj.is_standalone_payment'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::STANDALONE;
        }

        return PaymobTransactionType::REFUND;
    }

    public function hasValidHmac(): bool
    {
        return hash_hmac('sha512', $this->buildHmacData(), $this->config->get('hmac_secret')) === $this->get('hmac');
    }

    protected function buildHmacData(): string
    {
        $hmacData = [
            'amount_cents' => $this->get('obj.amount_cents'),
            'created_at' => $this->get('obj.created_at'),
            'currency' => $this->get('obj.currency'),
            'error_occured' => filter_var($this->get('obj.error_occured'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'has_parent_transaction' => filter_var($this->get('obj.has_parent_transaction'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'id' => $this->get('obj.id'),
            'integration_id' => $this->get('obj.integration_id'),
            'is_3d_secure' => filter_var($this->get('obj.is_3d_secure'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_auth' => filter_var($this->get('obj.is_auth'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_capture' => filter_var($this->get('obj.is_capture'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_refunded' => filter_var($this->get('obj.is_refunded'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_standalone_payment' => filter_var($this->get('obj.is_standalone_payment'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_voided' => filter_var($this->get('obj.is_voided'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'order.id' => $this->get('obj.order.id'),
            'owner' => $this->get('obj.owner'),
            'pending' => filter_var($this->get('obj.pending'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'source_data.pan' => $this->get('obj.source_data.pan'),
            'source_data.sub_type' => $this->get('obj.source_data.sub_type'),
            'source_data.type' => $this->get('obj.source_data.type'),
            'success' => filter_var($this->get('obj.success'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
        ];

        ksort($hmacData);

        return implode('', $hmacData);
    }
}
