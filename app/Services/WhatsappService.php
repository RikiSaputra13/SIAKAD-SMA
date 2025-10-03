<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL');
        $this->apiKey = env('WHATSAPP_API_KEY');
    }

    public function sendMessage($phone, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->asForm()->post($this->apiUrl, [
                'target'  => $phone,   // nomor WA orang tua, format 628xxx
                'message' => $message,
            ]);

            // simpan log request & response untuk debug
            Log::info('WA Request', [
                'to' => $phone,
                'message' => $message,
                'response' => $response->json(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('WA API gagal', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WA Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
