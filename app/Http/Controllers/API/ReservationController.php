<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Reservation\IndexReservationRequest;
use App\Http\Requests\API\Reservation\ShowReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index(IndexReservationRequest $request)
    {
        $customer = $request->user();
        $now = now();
    
        $query = Reservation::where('customer_id', $customer->id);
    
        $upcomingReservations = (clone $query)->whereRaw(
            "STR_TO_DATE(CONCAT(reservation_date, ' ', end_time), '%Y-%m-%d %H:%i:%s') > ?",
            [$now]
        )->get();
    
        $completedReservations = (clone $query)->whereRaw(
            "STR_TO_DATE(CONCAT(reservation_date, ' ', end_time), '%Y-%m-%d %H:%i:%s') <= ?",
            [$now]
        )->get();
    
        $reservations = [
            'upcoming' => $upcomingReservations,
            'completed' => $completedReservations,
            'status' => 200
        ];
    
        return response()->json(data: $reservations);
    }

    public function show(Reservation $reservation, ShowReservationRequest $request)
    {
        return response()->json(
            data: $reservation
        );
    }
}
