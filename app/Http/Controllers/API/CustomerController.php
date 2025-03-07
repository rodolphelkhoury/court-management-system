<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        return response()->json(
            $customer
        );
    }
}
