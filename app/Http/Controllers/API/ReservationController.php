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
        )->orderBy('reservation_date')
         ->orderBy('start_time')
         ->get();
    
        $completedReservations = (clone $query)->whereRaw(
            "STR_TO_DATE(CONCAT(reservation_date, ' ', end_time), '%Y-%m-%d %H:%i:%s') <= ?",
            [$now]
        )->orderByDesc('reservation_date')
         ->orderByDesc('end_time')
         ->get();
    
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
            'reservation_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
    
        // Ensure time is in HH:mm:ss format (only append :00 if seconds aren't present)
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $validated['start_time'])) {
            $validated['start_time'] .= ':00';
        }
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $validated['end_time'])) {
            $validated['end_time'] .= ':00';
        }
    
        // Check for overlapping reservation
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
    
        // Calculate total price based on hourly rate and time difference
        $start = Carbon::createFromFormat('H:i:s', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i:s', $validated['end_time']);
        $hours = $end->diffInMinutes($start) / 60;
        $total_price = $court->hourly_rate * $hours;
    
        // Save reservation
        $reservation = new Reservation();
        $reservation->court_id = $court->id;
        $reservation->section_id = $validated['section_id'] ?? null;
        $reservation->customer_id = $request->user()->id;
        $reservation->reservation_date = $validated['reservation_date'];
        $reservation->start_time = $validated['start_time'];
        $reservation->end_time = $validated['end_time'];
        $reservation->total_price = abs($total_price);
        $reservation->save();
    
        $reservationDate = $reservation->reservation_date;
        $today = Carbon::today('Asia/Beirut');
        $now = Carbon::now('Asia/Beirut');
    
        // If date is in the past, return empty array
        if (Carbon::parse($reservationDate)->isBefore($today)) {
            return response()->json([
                'available_reservations' => []
            ]);
        }
    
        $durationInMinutes = (int) ($court->reservation_duration * 60);
        
        $openingTime = Carbon::createFromFormat('H:i:s', $court->opening_time, 'Asia/Beirut');
        $closingTime = Carbon::createFromFormat('H:i:s', $court->closing_time, 'Asia/Beirut');
    
        $existingReservations = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $reservationDate)
            ->get()
            ->map(function ($res) {
                return [
                    'start' => Carbon::createFromFormat('H:i:s', $res->start_time, 'Asia/Beirut'),
                    'end' => Carbon::createFromFormat('H:i:s', $res->end_time, 'Asia/Beirut'),
                ];
            });
    
        $availableSlots = [];
        $currentStart = clone $openingTime;
    
        while ($currentStart->copy()->addMinutes($durationInMinutes)->lte($closingTime)) {
            $currentEnd = $currentStart->copy()->addMinutes($durationInMinutes);
    
            // Check if this time slot overlaps with existing reservations
            $overlaps = $existingReservations->contains(function ($res) use ($currentStart, $currentEnd) {
                return $currentStart->lt($res['end']) && $currentEnd->gt($res['start']);
            });
    
            // Check if the slot is in the past for today's reservations using Beirut time
            $isPastTimeForToday = Carbon::parse($reservationDate, 'Asia/Beirut')->isSameDay($today) && 
                Carbon::createFromFormat('H:i:s', $currentStart->format('H:i:s'), 'Asia/Beirut')->isBefore($now);
    
            if (!$overlaps && !$isPastTimeForToday) {
                $availableSlots[] = [
                    'start_time' => $currentStart->format('H:i:s'),
                    'end_time' => $currentEnd->format('H:i:s'),
                ];
            }
    
            $currentStart->addMinutes($durationInMinutes);
        }
    
        return response()->json([
            'available_reservations' => $availableSlots
        ]);
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
