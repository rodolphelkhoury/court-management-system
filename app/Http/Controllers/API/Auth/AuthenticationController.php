<?php

namespace App\Http\Controllers\API\Auth;

use App\Domain\Otp\Actions\GenerateOtp;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginCustomerRequest;
use App\Http\Requests\API\Auth\RegisterCustomerRequest;
use App\Http\Requests\API\Auth\VerifyOtpRequest;
use App\Integrations\Twilio\Actions\SendSMS;
use App\Integrations\WhatsApp\Actions\SendWhatsAppMessage;
use App\Models\Customer;
use App\Models\Otp;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthenticationController extends Controller
{
    public function register(RegisterCustomerRequest $request, SendWhatsAppMessage $sendWhatsApp, GenerateOtp $generateOtp)
    {
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->password = bcrypt($request->password);
        $customer->save();
    
        $otp = $generateOtp->run(
            customer: $customer
        );

        $sendWhatsApp->sendTemplate(
            to: '961'.$customer->phone_number,
            otp: strval($otp)
        );
    
        return [
            'token' => $customer->createToken("customer_token")->plainTextToken,
            'customer' => $customer->refresh()
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
            'token' => $token->plainTextToken,
            'customer' => $customer
        ];
    }
    
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $customer = $request->user();

        $otpRecord = Otp::where('customer_id', $customer->id)
            ->where('otp', (int) $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            $otpRecord->delete();
            $otpRecord->expires_at = now();
            $customer->phone_number_verified_at = now();
            $otpRecord->save();
            $customer->save();
            $customer->refresh();
            return [
                'verified' => true
            ];
        }

        return [
            'verified' => false
        ];
    }
}
