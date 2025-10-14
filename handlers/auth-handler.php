<?php
session_start();
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../services/OTPService.php';

class AuthHandler {
    private $authController;
    private $otpService;

    public function __construct() {
        $this->authController = new AuthController();
        $this->otpService = new OTPService();
    }

    private function baseUrl(): string {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $rootPath = '/webbanhang/';
        return $protocol . $host . $rootPath;
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirectWithError('register', 'Method không được phép');
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];

        $result = $this->authController->register($data);

        if ($result['status'] === 'success') {
            header('Location: ' . $this->baseUrl() . 'verify-otp?email=' . urlencode($data['email']));
            exit;
        } else {
            return $this->redirectWithError('register', $result['message'], $data);
        }
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirectWithError('login', 'Method không được phép');
        }

        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? ''
        ];

        $result = $this->authController->login($data);

        if ($result['status'] === 'success') {
            $_SESSION['user'] = $result['data'];
            $_SESSION['token'] = $result['token'];
            $_SESSION['refreshToken'] = $result['refreshToken'];

            header('Location: ' . $this->baseUrl() . 'home');
            exit;
        } else {
            $requires_verification = $result['requires_verification'] ?? false;
            $email = $result['email'] ?? $data['email'];

            return $this->redirectWithError('login', $result['message'], $data, $requires_verification, $email);
        }
    }

    public function handleVerifyOTP() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirectWithError('verify-otp', 'Method không được phép');
        }

        $data = [
            'email' => $_POST['email'] ?? '',
            'otp_code' => $_POST['otp_code'] ?? ''
        ];

        $result = $this->authController->verifyOTP($data);

        if ($result['status'] === 'success') {
            header('Location: ' . $this->baseUrl() . 'login?success=' . urlencode($result['message']));
            exit;
        } else {
            return $this->redirectWithError('verify-otp', $result['message'], $data);
        }
    }

    public function handleResendOTP() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirectWithError('verify-otp', 'Method không được phép');
        }

        $data = [
            'email' => $_POST['email'] ?? ''
        ];

        $result = $this->authController->resendOTP($data);

        if ($result['status'] === 'success') {
            header('Location: ' . $this->baseUrl() . 'verify-otp?email=' . urlencode($data['email']) . '&success=' . urlencode($result['message']));
            exit;
        } else {
            return $this->redirectWithError('verify-otp', $result['message'], $data);
        }
    }

    private function redirectWithError($page, $message, $data = [], $requires_verification = false, $email = '') {
        $params = http_build_query([
            'error' => $message,
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? $email,
            'requires_verification' => $requires_verification
        ]);

        header('Location: ' . $this->baseUrl() . $page . '?' . $params);
        exit;
    }
}

$action = $_GET['action'] ?? '';
$handler = new AuthHandler();

switch ($action) {
    case 'register':
        $handler->handleRegister();
        break;

    case 'login':
        $handler->handleLogin();
        break;

    case 'verify-otp':
        $handler->handleVerifyOTP();
        break;

    case 'resend-otp':
        $handler->handleResendOTP();
        break;

    default:
        header('Location: ' . (new AuthHandler())->baseUrl() . 'home');
        exit;
}
