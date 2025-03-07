<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = [
        'complex_id',
        'court_type_id',
        'surface_type_id',
        'name',
        'description',
        'hourly_rate',
        'divisible',
        'max_divisions',
        'opening_time',
        'closing_time',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function complex()
    {
        return $this->belongsTo(Complex::class);
    }

    public function court_type()
    {
        return $this->belongsTo(CourtType::class);
    }

    public function surface_type()
    {
        return $this->belongsTo(SurfaceType::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
