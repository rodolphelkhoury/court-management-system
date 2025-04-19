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
        $query = Court::query()->where('name', 'like', '%' . $request->search . '%');

        $courts = $query->get();
        return response()->json(
            data: [
                "courts" => $courts,
                "status" => 200,
            ],
        );
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
    
        if (Carbon::parse($reservationDate)->isBefore(Carbon::today())) {
            return response()->json([
                'available_reservations' => []
            ]);
        }
    
        $durationInMinutes = (int) ($court->reservation_duration * 60);
        
        $openingTime = Carbon::createFromFormat('H:i:s', $court->opening_time);
        $closingTime = Carbon::createFromFormat('H:i:s', $court->closing_time);
    
        $existingReservations = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $reservationDate)
            ->get()
            ->map(function ($res) {
                return [
                    'start' => Carbon::createFromFormat('H:i:s', $res->start_time),
                    'end' => Carbon::createFromFormat('H:i:s', $res->end_time),
                ];
            });
    
        $availableSlots = [];
        $currentStart = clone $openingTime;
    
        while ($currentStart->copy()->addMinutes($durationInMinutes)->lte($closingTime)) {
            $currentEnd = $currentStart->copy()->addMinutes($durationInMinutes);
    
            $overlaps = $existingReservations->contains(function ($res) use ($currentStart, $currentEnd) {
                return $currentStart->lt($res['end']) && $currentEnd->gt($res['start']);
            });
    
            if (!$overlaps) {
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
