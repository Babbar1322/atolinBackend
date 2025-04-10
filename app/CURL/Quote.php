<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Quote
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Quote.php");
                Log::info($response->json());
                Log::info("================================");
            }
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function createQuote($id)
    {
        $data = [
            'type' => 'kyc',
            'method' => 'id_and_selfie',
            'customer_guid' => $id
        ];
        $token = Auth::getToken('identity_verifications', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post(config('cybrid.api').'/identity_verifications', $data);
        return self::returnData($response);
    }

    public static function getKYC($id)
    {
        $token = Auth::getToken('identity_verifications', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/identity_verifications/{$id}");
        return self::returnData($response);
    }

    public static function getVerifiedByUser($id) {
        $token = Auth::getToken('identity_verifications', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/identity_verifications?customer_guid={$id}&state=completed");
        return self::returnData($response);
    }
}
