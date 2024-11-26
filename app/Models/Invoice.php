<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'reservation_id', 
        'customer_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $with = ['customer', 'reservation.court'];

    /**
     * Get the reservation associated with the invoice.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the customer associated with the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Check if the invoice is overdue.
     */
    public function isOverdue()
    {
        return $this->status == 'unpaid' && $this->due_date < now();
    }

    /**
     * Mark the invoice as paid.
     */
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }
}
