<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStripeTransaction extends Model
{
    use HasFactory;

    protected $table = 'user_stripe_transactions_details';

    protected $fillable = [
        'stripe_tid',
        'stripe_uid',
        'card_id',
        'amount',
        'currency',
        't_type',
        'request_at',
        'created_at' 
    ];
}
