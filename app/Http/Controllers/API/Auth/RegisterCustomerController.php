<?php

namespace App\Http\Controllers\API\Auth;

use App\Domain\Otp\Actions\GenerateOtp;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginCustomerRequest;
use App\Http\Requests\API\Auth\RegisterCustomerRequest;
use App\Http\Requests\API\Auth\VerifyOtpRequest;
use App\Integrations\Twilio\Actions\SendSMS;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class RegisterCustomerController extends Controller
{
    public function register(RegisterCustomerRequest $request, SendSMS $sendSMS, GenerateOtp $generateOtp)
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->password = bcrypt($request->password);
        $customer->save();
    
        $otp = $generateOtp->run(
            customer: $customer
        );
    
        $sendSMS->run(
            to: $customer->phone_number,
            body: "Your verification code is: {$otp}. It will expire in 5 minutes."
        );
    
        return [
            'token' => $customer->createToken("customer_token")->plainTextToken,
            'customer' => $customer
        ];
    }    

    public function login(LoginCustomerRequest $request)
    {
        $customer = Customer::where('phone_number', $request->phone_number)->first();
    
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $customer->createToken('customer_token');
        return [
            'token' => $token,
            'customer' => $customer
        ];
    }
    
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $customer = $request->auth();
    }
}
