<?php
require_once __DIR__ . '/../controllers/AuthController.php';

class AuthApi {
    private $authController;

    public function __construct() {
        $this->authController = new AuthController();
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_GET['action'] ?? '';

        $input = json_decode(file_get_contents('php://input'), true) ?? [];

        switch ($action) {
            case 'login':
                if ($method === 'POST') {
                    return $this->authController->login($input);
                }
                break;

            case 'register':
                if ($method === 'POST') {
                    return $this->authController->register($input);
                }
                break;

            case 'verify-otp':
                if ($method === 'POST') {
                    return $this->authController->verifyOTP($input);
                }
                break;

            case 'resend-otp':
                if ($method === 'POST') {
                    return $this->authController->resendOTP($input);
                }
                break;

            case 'refresh':
                if ($method === 'POST') {
                    return $this->authController->refresh($input);
                }
                break;

            case 'verify':
                if ($method === 'GET') {
                    $jwt = $_GET['token'] ?? '';
                    return $this->authController->verify($jwt);
                }
                break;

            case 'logout':
                if ($method === 'POST') {
                    return $this->authController->logout($input);
                }
                break;

            default:
                return [
                    'status' => 'error',
                    'message' => 'Action không hợp lệ'
                ];
        }

        return [
            'status' => 'error',
            'message' => 'Method không được hỗ trợ'
        ];
    }
}

// Handle API request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    
    try {
        $api = new AuthApi();
        $result = $api->handleRequest();
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi server: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method không được phép'
    ]);
}