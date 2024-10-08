<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class ExternalAccount
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
     * Create new external account for Priority Customer
     * @param int $user_id
     * @param object $account
     * @return array
     */
    public static function addExternalAccount($user_id, $account_id, $token)
    {
        $data = [
            "account_kind" => "plaid",
            "name" => "Atolin",
            "customer_guid" => $user_id,
            "asset" => "USD",
            "plaid_account_id" => $account_id,
            "plaid_public_token" => $token,
        ];
        $token = Auth::getToken('external_bank_accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/external_bank_accounts", $data);
        // \Log::info($response);

        return self::returnData($response);
    }

    /**
     * Get External account by user_id and account number
     * @param int $user_id
     * @param int $account_number
     * @return array
     */
    public static function getAccount($user_id, $account_number)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/externalId/{$user_id}/externalAccount/externalId/{$account_number}");
        // \Log::info($response->body());

        return self::returnData($response);
    }

    /**
     * Get External account by user_id and account id
     * @param int $account_id
     * @return array
     */
    public static function getAccountById($account_id)
    {
        $token = Auth::getToken('external_bank_accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/external_bank_accounts/{$account_id}");
        // \Log::info($response->body());

        return self::returnData($response);
    }

    /**
     * Get All External Accounts by user
     * @param int $user_id
     * @return array
     */
    public static function getAllAccounts($user_id)
    {
        $token = Auth::getToken('external_bank_accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/external_bank_accounts?customer_guid=$user_id");

        return self::returnData($response);
    }

    /**
     * Delete External Account from User
     * @param int $account_id
     * @return array
     */
    public static function deleteAccount($account_id)
    {
        $token = Auth::getToken('external_bank_accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->delete("https://bank.sandbox.cybrid.app/api/external_bank_accounts/{$account_id}");

        return self::returnData($response);
    }

    /**
     * Verify External account with micro deposit
     * @param int $user_id
     * @param int $account_id
     * @param int $amount1
     * @param int $amount2
     * @return array
     */
    public static function verifyAccount($user_id, $account_id, $amount1, $amount2)
    {
        $data = [
            'microDeposit' => [
                'creditAmount' => [
                    'amount1' => $amount1,
                    'amount2' => $amount2,
                ]
            ]
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount/id/{$account_id}/verify", $data);

        return self::returnData($response);
    }

    public static function refreshAccount($account_id)
    {
        $token = Auth::getToken('external_bank_accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->patch("https://bank.sandbox.cybrid.app/api/external_bank_accounts/{$account_id}", [
            "status" => "completed"
        ]);

        return self::returnData($response);
    }
}
