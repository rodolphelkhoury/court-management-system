<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'website'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
