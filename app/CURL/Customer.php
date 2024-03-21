<?php

namespace App\CURL;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class Customer
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
    public static function createCustomer(User $user)
    {
        $data = [
            'externalId' => $user->id,
            'type' => 'INDIVIDUAL',
            'individual' => [
                'firstName' => $user->name,
                'lastName' => $user->lastname,
                'email' => $user->email,
            ],
            'linkedDocument' => [
                [
                    'purpose' => 'IDENTIFICATION_PROOF',
                    'document' => [
                        'type' => 'PASSPORT',
                        'name' => 'filename.png',
                        'base64encodedContent' => 'data'
                    ]
                ]
            ]
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->post('https://api.sandbox.prioritypassport.com/v1/customer', $data);
        // \Log::info($response);
        return self::returnData($response);
    }

    public static function getCustomer($id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->get("https://api.sandbox.prioritypassport.com/v1/customer/externalId/{$id}");
        // \Log::info($response);
        return self::returnData($response);
    }

    public static function deleteCustomer($id)
    {
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Bearer ' . env('PRIORITY_API_KEY'),
        ])->delete("https://api.sandbox.prioritypassport.com/v1/customer/id/{$id}");

        return self::returnData($response);
    }
}
