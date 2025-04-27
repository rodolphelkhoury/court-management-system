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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthenticationController extends Controller
{
    public function register(RegisterCustomerRequest $request, SendWhatsAppMessage $sendWhatsApp, GenerateOtp $generateOtp)
    {
        // Check if the phone number already exists
        $existingCustomer = Customer::where('phone_number', $request->phone_number)->first();
        if ($existingCustomer) {
            throw ValidationException::withMessages([
                'phone_number' => ['This phone number is already registered in our system.'],
            ]);
        }
        
        if (empty($request->name) || strlen($request->name) < 2) {
            throw ValidationException::withMessages([
                'name' => ['Please provide a valid name (at least 2 characters).'],
            ]);
        }
        
        if (strlen($request->password) < 8) {
            throw ValidationException::withMessages([
                'password' => ['Password must be at least 8 characters long.'],
            ]);
        }
        
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->password = bcrypt($request->password);
        $customer->save();
    
        $otp = $generateOtp->run(
            customer: $customer
        );
    
        try {
            $sendWhatsApp->sendTemplate(
                to: $customer->phone_number,
                otp: strval($otp)
            );
        } catch (\Exception $e) {
            info('Failed to send WhatsApp OTP: ' . $e->getMessage());
        }
    
        return [
            'token' => $customer->createToken("customer_token")->plainTextToken,
            'customer' => $customer->refresh()
        ];
    }
    
    public function login(LoginCustomerRequest $request)
    {
        $customer = Customer::where('phone_number', $request->phone_number)->first();
        
        if (!$customer) {
            throw ValidationException::withMessages([
                'phone_number' => ['This phone number does not exist in our records.'],
            ]);
        }
        
        if (!Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
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
    
        if (!is_numeric($request->code) || strlen($request->code) != 6) {
            throw ValidationException::withMessages([
                'code' => ['The verification code must be a 6-digit number.'],
            ]);
        }
    
        if ($customer->phone_number_verified_at) {
            return [
                'verified' => true,
                'message' => 'Phone number is already verified.'
            ];
        }
    
        $otpRecord = Otp::where('customer_id', $customer->id)
            ->where('otp', (int) $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    
        if (!$otpRecord) {
            // Check if there's an expired OTP with this code
            $expiredOtp = Otp::where('customer_id', $customer->id)
                ->where('otp', (int) $request->code)
                ->where('expires_at', '<=', Carbon::now())
                ->first();
                
            if ($expiredOtp) {
                throw ValidationException::withMessages([
                    'code' => ['This verification code has expired. Please request a new one.'],
                ]);
            }
            
            $anyOtp = Otp::where('customer_id', $customer->id)->first();
            if (!$anyOtp) {
                throw ValidationException::withMessages([
                    'code' => ['No verification code was sent. Please request a new code.'],
                ]);
            }
    
            throw ValidationException::withMessages([
                'code' => ['Invalid verification code. Please try again.'],
            ]);
        }
    
        $otpRecord->delete();
        $customer->phone_number_verified_at = now();
        $customer->save();
        $customer->refresh();
        
        return [
            'verified' => true,
            'message' => 'Phone number verified successfully.'
        ];
    }

    public function resendOtp(Request $request, SendWhatsAppMessage $sendWhatsApp, GenerateOtp $generateOtp)
    {
        $customer = $request->user();
        
        if ($customer->phone_number_verified_at) {
            return response()->json([
                'message' => 'Phone number is already verified.'
            ], 400);
        }
        
        $latestOtp = Otp::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($latestOtp) {
            Otp::where('customer_id', $customer->id)->delete();
        }
        
        $otp = $generateOtp->run(
            customer: $customer
        );
        
        try {
            $sendWhatsApp->sendTemplate(
                to: $customer->phone_number,
                otp: strval($otp)
            );
            
            return response()->json([
                'message' => 'Verification code has been sent to your phone number.'
            ]);
        } catch (\Exception $e) {
            info('Failed to send WhatsApp OTP: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to send verification code. Please try again later.'
            ], 500);
        }
    }
}
