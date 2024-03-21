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
            if (!empty($response->json()['errorMessage'])) {
                return ['status' => false, 'response' => $response->json()['errorMessage']];
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
    public static function addExternalAccount($user_id, $account)
    {
        $data = [
            'externalId' => $account->account_number,
            'holderType' => 'CONSUMER',
            'type' => $account->account_type,
            'wireRoutingNumber' => $account->wire_routing,
            'linkedDocument' => [
                [
                    'purpose' => 'AUTHORIZATION',
                    'document' => [
                        'type' => 'ATD',
                        'name' => 'file.png',
                        'base64encodedContent' => 'data'
                    ]
                ]
            ],
            'validateAccount' => [
                [
                    'ews' => true
                ]
            ],
            'accountNumber' => $account->account_number,
            'purpose' => 'Transaction',
            'routingNumber' => $account->routing_number,
            'holderName' => $account->holder_name,
            'microDeposit' => 'NEVER',
            'prenote' => 'ON_FAILURE',
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount", $data);
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
     * @param int $user_id
     * @param int $account_id
     * @return array
     */
    public static function getAccountById($user_id, $account_id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount/id/{$account_id}");
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
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount");

        return self::returnData($response);
    }

    /**
     * Delete External Account from User
     * @param int $user_id
     * @param int $account_id
     * @return array
     */
    public static function deleteAccount($user_id, $account_id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->delete("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount/id/{$account_id}");

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
}
