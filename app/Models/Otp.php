<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public $fillable = [
        'expires_at',
        'customer_id',
        'otp'
    ];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
