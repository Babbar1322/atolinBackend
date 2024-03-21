<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransactionDetails extends Model
{
    use HasFactory;

    protected $table = "user_transaction_details";

    protected $fillable = [
        'transaction_id',
        'user_id',
        'source_id',
        'amount',
        'receiver_id',
        't_type',
        'comments',
        'last4',
        'status',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id')->select('id','name','contact');
    }
}
