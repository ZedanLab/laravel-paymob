<?php

namespace ZedanLab\Paymob\Services;

use Illuminate\Config\Repository;
use ZedanLab\Paymob\Enums\PaymobTransactionStatus;
use ZedanLab\Paymob\Enums\PaymobTransactionType;

class PaymobResponse extends Repository
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
     * Get transaction's status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        if (filter_var($this->get('pending'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionStatus::PENDING;
        }

        return filter_var($this->get('success'), FILTER_VALIDATE_BOOLEAN)
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
        if (filter_var($this->get('is_auth'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::AUTHORIZE;
        }

        if (filter_var($this->get('is_capture'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::CAPTURE;
        }

        if (filter_var($this->get('is_void'), FILTER_VALIDATE_BOOLEAN)) {
            return PaymobTransactionType::VOID;
        }

        if (filter_var($this->get('is_standalone_payment'), FILTER_VALIDATE_BOOLEAN)) {
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
            'amount_cents' => $this->get('amount_cents'),
            'created_at' => $this->get('created_at'),
            'currency' => $this->get('currency'),
            'error_occured' => filter_var($this->get('error_occured'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'has_parent_transaction' => filter_var($this->get('has_parent_transaction'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'id' => $this->get('id'),
            'integration_id' => $this->get('integration_id'),
            'is_3d_secure' => filter_var($this->get('is_3d_secure'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_auth' => filter_var($this->get('is_auth'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_capture' => filter_var($this->get('is_capture'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_refunded' => filter_var($this->get('is_refunded'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_standalone_payment' => filter_var($this->get('is_standalone_payment'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'is_voided' => filter_var($this->get('is_voided'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'order' => $this->get('order'),
            'owner' => $this->get('owner'),
            'pending' => filter_var($this->get('pending'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
            'source_data_pan' => $this->get('source_data_pan'),
            'source_data_sub_type' => $this->get('source_data_sub_type'),
            'source_data_type' => $this->get('source_data_type'),
            'success' => filter_var($this->get('success'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false',
        ];

        ksort($hmacData);

        return implode('', $hmacData);
    }
}
