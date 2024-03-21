<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','amount','status','account_holder_name','bank_name','account_number','ifsc','phone_no', 'bank_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->select('name','contact');
    }
}
