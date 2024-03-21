<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lastname',
        'contact',
        'kyc_type',
        'kyc_number',
        'expiry_date',
        'issuing_country',
        'status',
        'utype',
        'stripe_uid',
        'priority_id',
        'city',
        'otp',
        'two_factor_secret',
        'otp_verified',
        'country_code',
        'state',
        'app_pin_status',
        'app_pin',
        'g2f_temp',
        'g2f_status',
        'notifications_settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function country(){
        return $this->hasOne('App\Models\Country','id','country_code');
    }

    public function wallet()
    {
        return $this->hasOne(CryptoWallet::class);
    }

    public function crypto_transaction()
    {
        return $this->hasMany(CryptoTransaction::class);
    }

}
