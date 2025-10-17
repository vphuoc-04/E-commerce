<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/ProductController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$productController = new ProductController();

function response($status, $message, $data = null) {
    return json_encode([
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE);
}

switch ($path) {
    case 'index': 
        $products = $productController->index();
        echo response("success", "Danh sách nhóm sản phẩm", $products);
        break;

    case 'show':
        if (isset($_GET['id'])) {
            $product = $productController->show($_GET['id']);
            if ($product) {
                echo response("success", "Chi tiết nhóm sản phẩm", $product);
            } else {
                echo response("error", "Không tìm thấy nhóm sản phẩm");
            }
        } else {
            echo response("error", "Thiếu ID nhóm sản phẩm");
        }
        break;

    case 'save': 
        if ($method === 'POST') {
            $file = $_FILES['image'] ?? null;

            // Nếu có ID thì là update, ngược lại là thêm mới
            if (!empty($_POST['id']) || !empty($input['id'])) {
                $id = $_POST['id'] ?? $input['id'];
                $result = $productController->update($id, $input, $file);
                echo response("success", "Cập nhật sản phẩm thành công", $result);
            } else {
                $result = $productController->store($input, $file);
                echo response("success", "Tạo sản phẩm thành công", $result);
            }
        } else {
            echo response("error", "Phương thức không hợp lệ");
        }
        break;


    case 'destroy': 
        if ($method === 'DELETE' && isset($_GET['id'])) {
            $result = $productController->destroy($_GET['id']);
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
