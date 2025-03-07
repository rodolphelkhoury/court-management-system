<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Reservation\IndexReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index(IndexReservationRequest $request)
    {
        $query = Reservation::query();
        $now = now();
        $upcomingReservations = $query->where('end_date', '>', $now)->get();
        $completedReservations = $query->where('end_date', '<=', $now)->get();
    
        $reservations = [
            'upcoming' => $upcomingReservations,
            'completed' => $completedReservations,
        ];

        return response()->json(
            data: $reservations
        );
    }
}
