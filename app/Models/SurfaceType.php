<?php

namespace App\Models;

use App\Domain\SurfaceType\Enums\SurfaceTypeName;
use Illuminate\Database\Eloquent\Model;

class SurfaceType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function casts()
    {
        return [
            'name' => SurfaceTypeName::class
        ];
    }

    public function court_types()
    {
        return $this->belongsToMany(CourtType::class, 'court_type_surface_type', 'surface_type_id', 'court_type_id');
    }
    
    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
