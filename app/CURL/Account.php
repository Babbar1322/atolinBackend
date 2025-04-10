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
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Account.php");
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
     * @param Response $response
     * @return array{status: bool, response: array{
     *     total?: int,
     *     page?: int,
     *     per_page?: int,
     *     objects?: array<array{
     *         type: string,
     *         guid: string,
     *         created_at: string,
     *         updated_at: string,
     *         asset: string,
     *         name: string,
     *         bank_guid: string,
     *         customer_guid: string,
     *         platform_balance: int,
     *         platform_available: int,
     *         state: string,
     *         labels: array<string>
     *     }>
     * }|string}
     */
    public static function getByUser($id)
    {
        $token = Auth::getToken('accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api').'/accounts?customer_guid='.$id.'&type=fiat');
        return self::returnData($response);
    }

    /**
     * Get an account by ID.
     *
     * @param string $id
     * @return array{status: bool, response: array{
     *     type?: string,
     *     guid?: string,
     *     created_at?: string,
     *     updated_at?: string,
     *     asset?: string,
     *     name?: string,
     *     bank_guid?: string,
     *     customer_guid?: string,
     *     platform_balance?: int,
     *     platform_available?: int,
     *     state?: string,
     *     labels?: array<string>
     * }|string}
     */
    public static function getById($id)
    {
        $token = Auth::getToken('accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/accounts/{$id}");
        return self::returnData($response);
    }

    /**
     * Create new account.
     *
     * @param string $id
     * @return array{status: bool, response: array{
     *     type?: string,
     *     guid?: string,
     *     created_at?: string,
     *     updated_at?: string,
     *     asset?: string,
     *     name?: string,
     *     bank_guid?: string,
     *     customer_guid?: string,
     *     platform_balance?: int,
     *     platform_available?: int,
     *     state?: string,
     *     labels?: array<string>
     * }|string}
     */
    public static function create($id)
    {
        $token = Auth::getToken('accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
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
        ])->post(config('cybrid.api')."/accounts", $data);
        return self::returnData($response);
    }
}
