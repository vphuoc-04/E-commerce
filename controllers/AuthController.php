<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../configs/token.php';

class AuthController {
    public function login($request) {
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;

        $user = User::findByEmail($email);
        if (!$user) {
            return ["status" => "error", "message" => "Email không tồn tại"];
        }

        if (!password_verify($password, $user->password)) {
            return ["status" => "error", "message" => "Sai mật khẩu"];
        }

        $token = createJwt([
            "id"    => $user->id,
            "email" => $user->email
        ], JWT_EXPIRE, JWT_SECRET);

        $refreshToken = generateRefreshToken(50);
        $expiresAt = date("Y-m-d H:i:s", time() + REFRESH_EXPIRE);

        User::storeRefreshToken($user->id, $refreshToken, $expiresAt);

        return [
            "status"  => "success",
            "message" => "Đăng nhập thành công",
            "token"   => $token,
            "refreshToken" => $refreshToken,
            "data" => [
                "id"               => $user->id,
                "email"            => $user->email,
                "firstName"        => $user->firstName,
                "middleName"       => $user->middleName,
                "lastName"         => $user->lastName,
                "img"              => $user->img,
                "gender"           => $user->gender,
                "phone"            => $user->phone,
                "userCatalogueName"=> $user->userCatalogueName,
            ]
        ];
    }

    public function refresh($request) {
        $refreshToken = $request['refreshToken'] ?? null;

        if (!$refreshToken) {
            return ["status" => "error", "message" => "Thiếu refresh token"];
        }

        $user = User::findByRefreshToken($refreshToken);
        if (!$user) {
            return ["status" => "error", "message" => "Refresh token không hợp lệ"];
        }

        if (strtotime($user['expires_at']) < time()) {
            return ["status" => "error", "message" => "Refresh token đã hết hạn"];
        }

        $newAccessToken = createJwt([
            "id"    => $user['id'],
            "email" => $user['email']
        ], JWT_EXPIRE, JWT_SECRET);

        $newRefreshToken = generateRefreshToken(50);
        $newExpiresAt = date("Y-m-d H:i:s", time() + REFRESH_EXPIRE);

        User::storeRefreshToken($user['id'], $newRefreshToken, $newExpiresAt);

        return [
            "status"  => "success",
            "message" => "Làm mới token thành công",
            "token"   => $newAccessToken,
            "refreshToken" => $newRefreshToken
        ];
    }

    public function verify($jwt) {
        $payload = verifyJwt($jwt);
        if (!$payload) {
            return ["status" => "error", "message" => "Token không hợp lệ hoặc đã hết hạn"];
        }
        return ["status" => "success", "message" => "Token hợp lệ", "data" => $payload];
    }
}
