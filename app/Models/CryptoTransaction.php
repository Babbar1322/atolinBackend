<?php

namespace App\Models;

use App\Encryption\Encryption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::retrieved(function ($cryptoTransaction) {
            $cryptoTransaction->decryptField();
        });
    }

    public function decryptField()
    {
        // Decrypt the 'to' field (adjust this based on your encryption logic)
        $this->to = Encryption::decrypt($this->to);
        $this->from = Encryption::decrypt($this->from);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
