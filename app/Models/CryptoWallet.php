<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CryptoWallet
 *
 * @property int $id
 * @property int $user_id
 * @property string $wallet_address
 * @property string $private_key
 * @property string $secret_phrase
 * @property string $public_key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereSecretPhrase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CryptoWallet whereWalletAddress($value)
 * @mixin \Eloquent
 */
class CryptoWallet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
