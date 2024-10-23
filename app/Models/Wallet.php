<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wallet
 *
 * @property-read \App\Models\User|null $from
 * @property-read mixed $fee_amount
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_id',
        'amount',
        'status',
        'type',
        'fee',
        't_type',
        'transaction_id',
        'remarks',
        'token_amount',
        'token_symbol',
    ];

    protected $append = ['fee_amount'];

    public function getFeeAmountAttribute() {
        if ($this->fee !== null) {
            return $this->amount * $this->fee / 100;
        }
        return 0;
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function from() {
        return $this->belongsTo(User::class,'from_id','id');
    }


    public function totalFee() {
        $transactions = $this->where('status', 'APPROVED')->where('type', 'TOKEN_SWAP')->get();

        $total_fee = $transactions->sum(function($transaction) {
            if ($transaction->t_type === 'debit') {
                return $transaction->amount * $transaction->fee /100;
            } else {
                return $transaction->amount * $transaction->fee /100;
            }
        });
        return $total_fee;
    }

    public static function balance($user_id)
    {
        $credit = self::where('user_id', $user_id)->where('t_type', 'credit')->where('status', 'APPROVED')->sum('amount');
        $debit = self::where('user_id', $user_id)->where('t_type', 'debit')->where('status', 'APPROVED')->sum('amount');
        $balance = $credit - $debit;
        return $balance;
    }
}
