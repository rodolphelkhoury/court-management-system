<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone_number'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
