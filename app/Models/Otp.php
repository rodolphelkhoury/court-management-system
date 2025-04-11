<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
