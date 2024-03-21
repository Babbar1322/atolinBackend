<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
