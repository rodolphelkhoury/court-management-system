<?php

namespace App\Http\Controllers;

use App\Models\CompanyCustomer;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function get(Request $request)
    {
        return CompanyCustomer::where('company_id', $request->user()->company_id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);
    
        $company_id = $request->user()->company_id;
    
        $customer = Customer::firstOrCreate([
            'phone_number' => $request->phone_number
        ]);

        CompanyCustomer::updateOrCreate([
            'company_id' => $company_id,
            'customer_id' => $customer->id
        ], [
            'name' => $request->name
        ]);
    }    
}
