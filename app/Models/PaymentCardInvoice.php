<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class PaymentCardInvoice extends Model
{
    use HasFactory;
}
