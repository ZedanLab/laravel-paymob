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
        if (boolval($this->get('pending'))) {
            return PaymobTransactionStatus::PENDING;
        }

        return boolval($this->get('success'))
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
        if (boolval($this->get('is_auth'))) {
            return PaymobTransactionType::AUTHORIZE;
        }

        if (boolval($this->get('is_capture'))) {
            return PaymobTransactionType::CAPTURE;
        }

        if (boolval($this->get('is_void'))) {
            return PaymobTransactionType::VOID;
        }

        if (boolval($this->get('is_standalone_payment'))) {
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
            'error_occured' => boolval($this->get('error_occured')) ? 'true' : 'false',
            'has_parent_transaction' => boolval($this->get('has_parent_transaction')) ? 'true' : 'false',
            'id' => $this->get('id'),
            'integration_id' => $this->get('integration_id'),
            'is_3d_secure' => boolval($this->get('is_3d_secure')) ? 'true' : 'false',
            'is_auth' => boolval($this->get('is_auth')) ? 'true' : 'false',
            'is_capture' => boolval($this->get('is_capture')) ? 'true' : 'false',
            'is_refunded' => boolval($this->get('is_refunded')) ? 'true' : 'false',
            'is_standalone_payment' => boolval($this->get('is_standalone_payment')) ? 'true' : 'false',
            'is_voided' => boolval($this->get('is_voided')) ? 'true' : 'false',
            'order' => $this->get('order'),
            'owner' => $this->get('owner'),
            'pending' => boolval($this->get('pending')) ? 'true' : 'false',
            'source_data_pan' => $this->get('source_data_pan'),
            'source_data_sub_type' => $this->get('source_data_sub_type'),
            'source_data_type' => $this->get('source_data_type'),
            'success' => boolval($this->get('success')) ? 'true' : 'false',
        ];

        ksort($hmacData);

        return implode('', $hmacData);
    }
}
