<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class Transaction
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
     * Send payment from one account to another
     * @param int $amount
     * @param string $sender_id
     * @param string $receiver_id
     * @return array
     */
    public static function makeTransactionACH($amount, $sender_id, $receiver_id, $external_id)
    {
        $data = [
            'amount' => $amount,
            'allowDuplicate' => 'true',
            'method' => 'ACH',
            'purpose' => 'Payment',
            'externalId' => $external_id,
            'source' => [
                'externalAccount' => [
                    'id' => $sender_id,
                ]
            ],
            'destination' => [
                'account' => [
                    'id' => $receiver_id
                ]
            ],
            'type' => 'REGULAR',
            'metaData' => [
                'type' => 'ACH'
            ],
            'comment' => 'Pulling funds from external account'
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post("https://api.sandbox.prioritypassport.com/v1/transaction", $data);
        // \Log::info($response);

        return self::returnData($response);
    }

    /**
     * Send payment to user's bank
     * @param int $amount
     * @param string $receiver_id
     * @return array
     */
    public static function sendToUser($amount, $receiver_id, $external_id)
    {
        $quote = [
            "product_type" => "funding",
            "bank_guid" => "e51933ea9ce04d6f6e9f43cb9d19725e",
            "customer_guid" => $receiver_id,
            "asset" => "USD",
            "receive_amount" => $amount * 100,
            "side" => "withdrawal"
        ];
        $quoteToken = Auth::getToken('quotes', 'execute');
        if ($quoteToken['status'] == false) {
            return self::returnData($quoteToken);
        }
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $quoteToken['response']['access_token'],
        ])->post('https://bank.sandbox.cybrid.app/api/quotes', $quote);

        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }

        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "funding",
            "external_bank_account_guid" => $external_id,
        ];
        $transferToken = Auth::getToken('transfers', 'execute');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $transferToken['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/transfers", $transfer);
        // \Log::info($response);

        return self::returnData($response);
    }

    public static function collectToMainAccount(int $amount, string $sender_id, $external_id, $fee, $accountId)
    {
        $quote = [
            "product_type" => "funding",
            "bank_guid" => "e51933ea9ce04d6f6e9f43cb9d19725e",
            "customer_guid" => $sender_id,
            "asset" => "USD",
            "receive_amount" => $amount * 100,
            "side" => "deposit",
            "fees" => [
                [
                    "type" => "spread",
                    "spread_fee" => $fee * 100
                ]
            ]
        ];
        $quoteToken = Auth::getToken('quotes', 'execute');
        if ($quoteToken['status'] == false) {
            return self::returnData($quoteToken);
        }
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $quoteToken['response']['access_token'],
        ])->post('https://bank.sandbox.cybrid.app/api/quotes', $quote);

        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }

        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "funding",
            "external_bank_account_guid" => $external_id,
            "customer_fiat_account_guid" => $accountId,
        ];
        $transferToken = Auth::getToken('transfers', 'execute');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $transferToken['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/transfers", $transfer);
        // \Log::info($response);

        self::sendUserToUser($amount * 100, $accountId, env('CYBRID_MAIN_ACCOUNT'));

        return self::returnData($response);
    }

    public static function sendUserToUser(int $amount, string $sender_id, string $receiver_id) {
        $quote = [
            "product_type"=> 'book_transfer',
            "asset" => "USD",
            "receive_amount" => $amount,
        ];
        $quoteToken = Auth::getToken('quotes', 'execute');
        if ($quoteToken['status'] == false) {
            return self::returnData($quoteToken);
        }
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $quoteToken['response']['access_token'],
        ])->post('https://bank.sandbox.cybrid.app/api/quotes', $quote);

        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "book",
            "source_account_guid" => $sender_id,
            "destination_account_guid" => $receiver_id,
        ];
        $transferToken = Auth::getToken('transfers', 'execute');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $transferToken['response']['access_token'],
        ])->post("https://bank.sandbox.cybrid.app/api/transfers", $transfer);

        return self::returnData($response);
    }

    /**
     * Get Transaction by external ID
     * @param int $id
     * @return array
     */
    public static function getTransaction($id)
    {
        $token = Auth::getToken("transfers", "execute");
        if ($token["status"] == false) {
            return self::returnData($token);
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
        ])->get("https://bank.sandbox.cybrid.app/api/transfers/{$id}");

        return self::returnData($response);
    }

    // public static function getAllAccounts(User $user)
    // {
    //     $response = Http::withHeaders([
    //         'PromiseMode' => 'NEVER',
    //         'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
    //     ])->get("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user->priority_id}/externalAccount");

    //     return self::returnData($response);
    // }

    // public static function deleteAccount($user_id, $account_id)
    // {
    //     $response = Http::withHeaders([
    //         'PromiseMode' => 'NEVER',
    //         'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
    //     ])->delete("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/externalAccount/id/{$account_id}");

    //     return self::returnData($response);
    // }

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
}
