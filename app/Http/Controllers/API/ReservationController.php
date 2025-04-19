<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Reservation\IndexReservationRequest;
use App\Http\Requests\API\Reservation\ShowReservationRequest;
use App\Models\Court;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function store(Court $court, Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:company_customer,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';
        $existing_reservation = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $validated['reservation_date'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->first();


        if ($existing_reservation) {
            return back()->withErrors(['error' => 'The court is already reserved at this time.']);
        }
        $hourly_rate = $court->hourly_rate;

        $total_price = $hourly_rate * $this->calculateDurationInHours(
            $request->start_time,
            $request->end_time
        );

        $reservation = new Reservation();
        $reservation->court_id = $court->id;
        $reservation->section_id = $validated['section_id'] ?? null;
        $reservation->customer_id = $validated['customer_id'];
        $reservation->reservation_date = $validated['reservation_date'];
        $reservation->start_time = $validated['start_time'];
        $reservation->end_time = $validated['end_time'];
        $reservation->total_price = $total_price;

        $reservation->save();

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

    private function calculateDurationInHours($start_time, $end_time)
    {
        $start = Carbon::createFromFormat('H:i', $start_time);
        $end = Carbon::createFromFormat('H:i', $end_time);
        $differenceInMinutes = abs($end->diffInMinutes($start));
        $differenceInHours = $differenceInMinutes / 60;
        return $differenceInHours;
    }
}
