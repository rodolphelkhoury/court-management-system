<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complex extends Model
{
    protected $table = 'complexes';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'line1',
        'line2',
        'city',
        'country',
        'latitude',
        'longitude',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
