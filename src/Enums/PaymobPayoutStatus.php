<?php

namespace ZedanLab\Paymob\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING()
 * @method static static SUCCESS()
 * @method static static FAILED()
 */
final class PaymobPayoutStatus extends Enum
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const FAILED = 'failed';
}
