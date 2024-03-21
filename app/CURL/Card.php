<?php

namespace App\CURL;

use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class Card
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
     * Add card to user
     * @param User $user
     * @param object $card
     * @return array
     */
    public static function createCard(User $user, $card)
    {
        $data = [
            'externalId' => $card->card_no,
            'holderName' => $card->name,
            'cardNumber' => $card->card_no,
            'expiryMonth' => $card->exp_month,
            'expiryYear' => $card->exp_year,
            'cvv' => $card->cvv,
            'billingAddress' => [
                'addressLine1' => $card->address_line1,
                'addressLine2' => $card->address_line2,
                'city' => $card->city,
                'state' => $card->state,
                'zip' => $card->zip,
            ],
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user->priority_id}/card", $data);
        // \Log::info($response);

        return self::returnData($response);
    }

    /**
     * Get Card from user_id and card number
     * @param int $user_id
     * @param int $card_number
     * @return array
     */
    public static function getCard($user_id, $card_number)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/externalId/{$user_id}/card/externalId/{$card_number}");
        // \Log::info($response->body());

        return self::returnData($response);
    }

    /**
     * Get all cards from user
     * @param int $user_id
     * @return array
     */
    public static function getAllCards(int $user_id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/card");

        return self::returnData($response);
    }

    /**
     * Delete card from user
     * @param int $user_id
     * @param int $card_id
     * @return array
     */
    public static function deleteCard($user_id, $card_id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->delete("https://api.sandbox.prioritypassport.com/v1/customer/id/{$user_id}/card/id/{$card_id}");

        return self::returnData($response);
    }
}
