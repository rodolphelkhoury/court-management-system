<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    protected $fillable = [
        'court_id',
        'section_id',
        'customer_id',
        'reservation_date',
        'start_time',
        'end_time',
        'total_price',
        'is_canceled',
        'is_no_show',
    ];

    public $with = ['section'];

    protected static function booted()
    {
        static::addGlobalScope('notCanceled', function (Builder $builder) {
            $builder->where('is_canceled', 0);  // Assuming '0' means not canceled
        });
    }

    public function customer()
    {
        return $this->belongsTo(CompanyCustomer::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}
