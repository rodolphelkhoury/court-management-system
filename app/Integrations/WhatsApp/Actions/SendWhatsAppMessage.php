<?php

namespace App\Integrations\WhatsApp\Actions;

use Illuminate\Support\Facades\Http;

class SendWhatsAppMessage
{
    private string $accessToken;
    private string $phoneNumberId;

    public function __construct()
    {
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
    }

    /**
     * Send a WhatsApp template message
     * 
     * @param string $to Recipient's phone number with country code (e.g. 96171752143)
     * @param string $templateName The name of the template to use
     * @param string $languageCode The language code for the template (e.g. en_US)
     * @return void
     */
    public function sendTemplate(string $to, string $otp, string $languageCode = 'en_US'): void
    {
        $url = "https://graph.facebook.com/v22.0/{$this->phoneNumberId}/messages";
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => 'verify_otp',
                'language' => [
                    'code' => $languageCode
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $otp
                            ]
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => 0,
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $otp
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    
        if ($response->successful()) {
            info('WhatsApp message sent successfully: ' . $response->body());
        } else {
            info('Error sending WhatsApp message: ' . $response->status() . ' - ' . $response->body());
        }
    }
}