<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestMoney extends Model
{
    use HasFactory;

    protected $table = "user_request_money";

    protected $fillable = [
        'requester_user_id',
                'receiver_user_id',
                'amount',
                'currency',
                'comments',
                'created_at'
    ];

    public function user(){
        
    }
}
