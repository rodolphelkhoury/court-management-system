<?php

namespace App\Domain\Otp\Actions;

use App\Models\Customer;
use Carbon\Carbon;

final class GenerateOtp 
{
    public function run(Customer $customer)
    {
        $otp = random_int(10000, 99999);
    
        $customer->otps()->create([
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    
        return $otp;
    }
    
}