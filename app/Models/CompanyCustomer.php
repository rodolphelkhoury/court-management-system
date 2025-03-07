<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyCustomer extends Pivot
{
    protected $table = "company_customer";

    protected $with = ['customer'];

    protected $fillable = [
        'company_id',
        'customer_id',
        'name'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
