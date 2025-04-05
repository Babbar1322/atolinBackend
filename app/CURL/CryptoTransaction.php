<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Log;

class CryptoTransaction
{
    public static function returnData($response)
    {
        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        }
        if ($response->failed()) {
            if (env('LOG_CYBRID')) {
                Log::info("Cybrid Error in CryptoTransfer.php");
                Log::info($response->json());
                Log::info("================================");
            }
            if (!empty($response->json()['error_message'])) {
                return ['status' => false, 'response' => $response->json()['error_message']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }

    public static function buy($customer_guid, $amount, $fee)
    {
        $deliver_amount = $amount * 100;
        $quote = [
            "product_type" => "trading",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "deliver_amount" => $deliver_amount,
            "side" => "buy",
            "symbol" => "BTC-USD"
        ];
        if ($fee > 0) {
            $quote['fees'] = [
                [
                    "type" => "spread",
                    "spread_fee" => $fee
                ]
            ];
        }
        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'trades:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $trade = [
            "trade_type" => "platform",
            "quote_guid" => $quoteResponse->json()['guid'],
        ];

        $tradeResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/trades', $trade);
        return self::returnData($tradeResponse);
    }

    public static function sell($customer_guid, $amount, $fee)
    {
        $deliver_amount = $amount;
        $quote = [
            "product_type" => "trading",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "deliver_amount" => $deliver_amount,
            "side" => "sell",
            "symbol" => "BTC-USD"
        ];
        if ($fee > 0) {
            $quote['fees'] = [
                [
                    "type" => "spread",
                    "spread_fee" => $fee
                ]
            ];
        }
        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'trades:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $trade = [
            "trade_type" => "platform",
            "quote_guid" => $quoteResponse->json()['guid'],
        ];

        $tradeResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/trades', $trade);
        return self::returnData($tradeResponse);
    }

    public static function withdraw($customer_guid, $extenal_wallet_guid, $amount)
    {
        $deliver_amount = $amount;
        $quote = [
            "product_type" => "crypto_transfer",
            "bank_guid" => config('cybrid.bank'),
            "customer_guid" => $customer_guid,
            "asset" => "BTC",
            "side" => "withdrawal",
            "deliver_amount" => $deliver_amount,
        ];
        // if ($fee > 0) {
        //     $quote['fees'] = [
        //         [
        //             "type" => "spread",
        //             "spread_fee" => $fee
        //         ]
        //     ];
        // }
        $token = Auth::getTokenForMultipleScopes(['quotes:execute', 'transfers:execute']);
        $quoteResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->post(config('cybrid.api').'/quotes', $quote);
        if ($quoteResponse->failed()) {
            return self::returnData($quoteResponse);
        }
        $transfer = [
            "transfer_type" => "crypto",
            "quote_guid" => $quoteResponse->json()['guid'],
            "customer_guid" => $customer_guid,
            "external_wallet_guid" => $extenal_wallet_guid,
            "source_participants" => [
                [
                    "type" => "customer",
                    "guid" => $customer_guid,
                    "amount" => $deliver_amount,
                ]
            ],
            "destination_participants" => [
                [
                    "type" => "external_wallet",
                    "guid" => $extenal_wallet_guid,
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

    public static function tradeHistory($customer_id, $page = 0) {
        $token = Auth::getTokenForMultipleScopes(['trades:read']);

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->get(config('cybrid.api')."/trades?per_page=10&page={$page}&customer_guid={$customer_id}");
        return self::returnData($transferResponse);
    }
    public static function transferHistory($customer_id, $page = 0) {
        $token = Auth::getTokenForMultipleScopes(['transfers:read']);

        $transferResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['response']['access_token'],
            'Content-Type' => 'application/json',
        ])->get(config('cybrid.api')."/transfers?per_page=10&page={$page}&customer_guid={$customer_id}&transfer_type=crypto");
        return self::returnData($transferResponse);
    }
}
