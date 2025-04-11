<?php

namespace App\Integrations\Twilio\Actions;

use Twilio\Rest\Client;

class SendSMS {
    public function run(
        string $to,
        string $body
    ) {
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($to, ['from' => $twilio_number, 'body' => $body]);
    }
}