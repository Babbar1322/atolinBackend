<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRequestMoney
 *
 * @property int $id
 * @property string $requester_user_id
 * @property string $receiver_user_id
 * @property string $amount
 * @property string $currency
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereReceiverUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereRequesterUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequestMoney whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserRequestMoney extends Model
{
    use HasFactory;

    protected $table = "user_request_money";

    protected $fillable = [
        'requester_user_id',
                'receiver_user_id',
                'amount',
                'currency',
                'comments',
                'created_at'
    ];

    public function user(){
        
    }
}
