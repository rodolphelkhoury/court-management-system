<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourtRequest;
use App\Models\Complex;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\SurfaceType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourtController extends Controller
{
    public function getCourts(Request $request)
    {
        $company = $request->user()->company()->first();
        $courts = Court::with(['court_type', 'surface_type'])
            ->whereHas('complex', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('Courts', [
            'courts' => $courts
        ]);
    }
    

    public function getCourt(Request $request, Court $court)
    {
        return $court;
    }

    public function getCourtReservations(Request $request, Court $court)
    {
        $reservations = $court->reservations()->with(['customer', 'section'])->get();
        return Inertia::render('Reservations', [
            'court' => $court,
            'reservations' => $reservations
        ]);
    }

    public function store(CourtRequest $request)
    {
        $court = Court::firstOrCreate($request->validated());
        
        return redirect()->route('court.index')->with('success', 'Court created successfully.');
    }

    public function update(CourtRequest $request, Court $court)
    {
        $court = Court::where('id', $court->id)->where('company_id', $request->user()->company_id)->firstOrFail();
        $court->update($request->validated());
        return redirect()->route('court.index')->with('success', 'Court updated successfully.');
    }

    public function getCreateCourtPage(Request $request)
    {
        return Inertia::render('CreateCourts', [
            'complexes' => Complex::where('company_id', $request->user()->company_id)->get(),
            'surface_types' => SurfaceType::with('court_types')->get(),
            'court_types' => CourtType::with('surface_types')->get()
        ]);
    }
}
