<?php
require_once __DIR__ . '/../controllers/AuthController.php';

function requireAuth() {
    $token = $_COOKIE['token'] ?? null;

    if (!$token) {
        header("Location: login&expired=1");
        exit;
    }

    $authController = new AuthController();
    $payload = $authController->verify($token);

    if ($payload['status'] === 'error') {
        $refreshToken = $_COOKIE['refresh_token'] ?? null;

        if ($refreshToken) {
            $ch = curl_init("http://localhost/webbanhang/apis/AuthApi.php?route=refresh");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                "refreshToken" => $refreshToken
            ]));
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data['token'])) {
                setcookie("token", $data['token'], time() + 3600, "/");
                setcookie("refresh_token", $data['refreshToken'], time() + 60*60*24*7, "/");

                header("Refresh:0");
                exit;
            }
        }

        setcookie("token", "", time() - 3600, "/");
        setcookie("refresh_token", "", time() - 3600, "/");
        session_destroy();
        header("Location: login&expired=1");
        exit;
    }
}
