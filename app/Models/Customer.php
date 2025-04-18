<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'phone_number',
        'password',
        'phone_number_verified_at'
    ];

    protected $hidden = [
        'password',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_customer');
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function validOtps()
    {
        return $this->hasMany(Otp::class)->where('expires_at', '>', now());
    }    

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
