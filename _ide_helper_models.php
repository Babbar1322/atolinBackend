<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\BannerImage
 *
 * @property int $id
 * @property string $image
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannerImage whereUpdatedAt($value)
 */
	class BannerImage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereStateId($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $phonecode
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country wherePhonecode($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
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
 */
	class CryptoTransaction extends \Eloquent {}
}

namespace App\Models{
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
 */
	class CryptoWallet extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $name
 * @property string|null $image
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
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
 */
	class EmailLoginOTP extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Forgot2fa extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NotificationUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $receiver_id
 * @property string|null $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationUser whereUserId($value)
 */
	class NotificationUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentCardInvoice
 *
 * @property int $id
 * @property string $card_number
 * @property string $expiry_month
 * @property string $expiry_year
 * @property string $cvv
 * @property string $contact_name
 * @property string $amount
 * @property string $invoice_id
 * @property string $address
 * @property string|null $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $payment_id
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereCvv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereExpiryMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereExpiryYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCardInvoice whereZip($value)
 */
	class PaymentCardInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentInvoice
 *
 * @property int $id
 * @property string $account_number
 * @property string $account_holder_name
 * @property string $routing_number
 * @property string $account_type
 * @property string $contact_name
 * @property string $amount
 * @property string $invoice_id
 * @property string $address
 * @property string|null $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $payment_id
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAccountHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereRoutingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentInvoice whereZip($value)
 */
	class PaymentInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PaymentLog
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $response
 * @property string|null $payment_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentLog whereUserId($value)
 */
	class PaymentLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StripeBankDetails
 *
 * @property int $id
 * @property string|null $bank_id
 * @property string|null $priority_id
 * @property string|null $bank_name
 * @property string|null $fingerprint
 * @property string|null $last4
 * @property string|null $country
 * @property string|null $routing_number
 * @property string|null $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereRoutingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StripeBankDetails whereUpdatedAt($value)
 */
	class StripeBankDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenSwap
 *
 * @property-read mixed $fee_amount
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSwap query()
 */
	class TokenSwap extends \Eloquent {}
}

namespace App\Models{
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
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserDocument
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $document_id
 * @property string $url
 * @property string|null $unique_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document|null $document
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDocument whereUserId($value)
 */
	class UserDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserRefund
 *
 * @property int $id
 * @property string $transaction_id
 * @property int $amount
 * @property int|null $user_id
 * @property string $destination
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRefund whereUserId($value)
 */
	class UserRefund extends \Eloquent {}
}

namespace App\Models{
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
 */
	class UserRequestMoney extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserStripeCard
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $priority_id
 * @property string|null $card_id
 * @property string|null $fingerprint
 * @property string|null $last4
 * @property string|null $brand
 * @property string|null $country
 * @property string|null $exp_month
 * @property string|null $exp_year
 * @property string|null $card_type
 * @property string|null $card_holder_name
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeCard whereUpdatedAt($value)
 */
	class UserStripeCard extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserStripeTransaction
 *
 * @property int $id
 * @property string|null $stripe_tid
 * @property string|null $stripe_uid
 * @property string|null $email
 * @property string|null $card_id
 * @property string|null $amount
 * @property string|null $currency
 * @property string|null $t_type
 * @property string|null $request_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereStripeTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereStripeUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereTType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStripeTransaction whereUpdatedAt($value)
 */
	class UserStripeTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserTransactionDetails
 *
 * @property int $id
 * @property string|null $transaction_id
 * @property int|null $user_id
 * @property string|null $receiver_id
 * @property string|null $source_id
 * @property string|null $amount
 * @property string|null $t_type
 * @property string|null $transaction_type
 * @property string|null $comments
 * @property string|null $status
 * @property string|null $process_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $last4
 * @property-read \App\Models\User|null $receiver
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereProcessDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTransactionDetails whereUserId($value)
 */
	class UserTransactionDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Wallet
 *
 * @property-read \App\Models\User|null $from
 * @property-read mixed $fee_amount
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 */
	class Wallet extends \Eloquent {}
}

namespace App\Models{
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
 */
	class WithdrawalRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\faq
 *
 * @property int $id
 * @property string|null $question
 * @property string|null $answer
 * @property int $published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|faq whereUpdatedAt($value)
 */
	class faq extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\userfeedback
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\userfeedbackFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|userfeedback whereUserId($value)
 */
	class userfeedback extends \Eloquent {}
}

