<?php

namespace ZedanLab\Paymob\Models;

use Illuminate\Database\Eloquent\Model;
use ZedanLab\Paymob\Enums\PaymobPayoutIssuer;
use ZedanLab\Paymob\Enums\PaymobPayoutStatus;

/**
 * \ZedanLab\Paymob\Models\PaymobPayout
 *
 * @property int $id
 * @property string $transaction_id
 * @property float $amount
 * @property object $data
 * @property object $callback
 * @property string|PaymobPayoutIssuer $issuer
 * @property string|PaymobPayoutStatus $status
 * @property int|null $receiver_id
 * @property string|null $receiver_type
 * @property-read Model|\Eloquent $receiver
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobPayout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobPayout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobPayout query()
 * @mixin \Eloquent
 */
class PaymobPayout extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paymob_payouts_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'transaction_id',
        'amount',
        'issuer',
        'status',
        'data',
        'callback',
        'receiver_id',
        'receiver_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'object',
        'callback' => 'object',
        'status' => PaymobPayoutStatus::class,
        'issuer' => PaymobPayoutIssuer::class,
    ];

    /**
     * ----------------------------------------------------------------- *
     * --------------------------- Relations --------------------------- *
     * ----------------------------------------------------------------- *.
     */

    /**
     * Get the receiver model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function receiver()
    {
        return $this->morphTo();
    }

    /*
     * ----------------------------------------------------------------- *
     * --------------------------- Accessors --------------------------- *
     * ----------------------------------------------------------------- *
     */

    /*
     * ----------------------------------------------------------------- *
     * ---------------------------- Mutators --------------------------- *
     * ----------------------------------------------------------------- *
     */

    /*
     * ----------------------------------------------------------------- *
     * ----------------------------- Scopes ---------------------------- *
     * ----------------------------------------------------------------- *
     */

    /*
     * ----------------------------------------------------------------- *
     * ------------------------------ Misc ----------------------------- *
     * ----------------------------------------------------------------- *
     */
    //
}
