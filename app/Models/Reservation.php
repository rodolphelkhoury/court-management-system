<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
