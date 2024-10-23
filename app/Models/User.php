<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $stripe_uid
 * @property string $name
 * @property string|null $lastname
 * @property string $email
 * @property string|null $contact
 * @property string|null $payment_pin
 * @property string|null $kyc_type
 * @property string|null $kyc_number
 * @property string|null $expiry_date
 * @property string|null $issuing_country
 * @property string|null $country_code
 * @property string|null $state
 * @property string|null $city
 * @property int $status
 * @property string $utype
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $app_pin
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string $notifications_settings
 * @property mixed|null $privacy_settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $otp
 * @property string|null $otp_verified
 * @property string|null $door_no
 * @property string|null $street_name
 * @property int|null $zip
 * @property string $registered_users
 * @property int $balance
 * @property string $kyc_status
 * @property string|null $g2f_temp
 * @property string $g2f_status
 * @property string $app_pin_status 0 => INACTIVE, 1 => ACTIVE
 * @property string|null $stripe_acc_id
 * @property string $priority_id
 * @property-read \App\Models\Country|null $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CryptoTransaction> $crypto_transaction
 * @property-read int|null $crypto_transaction_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\CryptoWallet|null $wallet
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAppPin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAppPinStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoorNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereG2fStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereG2fTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIssuingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKycNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKycStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKycType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNotificationsSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePaymentPin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrivacySettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegisteredUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStreetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeAccId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereZip($value)
 * @mixin \Eloquent
 */
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
        'kyc_id',
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
