<?php

namespace ZedanLab\Paymob\PayoutIssuers\Drivers;

use Exception;
use Iban\Validation\Iban;
use Iban\Validation\Validator as IbanValidator;
use ZedanLab\Paymob\PayoutIssuers\BasePayoutIssuer;

class BankCardPayoutIssuer extends BasePayoutIssuer
{
    public const ALLOWED_BANK_CODES = [
        'AUB',
        'CITI',
        'MIDB',
        'BDC',
        'HSBC',
        'CAE',
        'EGB',
        'UB',
        'QNB',
        'ARAB',
        'ENBD',
        'ABK',
        'NBK',
        'ABC',
        'FAB',
        'ADIB',
        'CIB',
        'HDB',
        'MISR',
        'AAIB',
        'EALB',
        'EDBE',
        'FAIB',
        'BLOM',
        'ADCB',
        'BOA',
        'SAIB',
        'NBE',
        'ABRK',
        'POST',
        'NSB',
        'IDB',
        'SCB',
        'MASH',
        'AIB',
        'AUDI',
        'GASC',
        'ARIB',
        'PDAC',
        'NBG',
        'CBE',
        'BBE',
    ];

    public const ALLOWED_BANK_TRANSACTION_TYPES = [
        'salary',
        'credit_card',
        'prepaid_card',
        'cash_transfer',
    ];

    /**
     * Set payout full_name.
     *
     * @param  string $fullName
     * @return self
     */
    public function setFullName(string $fullName): self
    {
        $this->set('full_name', $fullName);

        return $this;
    }

    /**
     * Set payout bank_card_number.
     *
     * @param  string $bankCardNumber
     * @return self
     */
    public function setBankCardNumber(string $bankCardNumber): self
    {
        $this->set('bank_card_number', $bankCardNumber);

        return $this;
    }

    /**
     * Set payout bank_code.
     *
     * @param  string $bankCode
     * @return self
     */
    public function setBankCode(string $bankCode): self
    {
        $this->set('bank_code', $bankCode);

        return $this;
    }

    /**
     * Set payout bank_transaction_type.
     *
     * @param  string $bankTransactionType
     * @return self
     */
    public function setBankTransactionType(string $bankTransactionType): self
    {
        $this->set('bank_transaction_type', $bankTransactionType);

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
            'issuer' => 'bank_card',
            'amount' => $this->get('amount'),
            'full_name' => $this->get('full_name'),
            'bank_card_number' => $this->get('bank_card_number'),
            'bank_code' => $this->get('bank_code'),
            'bank_transaction_type' => $this->get('bank_transaction_type'),
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
            is_null($fullName = $this->get('full_name'))
            || ! is_string($fullName),
            new Exception("Invalid full_name, '{$fullName}' given.")
        );

        throw_if(
            is_null($bankCardNumber = $this->get('bank_card_number'))
            || ! is_string($bankCardNumber)
            || (! $this->isIban($bankCardNumber) && ! $this->isBankCard($bankCardNumber)),
            new Exception("Invalid bank_card_number, '{$bankCardNumber}' given. ex. 1111-2222-3333-4444, EG829299835722904511873050307")
        );

        throw_if(
            is_null($bankCode = $this->get('bank_code'))
            || ! is_string($bankCode)
            || ! $this->isBankCode($bankCode),
            new Exception("Invalid bank_code, '{$bankCode}' given. ex. " . implode(', ', static::ALLOWED_BANK_CODES))
        );

        throw_if(
            is_null($bankTransactionType = $this->get('bank_transaction_type'))
            || ! is_string($bankTransactionType)
            || ! $this->isBankTransactionType($bankTransactionType),
            new Exception("Invalid bank_transaction_type, '{$bankTransactionType}' given. ex. " . implode(', ', static::ALLOWED_BANK_TRANSACTION_TYPES))
        );

        return true;
    }

    /**
     * Indicates if the given string is a valid IBAN or not.
     *
     * @param  string $iban
     * @return bool
     */
    public static function isIban(string $iban): bool
    {
        $iban = new Iban($iban);
        $validator = new IbanValidator();

        return $validator->validate($iban);
    }

    /**
     * Indicates if the given string is a valid bank card or not.
     *
     * @param  string $bankCard
     * @return bool
     */
    public static function isBankCard(string $bankCard): bool
    {
        if (strlen(str($bankCard)->remove([' ', '-'])) !== 16) {
            return false;
        }

        return true;
    }

    /**
     * Indicates if the given string is a valid bank code or not.
     *
     * @param  string $bankCode
     * @return bool
     */
    public static function isBankCode(string $bankCode): bool
    {
        return in_array($bankCode, static::ALLOWED_BANK_CODES);
    }

    /**
     * Indicates if the given string is a valid bank transaction type or not.
     *
     * @param  string $bankTransactionType
     * @return bool
     */
    public static function isBankTransactionType(string $bankTransactionType): bool
    {
        return in_array($bankTransactionType, static::ALLOWED_BANK_TRANSACTION_TYPES);
    }
}
