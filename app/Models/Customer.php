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

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_customer');
    }
}
