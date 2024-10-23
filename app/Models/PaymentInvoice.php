<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @mixin \Eloquent
 */
class PaymentInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_holder_name',
        'routing_number',
        'account_type',
        'contact_name',
        'amount',
        'invoice_id',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'email',
        'phone',
    ];
}
