<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Workflow
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Workflow.php");
                Log::info($response->json());
                Log::info("================================");
            }
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
    public static function createWorkflow($customer_id, $platform)
    {
        $data = [
            'type' => "plaid",
            'kind' => 'link_token_create',
            'language' => 'en',
            'link_customization_name' => 'default',
            'customer_guid' => $customer_id,
//            'redirect_uri' => 'https://admin.atolin.us/redirect',
//            'android_package_name' => 'com.app.atolin',
        ];
        if ($platform == 'ios') {
            $data['redirect_uri'] = 'https://admin.atolin.us/redirect';
        } elseif ($platform == 'android') {
            $data['android_package_name'] = 'com.app.atolin';
        }
        $token = Auth::getToken('workflows', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post(config('cybrid.api')."/workflows", $data);
//         \Log::info("Create Workflow");
//         \Log::info($response);
//        \Log::info("######################################################");

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
        ])->get(config('cybrid.api')."/workflows?customer_guid=$customer_id");
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
        ])->get(config('cybrid.api')."/workflows/$guid");
        // \Log::info("Get One");
        // \Log::info($response);
        // \Log::info("######################################################");

        return self::returnData($response);
    }

    public static function getUpdateToken($customer_id, $external_account_id, $platform = 'ios') {
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
        if ($platform == 'ios') {
            $data['redirect_uri'] = 'https://admin.atolin.us/redirect';
        } elseif ($platform == 'android') {
            $data['android_package_name'] = 'com.app.atolin';
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' .  $token['response']['access_token'],
        ])->post(config('cybrid.api')."/workflows", $data);
        // \Log::info($response);

        return self::returnData($response);
    }
}
