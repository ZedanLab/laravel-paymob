<?php

namespace ZedanLab\Paymob\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static AUTHORIZE()
 * @method static static CAPTURE()
 * @method static static THREED_SECURE()
 * @method static static VOID()
 * @method static static STANDALONE()
 * @method static static REFUND()
 */
final class PaymobTransactionType extends Enum
{
    public const AUTHORIZE = 'auth';
    public const CAPTURE = 'capture';
    public const THREED_SECURE = '3ds';
    public const VOID = 'void';
    public const STANDALONE = 'standalone';
    public const REFUND = 'refund';
}
