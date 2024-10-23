<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Forgot2fa
 *
 * @property int $id
 * @property string $token
 * @property string $otp
 * @property string|null $g2fa_key
 * @property string|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereG2faKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Forgot2fa whereVerifiedAt($value)
 * @mixin \Eloquent
 */
class Forgot2fa extends Model
{
    use HasFactory;
}
