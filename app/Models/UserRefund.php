<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRefund
 *
 * @property int $id
 * @property string $transaction_id
 * @property int $amount
 * @property int|null $user_id
 * @property string $destination
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereUserId($value)
 * @mixin \Eloquent
 */
class UserRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'user_id',
        'destination',
        'status',
    ];
}
