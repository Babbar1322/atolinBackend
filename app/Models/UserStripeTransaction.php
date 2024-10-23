<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserStripeTransaction
 *
 * @property int $id
 * @property string|null $stripe_tid
 * @property string|null $stripe_uid
 * @property string|null $email
 * @property string|null $card_id
 * @property string|null $amount
 * @property string|null $currency
 * @property string|null $t_type
 * @property string|null $request_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereStripeTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereStripeUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereTType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserStripeTransaction extends Model
{
    use HasFactory;

    protected $table = 'user_stripe_transactions_details';

    protected $fillable = [
        'stripe_tid',
        'stripe_uid',
        'card_id',
        'amount',
        'currency',
        't_type',
        'request_at',
        'created_at' 
    ];
}
