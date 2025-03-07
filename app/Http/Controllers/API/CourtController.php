<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Court\IndexCourtRequest;
use App\Models\Court;

class CourtController extends Controller
{
    public function index(IndexCourtRequest $request)
    {
        $query = Court::query();

        $courts = $query->get();
        return response()->json(
            data: $courts,
        );
    }

    public function show(Court $court)
    {
        return response()->json(
            data: $court
        );
    }
}
