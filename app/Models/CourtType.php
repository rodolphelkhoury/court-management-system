<?php

namespace App\Models;

use App\Domain\CourtType\Enums\CourtTypeName;
use Illuminate\Database\Eloquent\Model;

class CourtType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function casts()
    {
        return [
            'name' => CourtTypeName::class
        ];
    }

    public function surface_types()
    {
        return $this->belongsToMany(SurfaceType::class, 'court_type_surface_type', 'court_type_id', 'surface_type_id');
    }
    
    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
