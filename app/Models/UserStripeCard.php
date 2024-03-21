<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStripeCard extends Model
{
    use HasFactory;

    protected $table = "user_stripe_carddetails";

    protected $fillable = [
        'priority_id',
        'card_id',
        'fingerprint',
        'last4',
        'brand',
        'country',
        'exp_month',
        'exp_year',
        'created_at'
    ];

}
