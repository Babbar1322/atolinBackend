<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'user_id',
        'destination',
        'status',
    ];
}
