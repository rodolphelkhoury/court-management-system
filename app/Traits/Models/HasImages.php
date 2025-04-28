<?php

namespace App\Traits\Models;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasImages
{
    /**
     * @return MorphOne
     */
    public function image(string $name = 'primary'): MorphOne
    {
        return $this->morphOne(Image::class, 'owner')->whereName($name);
    }

    /**
     * @return MorphMany
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'owner');
    }
}
