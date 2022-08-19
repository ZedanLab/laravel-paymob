<?php

namespace ZedanLab\Paymob\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ETISALAT()
 * @method static static VODAFONE()
 * @method static static ORANGE()
 * @method static static AMAN()
 * @method static static BANK_WALLET()
 * @method static static BANK_CARD()
 */
final class PaymobPayoutIssuer extends Enum
{
    public const ETISALAT = 'etisalat';
    public const VODAFONE = 'vodafone';
    public const ORANGE = 'orange';
    public const AMAN = 'aman';
    public const BANK_WALLET = 'bank_wallet';
    public const BANK_CARD = 'bank_card';
}
