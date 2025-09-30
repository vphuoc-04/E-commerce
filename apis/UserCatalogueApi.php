<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../controllers/UserCatalogueController.php';

$path   = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$userCatalogueController = new UserCatalogueController();

function response($status, $message, $data = null) {
    return json_encode([
        "status"  => $status,
        "message" => $message,
        "data"    => $data
    ], JSON_UNESCAPED_UNICODE);
}

switch ($path) {
    case 'index': 
        $catalogues = $userCatalogueController->index();
        echo response("success", "Danh sách catalogue", $catalogues);
        break;

    case 'show': 
        if (isset($_GET['id'])) {
            $catalogue = $userCatalogueController->show($_GET['id']);
            if ($catalogue) {
                echo response("success", "Chi tiết catalogue", $catalogue);
            } else {
                echo response("error", "Không tìm thấy catalogue");
            }
        } else {
            echo response("error", "Thiếu ID catalogue");
        }
        break;

    case 'store': 
        if ($method === 'POST') {
            $newCatalogue = $userCatalogueController->store($input);
            echo response("success", "Tạo catalogue thành công", $newCatalogue);
        } else {
            echo response("error", "Phương thức không hợp lệ");
        }
        break;

    case 'update': 
        if ($method === 'PUT' && isset($_GET['id'])) {
            $updatedCatalogue = $userCatalogueController->update($_GET['id'], $input);
            echo response("success", "Cập nhật catalogue thành công", $updatedCatalogue);
        } else {
            echo response("error", "Yêu cầu không hợp lệ");
        }
        break;

    case 'destroy':
        if ($method === 'DELETE' && isset($_GET['id'])) {
            $result = $userCatalogueController->destroy($_GET['id']);
            if ($result) {
                echo response("success", "Xóa catalogue thành công");
            } else {
                echo response("error", "Xóa catalogue thất bại");
            }
        } else {
            echo response("error", "Yêu cầu không hợp lệ");
        }
        break;

    default:
        echo response("error", "Route không tồn tại");
        break;
}
