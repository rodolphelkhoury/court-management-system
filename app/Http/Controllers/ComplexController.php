<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplexRequest;
use App\Http\Requests\CreateComplexRequest;
use App\Models\Complex;
use App\Models\Court;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComplexController extends Controller
{
    public function get(Request $request)
    {
        return Inertia::render("Complexes", [
            "complexes" => Complex::where("company_id", $request->user()->company_id)->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function show($id, Request $request)
    {
        $complex = Complex::where('company_id', $request->user()->company_id)->with(['company', 'courts'])->findOrFail($id);

        return Inertia::render('Complex', [
            'complex' => $complex
        ]);
    }

    public function getComplexCourts(Complex $complex, Request $request)
    {
        $company = $request->user()->company()->first();
        $courts = Court::with(['court_type', 'surface_type'])->where('complex_id', $complex->id)
            ->whereHas('complex', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('Courts', [
            'courts' => $courts
        ]);
    }

    public function getCreateComplexPage()
    {
        return Inertia::render("CreateComplexes");
    }

    public function store(CreateComplexRequest $request)
    {
        $complex = Complex::firstOrCreate(array_merge($request->validated(), ['company_id' => $request->user()->company_id]));
        
        return redirect()->route('complex.index')->with('success', 'Complex created successfully.');
    }

    public function update(ComplexRequest $request, Complex $complex)
    {
        $complex = Complex::where('id', $complex->id)->where('company_id', $request->user()->company_id)->firstOrFail();
        $complex->update($request->validated());
        return redirect()->route('complex.index')->with('success', 'Complex updated successfully.');
    }
}
