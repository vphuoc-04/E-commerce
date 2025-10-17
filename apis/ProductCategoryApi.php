<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/ProductCategoryController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$productCategoryController = new ProductCategoryController();

function response($status, $message, $data = null) {
    return json_encode([
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE);
}

switch ($path) {
    case 'index': 
        $categories = $productCategoryController->index();
        echo response("success", "Danh sách nhóm sản phẩm", $categories);
        break;

    case 'show':
        if (isset($_GET['id'])) {
            $category = $productCategoryController->show($_GET['id']);
            if ($category) {
                echo response("success", "Chi tiết nhóm sản phẩm", $category);
            } else {
                echo response("error", "Không tìm thấy nhóm sản phẩm");
            }
        } else {
            echo response("error", "Thiếu ID nhóm sản phẩm");
        }
        break;

    case 'save': 
        if ($method === 'POST') {
            // Nếu có ID thì là update, ngược lại là thêm mới
            if (!empty($_POST['id'])) {
                $id = $_POST['id'];
                $result = $productCategoryController->update($id, $input);
                if (is_array($result) && isset($result['error']) && $result['error'] === 'duplicate') {
                    http_response_code(409);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message'],
                        'errors' => [ $result['field'] => $result['message'] ]
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo response("success", "Cập nhật nhóm sản phẩm thành công", $result);
                }
            } else {
                $result = $productCategoryController->store($input);
                if (is_array($result) && isset($result['error']) && $result['error'] === 'duplicate') {
                    http_response_code(409);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message'],
                        'errors' => [ $result['field'] => $result['message'] ]
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    echo response("success", "Tạo nhóm sản phẩm thành công", $result);
                }
            }
        } else {
            echo response("error", "Phương thức không hợp lệ");
        }
        break;

    case 'destroy': 
        if ($method === 'DELETE' && isset($_GET['id'])) {
            $result = $productCategoryController->destroy($_GET['id']);
            if ($result) {
                echo response("success", "Xóa nhóm sản phẩm thành công");
            } else {
                echo response("error", "Xóa nhóm sản phẩm thất bại");
            }
        } else {
            echo response("error", "Yêu cầu không hợp lệ");
        }
        break;

    default:
        echo response("error", "Route không tồn tại");
        break;
}