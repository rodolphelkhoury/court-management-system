<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplexRequest;
use App\Models\Complex;

class ComplexController extends Controller
{
    public function store(ComplexRequest $request)
    {
        $complex = Complex::firstOrCreate($request->validated());
        
        return redirect()->route('complexes.index')->with('success', 'Complex created successfully.');
    }

    public function update(ComplexRequest $request, Complex $complex)
    {
        $complex = Complex::where('id', $complex->id)->where('company_id', $request->user()->company_id)->firstOrFail();
        $complex->update($request->validated());
        return redirect()->route('complexes.index')->with('success', 'Complex updated successfully.');
    }
}
