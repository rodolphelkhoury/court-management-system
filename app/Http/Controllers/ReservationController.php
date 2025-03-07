<?php

namespace App\Http\Controllers;

use App\Models\CompanyCustomer;
use App\Models\Court;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{

    public function get(Reservation $reservation, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }

        return Inertia::render('Reservation', ['reservation' => $reservation->load('customer', 'court', 'section')]);
    }

    public function getCreateReservationPage(Court $court, Request $request)
    {
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }

        return Inertia::render('CreateReservation', [
            'court' => $court,
            'sections' => $court->sections()->get(),
            'customers' => CompanyCustomer::where('company_id', $user->company_id)->get()
        ]);
    }

    public function update(Court $court, Reservation $reservation, Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'nullable|exists:sections,id,court_id,' . $court->id,
            'customer_id' => 'required|exists:company_customer,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        $openingTime = Carbon::createFromFormat('H:i:s', $court->opening_time);
        $closingTime = Carbon::createFromFormat('H:i:s', $court->closing_time);
    
        if ($startTime->lt($openingTime) || $endTime->gt($closingTime)) {
            abort(400, "The court will be closed at this time.");
        }

        $validated['start_time'] = $validated['start_time'] . ':00';
        $validated['end_time'] = $validated['end_time'] . ':00';
        $existing_reservation = Reservation::where('court_id', $court->id)->where('id', '!=', $reservation->id)
            ->where('reservation_date', $validated['reservation_date'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->first();

        if ($request->section_id) {
            if ($existing_reservation && is_null($existing_reservation->section_id)) {
                abort(400, "The court is already reserved at this time.");
            }

            if ($existing_reservation && $existing_reservation->section_id == $request->section_id) {
                abort(400,'This section is already reserved at this time.');
            }

            $section = Section::where('court_id', $court->id)->where('id', $request->section_id)->firstOrFail();
            $hourly_rate = $section->hourly_rate;
        } else {
            if ($existing_reservation) {
                abort(400, 'The court is already reserved at this time.');
            }
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

    public function store(Court $court, Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'nullable|exists:sections,id,court_id,' . $court->id,
            'customer_id' => 'required|exists:company_customer,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);
        $openingTime = Carbon::createFromFormat('H:i:s', $court->opening_time);
        $closingTime = Carbon::createFromFormat('H:i:s', $court->closing_time);
    
        if ($startTime->lt($openingTime) || $endTime->gt($closingTime)) {
            return back()->withErrors(['message' => 'The court will be closed at this time.']);
        }

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


        if ($request->section_id) {
            if ($existing_reservation && is_null($existing_reservation->section_id)) {
                return back()->withErrors(['message' => 'The court is already reserved at this time.']);
            }
            if ($existing_reservation && $existing_reservation->section_id == $request->section_id) {
                return back()->withErrors(['message' => 'This section is already reserved at this time.']);
            }

            $section = Section::where('court_id', $court->id)->where('id', $request->section_id)->firstOrFail();
            $hourly_rate = $section->hourly_rate;
        } else {
            if ($existing_reservation) {
                return back()->withErrors(['message' => 'The court is already reserved at this time.']);
            }
            $hourly_rate = $court->hourly_rate;
        }

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
        return redirect()->route('court.get.reservations', ['court' => $court->id])->with('success', 'Reservation created successfully.');
    }

    public function updateIsCanceledStatus(Reservation $reservation, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }

        $request->validate([
            'is_canceled' => 'required|boolean',
        ]);

        $reservation->is_canceled = $request->is_canceled;
        $reservation->save();
    }

    public function updateIsNoShowStatus(Reservation $reservation, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }

        $request->validate([
            'is_no_show' => 'required|boolean',
        ]);

        $reservation->is_no_show = $request->is_no_show;
        $reservation->save();
    }

    public function getInvoice(Reservation $reservation, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
    
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }
    
        return Inertia::render('Invoice', [
            'invoice' => Invoice::firstOrCreate(
                ['reservation_id' => $reservation->id],
                [
                    'customer_id' => $reservation->customer_id,
                    'amount' => $reservation->total_price,
                    'status' => 'unpaid',
                    'due_date' => Carbon::parse($reservation->end_time)->addDays(7),
                ]
            )
        ]);
        
    }

    public function updateInvoiceStatus(Reservation $reservation, Invoice $invoice, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }

        $request->validate([
            'status' => 'required|string|in:paid,unpaid',
        ]);

        $invoice->status = $request->status;
        if ($invoice->status == 'paid') {
            $invoice->paid_at = now();
        } else {
            $invoice->paid_at = null;
        }
        $invoice->save();
        return response()->json(['status' => $invoice->status, 'paid_at' => $invoice->paid_at]);
    }

    public function generatePdf(Reservation $reservation, Invoice $invoice, Request $request)
    {
        $court = $reservation->court()->firstOrFail();
        $complex = $court->complex()->firstOrFail();
        $user = $request->user();
    
        if ($complex->company_id != $user->company_id) {
            abort(401, "Unauthorized");
        }
    
        $pdf = Pdf::loadView('invoice', [
            'invoice' => $invoice, 
            'reservation' => $reservation,
        ]);
    
        return $pdf->stream("invoice" . $invoice->id . ".pdf");
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
