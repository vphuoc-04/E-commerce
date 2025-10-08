<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/BusinessController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$businessController = new BusinessController();

function response($status, $message, $data = null) {
    return json_encode([
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE);
}

switch ($path) {
    case 'show':
        if (isset($_GET['id'])) {
            $business = $businessController->show($_GET['id']);
            if ($business) {
                echo response("success", "Chi tiết business", $business);
            } else {
                echo response("error", "Không tìm thấy business");
            }
        } else {
            echo response("error", "Thiếu ID business");
        }
        break;
}