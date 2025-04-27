<?php

namespace App\Models;

use App\Traits\Models\HasImages;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasImages;

    protected $with = [
        'image',
        'images',
    ];

    protected $appends = [
        'image_url',
        'court_type_name',
        'surface_type_name',
    ];

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
        'reservation_duration',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'divisible' => 'boolean',
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

    public function getImageUrlAttribute()
    {
        return $this->image?->filepath;
    }

    public function getCourtTypeNameAttribute()
    {
        return $this->court_type?->name;
    }

    public function getSurfaceTypeNameAttribute()
    {
        return $this->surface_type?->name;
    }
}
