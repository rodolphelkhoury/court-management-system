<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'disk',
        'name',
        'mimetype',
        'filepath',
        'filesize',
        'width',
        'height',
        'owner_id',
        'owner_type',
        'label',
    ];

    /**
     * Retrieve the owner of this resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }
}
