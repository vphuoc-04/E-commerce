<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/AuthController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$authController = new AuthController();

switch ($path) {
    case 'login':
        echo json_encode($authController->login($input), JSON_UNESCAPED_UNICODE);
        break;

    case 'refresh':
        if ($method === 'POST') {
            echo json_encode($authController->refresh($input), JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Route not found"]);
        break;
}
