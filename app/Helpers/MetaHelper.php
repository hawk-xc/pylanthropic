<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

if (!function_exists('sendMetaCAPI')) {
    /**
     * Kirim event ke Facebook Conversion API (Meta CAPI)
     */
    function sendMetaCAPI(
        string $invoice_number,
        string $eventUrl,
        ?string $ph,
        ?string $ip_address,
        ?string $user_agent,
        ?string $fbc,
        ?string $fbp,
        string $donatur_id,
        int|float $nominal_final,
        ?string $program_title = null
    ): bool {
        $payload = [
            'data' => [[
                'event_name'       => 'Donate',
                'event_time'       => (int) now()->timestamp,
                'event_id'         => $invoice_number,
                'action_source'    => 'website',
                'event_source_url' => $eventUrl,

                'user_data' => array_filter([
                    'ph'                  => $ph,
                    'client_ip_address'   => $ip_address,
                    'client_user_agent'   => $user_agent,
                    'fbc'                 => $fbc,
                    'fbp'                 => $fbp,
                    'external_id'         => hash('sha256', $donatur_id),
                ]),

                'custom_data' => [
                    'currency'     => 'IDR',
                    'value'        => $nominal_final,
                    'content_name' => $program_title,
                ],
            ]],

            'access_token' => env('TOKEN_FB_CAPI'),
        ];

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->timeout(8)
                ->retry(2, 200)
                ->post('https://graph.facebook.com/v20.0/1278491429470122/events', $payload)
                ->throw();

            Log::info('Facebook CAPI response', [
                'invoice' => $invoice_number,
                'status'  => $response->status(),
                'body'    => $response->json(),
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Facebook CAPI error', [
                'invoice' => $invoice_number,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
