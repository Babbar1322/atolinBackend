<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentLog
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $response
 * @property string|null $payment_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUserId($value)
 * @mixin \Eloquent
 */
class PaymentLog extends Model
{
    use HasFactory;

    protected $guarded = [];
}
