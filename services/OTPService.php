<?php
require_once __DIR__ . '/../configs/email.php';
require_once __DIR__ . '/../configs/database.php';

class OTPService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function generateOTP($email) {
        $otpCode = $this->generateRandomOTP();
        $expiresAt = date('Y-m-d H:i:s', time() + (OTP_EXPIRE_MINUTES * 60));

        try {
            $this->deleteOldOTP($email);

            $stmt = $this->pdo->prepare("
                INSERT INTO user_otps (email, otp_code, expires_at, attempts, created_at)
                VALUES (?, ?, ?, 0, NOW())
            ");
            $stmt->execute([$email, $otpCode, $expiresAt]);

            return [
                'status' => 'success',
                'otp_code' => $otpCode,
                'expires_at' => $expiresAt
            ];
        } catch (Exception $e) {
            error_log("OTP generation error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Lỗi hệ thống khi tạo OTP'];
        }
    }

    public function verifyOTP($email, $otpCode) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM user_otps
                WHERE email = ? AND expires_at > NOW()
                ORDER BY created_at DESC
                LIMIT 1
            ");
            $stmt->execute([$email]);
            $otpRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$otpRecord) {
                return ['status' => 'error', 'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn'];
            }

            if ($otpRecord['attempts'] >= OTP_MAX_ATTEMPTS) {
                return ['status' => 'error', 'message' => 'Đã vượt quá số lần thử cho phép'];
            }

            if (trim($otpRecord['otp_code']) !== trim($otpCode)) {
                $this->incrementAttempts($otpRecord['id']);
                return ['status' => 'error', 'message' => 'Mã OTP không đúng'];
            }

            $this->deleteOTP($otpRecord['id']);
            return ['status' => 'success', 'message' => 'Xác thực OTP thành công'];

        } catch (Exception $e) {
            error_log("OTP verification error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Lỗi hệ thống khi xác thực OTP'];
        }
    }

    public function isValidOTP($email, $otpCode) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) AS count FROM user_otps
                WHERE email = ? AND otp_code = ? AND expires_at > NOW()
            ");
            $stmt->execute([$email, $otpCode]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            return $r['count'] > 0;
        } catch (Exception $e) {
            error_log("OTP validation error: " . $e->getMessage());
            return false;
        }
    }

    public function createOTPTable() {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS user_otps (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(255) NOT NULL,
                    otp_code VARCHAR(10) NOT NULL,
                    expires_at DATETIME NOT NULL,
                    attempts INT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_email (email),
                    INDEX idx_expires_at (expires_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            $this->pdo->exec($sql);
            return true;
        } catch (Exception $e) {
            error_log("OTP table creation error: " . $e->getMessage());
            return false;
        }
    }

    private function generateRandomOTP() {
        return str_pad(strval(random_int(0, pow(10, OTP_LENGTH) - 1)), OTP_LENGTH, '0', STR_PAD_LEFT);
    }

    private function deleteOldOTP($email) {
        $stmt = $this->pdo->prepare("DELETE FROM user_otps WHERE email = ?");
        $stmt->execute([$email]);
    }

    private function deleteOTP($otpId) {
        $stmt = $this->pdo->prepare("DELETE FROM user_otps WHERE id = ?");
        $stmt->execute([$otpId]);
    }

    private function incrementAttempts($otpId) {
        $stmt = $this->pdo->prepare("UPDATE user_otps SET attempts = attempts + 1 WHERE id = ?");
        $stmt->execute([$otpId]);
    }

    public function cleanupExpiredOTPs() {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user_otps WHERE expires_at < NOW()");
            $stmt->execute();
            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("OTP cleanup error: " . $e->getMessage());
            return 0;
        }
    }
}
