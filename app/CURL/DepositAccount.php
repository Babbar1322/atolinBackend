<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DepositAccount
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in DepositAccount.php");
                Log::info($response->json());
                Log::info("================================");
            }
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function getByUser($id)
    {
        $token = Auth::getToken('deposit_bank_accounts', 'read');
        if ($token['status'] == false) {
            return self::returnData($token);
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api').'/deposit_bank_accounts?customer_guid='.$id);
        return self::returnData($response);
    }

    public static function getById($id)
    {
        $token = Auth::getToken('deposit_bank_accounts', 'read');
        if ($token['status'] == false) {
            return self::returnData($token);
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/deposit_bank_accounts/{$id}");
        return self::returnData($response);
    }

    public static function create($id, $account_id)
    {
        $token = Auth::getToken('deposit_bank_accounts', 'execute');
        if ($token['status'] == false) {
            return self::returnData($token);
        }
        $accounts = self::getByUser($id)['response']['total'];
        if ($accounts > 0) {
            return;
        }
        $data = [
            'type' => 'main',
            'customer_guid' => $id,
            'account_guid' => $account_id,
            'asset' => 'USD',
            'name' => 'Main Account'
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post(config('cybrid.api')."/deposit_bank_accounts", $data);
        return self::returnData($response);
    }
}
