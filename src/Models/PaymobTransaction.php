<?php

namespace ZedanLab\Paymob\Models;

use Illuminate\Database\Eloquent\Model;
use ZedanLab\Paymob\Enums\PaymobTransactionType;
use ZedanLab\Paymob\Enums\PaymobTransactionStatus;

/**
 * \ZedanLab\Paymob\Models\PaymobTransaction
 *
 * @property int $id
 * @property int $paymob_id
 * @property string $hmac
 * @property object $data
 * @property string $status
 * @property string $type
 * @property int|null $payer_id
 * @property string|null $payer_type
 * @property int|null $payable_id
 * @property string|null $payable_type
 * @property-read Model|\Eloquent $payable
 * @property-read Model|\Eloquent $payer
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymobTransaction query()
 * @mixin \Eloquent
 */
class PaymobTransaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paymob_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'paymob_id',
        'hmac',
        'data',
        'status',
        'type',
        'payer_id',
        'payer_type',
        'payable_id',
        'payable_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data'   => 'object',
        'status' => PaymobTransactionStatus::class,
        'type'   => PaymobTransactionType::class,
    ];

    /**
     * ----------------------------------------------------------------- *
     * --------------------------- Relations --------------------------- *
     * ----------------------------------------------------------------- *.
     */

    /**
     * Get the parent payable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent payable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payer()
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
