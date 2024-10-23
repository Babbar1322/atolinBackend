<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WithdrawalRequest
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property float $admin_fees
 * @property float $net_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $account_holder_name
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property string|null $ifsc
 * @property string|null $phone_no
 * @property string|null $trans_id
 * @property string $request_id
 * @property string $bank_id
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereAccountHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereAdminFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereIfsc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest wherePhoneNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereTransId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithdrawalRequest whereUserId($value)
 * @mixin \Eloquent
 */
class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','amount','status','account_holder_name','bank_name','account_number','ifsc','phone_no', 'bank_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->select('name','contact');
    }
}
