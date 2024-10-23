<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TokenSwap
 *
 * @property-read mixed $fee_amount
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap query()
 * @mixin \Eloquent
 */
class TokenSwap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from',
        'to',
        'atolin_amount',
        'token_amount',
        'fee',
        'token_symbol',
    ];

    protected $appends = ['fee_amount'];

    public function getFeeAmountAttribute()
    {
        return $this->atolin_amount * $this->fee / 100;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amountAfterFee()
    {
        return $this->atolin_amount - $this->fee_amount;
    }
}
