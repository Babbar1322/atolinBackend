<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KYC
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            // Log::info("KYC ERROR ----------------------------------");
            // Log::error($response->json());
            // Log::info("KYC ERROR ----------------------------------");
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function createKYC($id)
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
        ])->post('https://bank.sandbox.cybrid.app/api/identity_verifications', $data);
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
        ])->get("https://bank.sandbox.cybrid.app/api/identity_verifications/{$id}");
        return self::returnData($response);
    }

    public static function getVerifiedByUser($id)
    {
        $token = Auth::getToken('identity_verifications', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/identity_verifications?customer_guid={$id}&state=completed");
        return self::returnData($response);
    }

    public static function getAllByUser($id)
    {
        $token = Auth::getToken('identity_verifications', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/identity_verifications?customer_guid={$id}");
        return self::returnData($response);
    }

    public static function verifyBankAccount($userId, $account_id)
    {
        $data = [
            "type" => "bank_account",
            "method" => "account_ownership",
            "customer_guid" => $userId,
            "external_bank_account_guid" => $account_id,
            "expected_behaviours" => [
                "passed_immediately"
            ]
        ];
        $token = Auth::getToken('identity_verifications', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/identity_verifications", $data);
        return self::returnData($response);
    }
}
