<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function get(Request $request)
    {
        return Customer::where('company_id', $request->user()->company_id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);
    
        $company_id = $request->user()->company_id;
    
        Customer::firstOrCreate([
            'company_id' => $company_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);
    }    
}
