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
            if (!empty($response->json()['errorMessage'])) {
                return ['status' => false, 'response' => $response->json()['errorMessage']];
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
        $data = [
            'amount' => $amount,
            'allowDuplicate' => 'true',
            'method' => 'ACH',
            'purpose' => 'Payment',
            'externalId' => $external_id,
            'source' => [
                'account' => [
                    'id' => 4006917
                ]
            ],
            'destination' => [
                'externalAccount' => [
                    'id' => $receiver_id,
                ]
            ],
            'type' => 'REGULAR',
            'metaData' => [
                'type' => 'ACH'
            ],
            'comment' => 'Withdraw',
            'processingDetail' => [
                'authType' => 'ONLINE'
            ]
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post("https://api.sandbox.prioritypassport.com/v1/transaction", $data);
        // \Log::info($response);

        return self::returnData($response);
    }

    public static function collectToMainAccount(int $amount, string $sender_id, $external_id, $type)
    {
        $source_type = $type === 'ACH' ? 'externalAccount' : 'card';
        $data = [
            'amount' => $amount,
            'allowDuplicate' => 'true',
            'method' => $type,
            'purpose' => 'Payment',
            'externalId' => $external_id,
            'source' => [
                $source_type => [
                    'id' => $sender_id,
                ]
            ],
            'processingDetail' => $type === 'ACH' ? [
                'authType' => 'ONLINE',
                'processingMode' =>  'SAME_DAY',
            ] : [
                'merchant' => [
                    'id' => env('PRIORITY_MERCHANT')
                ]
            ],
            'destination' => [
                'account' => [
                    'id' => 4006917
                ]
            ],
            'reason' => 'ON_CUSTOMER_REQUEST',
            'type' => 'REGULAR',
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
     * Get Transaction by external ID
     * @param int $id
     * @return array
     */
    public static function getTransaction($id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/transaction/externalId/{$id}");
        // \Log::info($response->body());

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
