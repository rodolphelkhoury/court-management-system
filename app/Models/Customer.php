<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'phone_number'
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_customer');
    }
}
