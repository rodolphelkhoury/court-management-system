<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SectionController extends Controller
{

    public function get(Court $court, Request $request)
    {

        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }
        return Section::where('court_id', $court->id)->get();
    }

    public function store(Court $court, Request $request)
    {
        $complex = $court->complex()->firstOrFail();
        if ($complex->company_id != $request->user()->company_id) {
            abort(401, "Unauthorized");
        }
        $validatedData = array_merge($request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'hourly_rate' => 'required|numeric|min:0',
        ]), ['court_id' => $court->id]);

        Section::create($validatedData);
    }
}
