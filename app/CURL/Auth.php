<?php

namespace App\CURL;

use Illuminate\Support\Facades\Http;

class Auth {
    public static function getToken(string $scope, string $type): array
    {
        $response = Http::post(config('cybrid.oauth'), [
            'grant_type' => 'client_credentials',
            'client_id' => config('cybrid.client_id'),
            'client_secret' => config('cybrid.client_secret'),
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

    public static function getTokenForMultipleScopes(array $scopes): array
    {
        $response = Http::post(config('cybrid.oauth'), [
            'grant_type' => 'client_credentials',
            'client_id' => config('cybrid.client_id'),
            'client_secret' => config('cybrid.client_secret'),
            'scope' => implode(' ', $scopes),
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
