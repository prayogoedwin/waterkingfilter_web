<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonteService
{
    public function sendMessage(string $target, string $message): array
    {
        $apiKey = config('fonte.api_key');

        if (blank($apiKey)) {
            return [
                'success' => false,
                'message' => 'FONTE_API_KEY belum diatur',
                'response' => null,
            ];
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization' => $apiKey,
                ])
                ->asForm()
                ->post(config('fonte.base_url'), [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $json = $response->json();
            $providerSuccess = is_array($json) ? ($json['status'] ?? false) : false;

            return [
                'success' => $response->successful() && (bool) $providerSuccess,
                'message' => is_array($json) ? ($json['message'] ?? 'Unknown provider response') : 'Invalid provider response',
                'response' => [
                    'http_status' => $response->status(),
                    'body' => $json,
                    'raw' => $response->body(),
                ],
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
                'response' => null,
            ];
        }
    }
}

