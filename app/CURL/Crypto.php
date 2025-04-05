<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ErrorResponse
{
    public int $status;
    public string $error_message;
    public ?string $message_code;

    public function __construct(array $errorData)
    {
        $this->status = $errorData['status'] ?? 500;
        $this->error_message = $errorData['error_message'] ?? 'An unknown error occurred.';
        $this->message_code = $errorData['message_code'] ?? null;
    }
}

class Crypto
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Crypto.php");
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
     * Get accounts by user.
     *
     * @param string $id
     * @return array{status: true, response: array{
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
     * }} | array{status: false, response: array{
     *     status?: int,
     *     error_message?: string,
     *     message_code?: string,
     * }}
     */
    public static function getAccountsByUser($id)
    {
        $token = Auth::getToken('accounts', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api').'/accounts?customer_guid='.$id.'&type=trading');
        return self::returnData($response);
    }

    public static function getAccountById($id)
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

    public static function createAccount($id)
    {
        $token = Auth::getToken('accounts', 'execute');
        if ($token['status'] == false) {
            return $token;
        }
        $accounts = self::getAccountsByUser($id);
        if ($accounts['response']['total'] > 0) {
            return ['status' => false, 'response' => 'Wallet Already Exist', 'wallet' => $accounts['response']['objects'][0]];
        }
        $data = [
            'type' => 'trading',
            'customer_guid' => $id,
            'asset' => 'BTC',
            'name' => 'Crypto Account'
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->post(config('cybrid.api')."/accounts", $data);

        return self::returnData($response);
    }

    /**
     * Get BTC Sell and Buy price.
     *
     * @param string $id
     * @return array{status: bool, response: array{array{
     *     symbol?: string,
     *     buy_price?: int,
     *     sell_price?: int,
     *     buy_price_last_updated_at?: string,
     *     sell_price_last_updated_at?: string,
     * }}|string}
     */
    public static function getBTCPrice()
    {
        $token = Auth::getToken('prices', 'read');
        if ($token['status'] == false) {
            return $token;
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get(config('cybrid.api')."/prices?symbol=BTC-USD");
        return self::returnData($response);
    }
}
