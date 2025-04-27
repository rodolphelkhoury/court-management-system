<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Get the authenticated customer profile
     */
    public function show()
    {
        // Return the authenticated customer
        $customer = Auth::user();
        return response()->json($customer);
    }

    /**
     * Update the authenticated customer profile
     */
    public function update(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();
        $customer->name = $request->input('name');
        $customer->save();
        
        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $customer
        ]);
    }
}