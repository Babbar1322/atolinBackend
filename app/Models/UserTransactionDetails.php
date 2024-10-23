<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserTransactionDetails
 *
 * @property int $id
 * @property string|null $transaction_id
 * @property int|null $user_id
 * @property string|null $receiver_id
 * @property string|null $source_id
 * @property string|null $amount
 * @property string|null $t_type
 * @property string|null $transaction_type
 * @property string|null $comments
 * @property string|null $status
 * @property string|null $process_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $last4
 * @property-read User|null $receiver
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereProcessDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereUserId($value)
 * @mixin \Eloquent
 */
class UserTransactionDetails extends Model
{
    use HasFactory;

    protected $table = "user_transaction_details";

    protected $fillable = [
        'transaction_id',
        'user_id',
        'source_id',
        'amount',
        'receiver_id',
        't_type',
        'comments',
        'last4',
        'status',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id')->select('id','name','contact');
    }
}
