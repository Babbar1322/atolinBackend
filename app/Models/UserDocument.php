<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'url',
        'number',
        'unique_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The services that belong to the user.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    /**
     * The services that belong to the user.
     */
    public function document()
    {
        return $this->belongsTo('App\Models\Document','document_id','id');
    }
}
