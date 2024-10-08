<?php

namespace App\CURL;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class Customer
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (!empty($response->json()['errorMessage'])) {
                return ['status' => false, 'response' => $response->json()['errorMessage']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }
    public static function createCustomer()
    {
        $data = [
            'type' => 'individual',
        ];
        $token = Auth::getToken('customers', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post('https://bank.sandbox.cybrid.app/api/customers', $data);
        // \Log::info($response);
        return self::returnData($response);
    }

    public static function getCustomer($id)
    {
        $token = Auth::getToken('customers', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/customers/{$id}");
        return self::returnData($response);
    }

    // public static function deleteCustomer($id)
    // {
    //     $response = Http::withHeaders([
    //         'PromiseMode' => 'NEVER',
    //         'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
    //     ])->delete("https://api.sandbox.prioritypassport.com/v1/customer/id/{$id}");

    //     return self::returnData($response);
    // }
}
