<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Court\IndexCourtRequest;
use App\Models\Court;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index(IndexCourtRequest $request)
    {
        $searchTerm = trim($request->search);
        
        $query = Court::query()
            ->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name', 'like', '%' . str_replace(' ', '%', $searchTerm) . '%');
            })
            ->orderByRaw("
                CASE 
                    WHEN name LIKE ? THEN 0 
                    WHEN name LIKE ? THEN 1 
                    ELSE 2 
                END
            ", ["$searchTerm%", "%$searchTerm%"]);
    
        $courts = $query->get();
        
        return response()->json([
            "courts" => $courts,
            "status" => 200,
        ]);
    }

    public function show(Court $court)
    {
        return response()->json(
            data: $court
        );
    }

    public function getAvailableReservations(Court $court, Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date',
        ]);
    
        $reservationDate = $validated['reservation_date'];
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
    
    
}
