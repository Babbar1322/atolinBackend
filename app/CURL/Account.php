<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Account
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function getByUser($id)
    {
        $token = Auth::getToken('accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get('https://bank.sandbox.cybrid.app/api/accounts?customer_guid='.$id);
        return self::returnData($response);
    }

    public static function getById($id)
    {
        $token = Auth::getToken('accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/accounts/{$id}");
        return self::returnData($response);
    }

    public static function create($id)
    {
        $token = Auth::getToken('accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $getToken = Auth::getToken('accounts', 'execute');
        if ($getToken['status'] == false) {
            return $getToken;
        }
        $accounts = self::getByUser($id)['response']['total'];
        if ($accounts > 0) {
            return;
        }
        $data = [
            'type' => 'fiat',
            'customer_guid' => $id,
            'asset' => 'USD',
            'name' => 'Main Account'
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/accounts", $data);
        return self::returnData($response);
    }
}
