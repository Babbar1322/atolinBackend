<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailLoginOTP
 *
 * @property int $id
 * @property int $user_id
 * @property int $otp
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailLoginOTP whereUserId($value)
 * @mixin \Eloquent
 */
class EmailLoginOTP extends Model
{
    use HasFactory;
}
