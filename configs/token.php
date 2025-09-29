<?php

const JWT_EXPIRE = 180; 
const REFRESH_EXPIRE = 604800;

const JWT_SECRET = "W9XVo1vRqa5a6o2U4I2h0N2kTP5RYQpxwM9F92kglZptFGTQElXYUu5S0uEVwL2OsU0DdjlZql33H0V6jpmKr5d9pmG6coy0";

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function createJwt($payload) {
    $header = json_encode(['alg' => 'HS512', 'typ' => 'JWT']);
    $base64UrlHeader = base64UrlEncode($header);

    $payload['iat'] = time();
    $payload['exp'] = time() + JWT_EXPIRE;
    $base64UrlPayload = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha512', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
    $base64UrlSignature = base64UrlEncode($signature);

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function verifyJwt($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return false;
    }

    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

    $signature = hash_hmac('sha512', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
    $expectedSignature = base64UrlEncode($signature);

    if (!hash_equals($expectedSignature, $base64UrlSignature)) {
        return false;
    }

    $payload = json_decode(base64_decode(strtr($base64UrlPayload, '-_', '+/')), true);

    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false; 
    }

    return $payload;
}

function generateRefreshToken($length = 50) {
    return bin2hex(random_bytes($length / 2));
}
