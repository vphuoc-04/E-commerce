<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/UserController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$userController = new UserController();

function response($status, $message, $data = null) {
    return json_encode([
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE);
}

switch ($path) {
    case 'index': 
        $users = $userController->index();
        echo response("success", "Danh sách user", $users);
        break;

    case 'show':
        if (isset($_GET['id'])) {
            $user = $userController->show($_GET['id']);
            if ($user) {
                echo response("success", "Chi tiết user", $user);
            } else {
                echo response("error", "Không tìm thấy user");
            }
        } else {
            echo response("error", "Thiếu ID user");
        }
        break;

    case 'save': 
        if ($method === 'POST') {
            // Nếu có ID thì là update, ngược lại là thêm mới
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
                $result = $userController->update($id, $input);
                if (is_array($result) && isset($result['error']) && $result['error'] === 'duplicate') {
                    http_response_code(409);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message'],
                        'errors' => [ $result['field'] => $result['message'] ]
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo response("success", "Cập nhật user thành công", $result);
                }
            } else {
                $result = $userController->store($input);
                if (is_array($result) && isset($result['error']) && $result['error'] === 'duplicate') {
                    http_response_code(409);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message'],
                        'errors' => [ $result['field'] => $result['message'] ]
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo response("success", "Tạo user thành công", $result);
                }
            }
        } else {
            echo response("error", "Phương thức không hợp lệ");
        }
        break;

    case 'destroy': 
        if ($method === 'DELETE' && isset($_GET['id'])) {
            $result = $userController->destroy($_GET['id']);
            if ($result) {
                echo response("success", "Xóa user thành công");
            } else {
                echo response("error", "Xóa user thất bại");
            }
        } else {
            echo response("error", "Yêu cầu không hợp lệ");
        }
        break;

    default:
        echo response("error", "Route không tồn tại");
        break;
}