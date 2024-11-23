<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{

    public function getCreateReservationPage(Court $court, Request $request)
    {
        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }

        return Inertia::render('CreateReservation', [
            'court' => $court
        ]);
    }

    public function update(Court $court, Reservation $reservation, Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'nullable|exists:sections,id,court_id,' . $court->id,
            'customer_id' => 'required|exists:customers,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }
    
        if ($request->section_id) {
            $section = Section::where('court_id', $reservation->court_id)->where('id', $request->section_id)->firstOrFail();
            $hourly_rate = $section->hourly_rate;
        } else {
            $hourly_rate = $court->hourly_rate;
        }
    
        $total_price = $hourly_rate * $this->calculateDurationInHours(
            $request->start_time ?? $reservation->start_time, 
            $request->end_time ?? $reservation->end_time
        );

        $reservation->section_id = $validated['section_id'] ?? $reservation->section_id;
        $reservation->customer_id = $validated['customer_id'] ?? $reservation->customer_id;
        $reservation->reservation_date = $validated['reservation_date'] ?? $reservation->reservation_date;
        $reservation->start_time = $validated['start_time'] ?? $reservation->start_time;
        $reservation->end_time = $validated['end_time'] ?? $reservation->end_time;
        $reservation->total_price = $total_price;

        $reservation->save();
    }
    
    private function calculateDurationInHours($start_time, $end_time) {
        $start = Carbon::createFromFormat('H:i', $start_time);
        $end = Carbon::createFromFormat('H:i', $end_time);
        $differenceInMinutes = abs($end->diffInMinutes($start));
        $differenceInHours = $differenceInMinutes / 60;
        return $differenceInHours;
    }
}
