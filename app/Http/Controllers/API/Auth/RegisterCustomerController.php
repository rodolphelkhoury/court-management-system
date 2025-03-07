<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterCustomerRequest;
use App\Models\Customer;

class RegisterCustomerController extends Controller
{
    public function register(RegisterCustomerRequest $request)
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->password = bcrypt($request->password);
        $customer->save();

        return ['token' => $customer->createToken("first_token")];
    }
}
