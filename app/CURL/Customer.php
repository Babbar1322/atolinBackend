<?php

namespace App\CURL;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Customer
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Customer.php");
                Log::info($response->json());
                Log::info("================================");
            }
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
        ])->post(config('cybrid.api').'/customers', $data);
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
        ])->get(config('cybrid.api')."/customers/{$id}");
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
