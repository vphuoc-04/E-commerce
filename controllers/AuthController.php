<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../configs/token.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../services/OTPService.php';

class AuthController {
    private function isSuperAdmin($user) {
        if (isset($user->catalogueId) && $user->catalogueId == 1) {
            return true;
        }
        
        return false;
    }

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

        $isSuperAdmin = $this->isSuperAdmin($user);
        if (!$isSuperAdmin && !User::isEmailVerified($email)) {
            return [
                "status" => "error", 
                "message" => "Tài khoản chưa được xác thực email. Vui lòng kiểm tra email và xác thực tài khoản.",
                "requires_verification" => true,
                "email" => $email
            ];
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
                "catalogueId"      => $user->catalogueId,
                "isSuperAdmin"     => $isSuperAdmin
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

    public function register($request) {
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;
        $passwordConfirm = $request['password_confirm'] ?? null;
        $name = $request['name'] ?? null;

        // Validation
        if (empty($email) || empty($password) || empty($name)) {
            return ["status" => "error", "message" => "Vui lòng điền đầy đủ thông tin"];
        }

        if ($password !== $passwordConfirm) {
            return ["status" => "error", "message" => "Mật khẩu xác nhận không khớp"];
        }

        if (strlen($password) < 6) {
            return ["status" => "error", "message" => "Mật khẩu phải có ít nhất 6 ký tự"];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => "error", "message" => "Email không hợp lệ"];
        }

        // Kiểm tra email đã tồn tại
        if (User::findByEmail($email)) {
            return ["status" => "error", "message" => "Email đã được sử dụng"];
        }

        // Tạo tài khoản tạm thời (chưa xác thực)
        $nameParts = explode(' ', trim($name), 3);
        $userData = [
            'first_name' => $nameParts[0] ?? '',
            'middle_name' => $nameParts[1] ?? '',
            'last_name' => $nameParts[2] ?? '',
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'gender' => null,
            'birth_date' => null,
            'phone' => null,
            'catalogue_id' => 2 // Mặc định là customer (giả sử catalogue_id = 2 là customer)
        ];

        $userId = User::create($userData);
        if (!$userId) {
            return ["status" => "error", "message" => "Không thể tạo tài khoản"];
        }

        // Tạo và gửi OTP
        $otpService = new OTPService();
        $otpResult = $otpService->generateOTP($email);
        
        if ($otpResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Không thể tạo mã xác thực"];
        }

        $emailService = new EmailService();
        $emailResult = $emailService->sendOTP($email, $name, $otpResult['otp_code']);
        
        if ($emailResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Không thể gửi email xác thực"];
        }

        return [
            "status" => "success", 
            "message" => "Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.",
            "data" => [
                "user_id" => $userId,
                "email" => $email,
                "requires_verification" => true
            ]
        ];
    }

    public function verifyOTP($request) {
        $email = $request['email'] ?? null;
        $otpCode = $request['otp_code'] ?? null;

        if (empty($email) || empty($otpCode)) {
            return ["status" => "error", "message" => "Vui lòng nhập email và mã OTP"];
        }

        $otpService = new OTPService();
        $verifyResult = $otpService->verifyOTP($email, $otpCode);

        if ($verifyResult['status'] !== 'success') {
            return $verifyResult;
        }

        // Cập nhật trạng thái xác thực
        $user = User::findByEmail($email);
        if (!$user) {
            return ["status" => "error", "message" => "Không tìm thấy tài khoản"];
        }

        User::updateVerificationStatus($user->id, true);

        return [
            "status" => "success", 
            "message" => "Xác thực email thành công! Bạn có thể đăng nhập ngay bây giờ."
        ];
    }

    public function resendOTP($request) {
        $email = $request['email'] ?? null;

        if (empty($email)) {
            return ["status" => "error", "message" => "Vui lòng nhập email"];
        }

        $user = User::findByEmail($email);
        if (!$user) {
            return ["status" => "error", "message" => "Email không tồn tại"];
        }

        // Bỏ qua kiểm tra xác thực email nếu là Super Admin (catalogue_id = 1)
        $isSuperAdmin = $this->isSuperAdmin($user);
        if (!$isSuperAdmin && User::isEmailVerified($email)) {
            return ["status" => "error", "message" => "Email đã được xác thực"];
        }

        $otpService = new OTPService();
        $otpResult = $otpService->generateOTP($email);
        
        if ($otpResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Không thể tạo mã xác thực"];
        }

        $emailService = new EmailService();
        $emailResult = $emailService->sendOTP($email, $user->firstName, $otpResult['otp_code']);
        
        if ($emailResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Không thể gửi email xác thực"];
        }

        return [
            "status" => "success", 
            "message" => "Mã xác thực mới đã được gửi đến email của bạn"
        ];
    }

    public function logout($request) {
        return [
            "status" => "success", 
            "message" => "Đăng xuất thành công"
        ];
    }
}