<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NotificationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'message',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->select('id','name','contact');
    }
    
}
