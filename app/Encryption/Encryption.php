<?php

namespace App\Encryption;

class Encryption {
    private static $method = "AES-256-CBC";

    public static function encrypt(string $text)
    {
        return openssl_encrypt($text, self::$method, env('ENCR_KEY'), 0, env('ENC_IV'));
    }
    public static function decrypt(string $text)
    {
        return openssl_decrypt($text, self::$method, env('ENCR_KEY'), 0, env('ENC_IV'));
    }
}
