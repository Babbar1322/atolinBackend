<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Transaction
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in Transaction.php");
                Log::info($response->json());
                Log::info("================================");
            }
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function deposit($customer_guid, $account_guid, $extenal_acccount_guid, $amount, $fee)
    {
        $recieve_amount = $amount * 100;
        $quote = [
            "product_type" => "funding",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "receive_amount" => $recieve_amount,
            "asset" => "USD",
            "side" => "deposit",
        ];
        if ($fee > 0) {
            $quote['fees'] = [
                [
                    "type" => "spread",
                    "spread_fee" => $fee * 100
                ]
            ];
        }
        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "transfer_type" => "funding",
            "quote_guid" => $quoteResponse->json()['guid'],
            "customer_guid" => $customer_guid,
            "customer_fiat_account_guid" => $account_guid,
            "external_bank_account_guid" => $extenal_acccount_guid,
            "source_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $recieve_amount,
                ]
            ],
            "destination_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $recieve_amount
                ]
            ],
        ];

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/transfers', $transfer);
        return self::returnData($transferResponse);
    }

    public static function withdraw($customer_guid, $account_guid, $extenal_acccount_guid, $amount, $fee)
    {
        $deliver_amount = $amount * 100;
        $quote = [
            "product_type" => "funding",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "asset" => "USD",
            "side" => "withdrawal",
            "deliver_amount" => $deliver_amount,
        ];
        if ($fee > 0) {
            $quote['fees'] = [
                [
                    "type" => "spread",
                    "spread_fee" => $fee * 100
                ]
            ];
        }
        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "transfer_type" => "funding",
            "quote_guid" => $quoteResponse->json()['guid'],
            "customer_guid" => $customer_guid,
            "customer_fiat_account_guid" => $account_guid,
            "external_bank_account_guid" => $extenal_acccount_guid,
            "source_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $deliver_amount,
                ]
            ],
            "destination_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $deliver_amount
                ]
            ],
        ];

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/transfers', $transfer);
        return self::returnData($transferResponse);
    }

    public static function customerToBank($customer_guid, $source_account_guid, $amount)
    {
        $deliver_amount = $amount * 100;
        $quote = [
            "product_type" => "book_transfer",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "asset"  => "USD",
            "deliver_amount" => $deliver_amount,
        ];

        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "book",
            "source_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $deliver_amount
                ]
                ],
            "destination_participants" => [
                [
                    "type" => "bank",
                    "guid" => config('cybrid.bank'),
                    "amount" => $deliver_amount
                ]
            ],
            "source_account_guid" => $source_account_guid,
            "destination_account_guid" => config('cybrid.main_account'),
        ];

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/transfers', $transfer);
        return self::returnData($transferResponse);
    }

    public static function bankToCustomer($customer_guid, $destination_account_guid, $amount)
    {
        $deliver_amount = $amount * 100;
        $quote = [
            "product_type" => "book_transfer",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "asset"  => "USD",
            "deliver_amount" => $deliver_amount,
        ];

        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "book",
            "source_participants" => [
                [
                    "type" => "bank",
                    "guid" => config('cybrid.bank'),
                    "amount" => $deliver_amount
                ]
            ],
            "destination_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $deliver_amount
                ]
            ],
            "source_account_guid" => config('cybrid.main_account'),
            "destination_account_guid" => $destination_account_guid,
        ];

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/transfers', $transfer);
        return self::returnData($transferResponse);
    }

    public static function customerToCustomer($sender_id, $receiver_id, $source_account_guid, $destination_account_guid, $amount)
    {
        $deliver_amount = $amount * 100;
        $quote = [
            "product_type" => "book_transfer",
            "bank_guid" => config('cybrid.bank'),
            "asset"  => "USD",
            "deliver_amount" => $deliver_amount,
        ];

        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "quote_guid" => $quoteResponse->json()['guid'],
            "transfer_type" => "book",
            "source_participants" => [
                [
                    "type" => "customer",
                    "guid" => $sender_id,
                    "amount" => $deliver_amount
                ]
            ],
            "destination_participants" => [
                [
                    "type" => "customer",
                    "guid" => $receiver_id,
                    "amount" => $deliver_amount
                ]
            ],
            "source_account_guid" => $source_account_guid,
            "destination_account_guid" => $destination_account_guid,
        ];

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/transfers', $transfer);
        return self::returnData($transferResponse);
    }
}
