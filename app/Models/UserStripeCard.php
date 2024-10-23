<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserStripeCard
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $priority_id
 * @property string|null $card_id
 * @property string|null $fingerprint
 * @property string|null $last4
 * @property string|null $brand
 * @property string|null $country
 * @property string|null $exp_month
 * @property string|null $exp_year
 * @property string|null $card_type
 * @property string|null $card_holder_name
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserStripeCard extends Model
{
    use HasFactory;

    protected $table = "user_stripe_carddetails";

    protected $fillable = [
        'priority_id',
        'card_id',
        'fingerprint',
        'last4',
        'brand',
        'country',
        'exp_month',
        'exp_year',
        'created_at'
    ];

}
