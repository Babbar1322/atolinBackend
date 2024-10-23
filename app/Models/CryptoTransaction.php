<?php

namespace App\Models;

use App\Encryption\Encryption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CryptoTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $block_hash
 * @property string|null $contract_address
 * @property string|null $fee
 * @property string|null $amount
 * @property string|null $from
 * @property string|null $gas_price
 * @property string|null $hash
 * @property string|null $status
 * @property string|null $to
 * @property string|null $transaction_type
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereBlockHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereContractAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereGasPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoTransaction whereUserId($value)
 * @mixin \Eloquent
 */
class CryptoTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'double'
    ];

    protected static function booted()
    {
        static::retrieved(function ($cryptoTransaction) {
            $cryptoTransaction->decryptField();
        });
    }

    public function decryptField()
    {
        // Decrypt the 'to' field (adjust this based on your encryption logic)
        $this->to = Encryption::decrypt($this->to);
        $this->from = Encryption::decrypt($this->from);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
