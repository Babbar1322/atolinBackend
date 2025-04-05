<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalWallet
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in ExternalWallet.php");
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
     * Create new account.
     *
     * @param string $customer_guid
     * @param string $name
     * @param string $address
     * @return array{status: bool, response: array{
     *     guid?: string,
     *     created_at?: string,
     *     updated_at?: string,
     *     asset?: string,
     *     environment?: string,
     *     address?: string,
     *     tag?: string,
     *     name?: string,
     *     bank_guid?: string,
     *     customer_guid?: string,
     *     state?: string,
     * }|string}
     */
    public static function addExternalAccount($customer_guid, $name, $address)
    {
        $data = [
            "name" => $name,
            "customer_guid" => $customer_guid,
            "asset" => "BTC",
            "address" => $address,
        ];
        $token = Auth::getToken('external_wallets', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post(config('cybrid.api')."/external_wallets", $data);

        return self::returnData($response);
    }

    /**
     * Get External account by user_id and account id
     * @param int $account_id
     * @return array
     */
    public static function getAccountById($account_id)
    {
        $token = Auth::getToken('external_wallets', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/external_wallets/{$account_id}");
        // \Log::info($response->body());

        return self::returnData($response);
    }

    /**
     * @param int $user_id
     * @return array{status: bool, response: array{
     *     total?: int,
     *     page?: int,
     *     per_page?: int,
     *     objects?: array<array{
     *         guid?: string,
     *         created_at?: string,
     *         updated_at?: string,
     *         asset?: string,
     *         environment?: string,
     *         address?: string,
     *         tag?: string,
     *         name?: string,
     *         bank_guid?: string,
     *         customer_guid?: string,
     *         state?: string,
     *     }>
     * }|string}
     */
    public static function getAllAccounts($user_id)
    {
        $token = Auth::getToken('external_wallets', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/external_wallets?customer_guid=$user_id&state=completed,storing");

        return self::returnData($response);
    }

    /**
     * Delete External Account from User
     * @param int $account_id
     * @return array
     */
    public static function deleteAccount($account_id)
    {
        $token = Auth::getToken('external_wallets', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->delete(config('cybrid.api')."/external_wallets/{$account_id}");

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
    // public static function verifyAccount($user_id, $account_id, $amount1, $amount2)
    // {
    //     $data = [
    //         'microDeposit' => [
    //             'creditAmount' => [
    //                 'amount1' => $amount1,
    //                 'amount2' => $amount2,
    //             ]
    //         ]
    //     ];
    //     $response = Http::withHeaders([
    //         'PromiseMode' => 'NEVER',
    //         'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
    //     ])->post("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount/id/{$account_id}/verify", $data);

    //     return self::returnData($response);
    // }

    public static function refreshAccount($account_id)
    {
        $token = Auth::getToken('external_wallets', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->patch(config('cybrid.api')."/external_wallets/{$account_id}", [
            "status" => "completed"
        ]);

        return self::returnData($response);
    }
}
