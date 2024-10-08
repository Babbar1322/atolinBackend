<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class Workflow
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

    /**
     * Create Workflow for External Account
     * @param string $customer_id
     * @return array
     */
    public static function createWorkflow($customer_id)
    {
        $data = [
            'type' => "plaid",
            'kind' => 'link_token_create',
            'language' => 'en',
            'link_customization_name' => 'default',
            'customer_guid' => $customer_id,
        ];
        $token = Auth::getToken('workflows', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/workflows", $data);
        // \Log::info($response);

        return self::returnData($response);
    }

    /**
     * Get Workflow by customer_id
     * @param string $customer_id
     * @return array
     */
    public static function getByCustomer($customer_id)
    {
        $token = Auth::getToken('workflows', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .  $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/workflows?customer_guid=$customer_id");
        // \Log::info($response);

        return self::returnData($response);
    }

    public static function getOne($guid)
    {
        $token = Auth::getToken('workflows', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .  $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/workflows/$guid");
        // \Log::info($response);

        return self::returnData($response);
    }

    public static function getUpdateToken($customer_id, $external_account_id) {
        $token = Auth::getToken('workflows', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $data = [
            "type" => "plaid",
            "kind" => "link_token_update",
            "language" => "en",
            "link_customization_name" => "default",
            "external_bank_account_guid" => $external_account_id,
            "customer_guid" => $customer_id
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .  $token['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/workflows", $data);
        // \Log::info($response);

        return self::returnData($response);
    }
}
