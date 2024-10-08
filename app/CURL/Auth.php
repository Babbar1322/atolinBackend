<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;

class Auth {
    public static function getToken(string $scope, string $type): array
    {
        $response = Http::post("https://id.sandbox.cybrid.app/oauth/token", [
            'grant_type' => 'client_credentials',
            'client_id' => env('CYBRID_CLIENT_ID'),
            'client_secret' => env('CYBRID_CLIENT_SECRET'),
            'scope' => "$scope:$type",
        ]);

        if ($response->successful()) {
            return ['status' => true, 'response' => $response->json()];
        } else {
            if (!empty($response->json()['error_description'])) {
                return ['status' => false, 'response' => $response->json()['error_description']];
            }
            return ['status' => false, 'response' => $response->json()];
        }
    }
}
