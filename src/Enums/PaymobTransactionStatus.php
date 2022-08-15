<?php

namespace ZedanLab\Paymob\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static SUCCESS()
 * @method static static DECLINED()
 */
final class PaymobTransactionStatus extends Enum
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const DECLINED = 'declined';
}
