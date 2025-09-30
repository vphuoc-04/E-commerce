<?php 
$page = $_GET['page'] ?? 'dashboard';
$model = $page; 

if (isset($_GET['page_number'])) {
    $pageNumber = (int)$_GET['page_number'];
    
} elseif (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pageNumber = (int)$_GET['page'];
    $page = basename($_SERVER['REQUEST_URI']);
    $page = explode('?', $page)[0];
    $model = $page; 

} else {
    $pageNumber = 1;
}

if ($pageNumber < 1) $pageNumber = 1;

switch ($page) {
    case 'login':
        $content = 'views/admin/pages/login.php';
        $title = "login";
        break;

    case 'dashboard':
        $content = 'views/admin/pages/dashboard.php';
        $title = "dashboard";
        $breadcrumb = [
            "pageTitle" => "BẢNG ĐIỀU KHIỂN",
            "items" => [
                ["label" => "Dashboard"],
                ["label" => "Tổng quan"]
            ]
        ];
        break;

    case 'users':
        $content = 'views/admin/pages/users.php';
        $title = "users";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH TẤT CẢ NGƯỜI DÙNG",
            "items" => [
                ["label" => "Người dùng"],
                ["label" => "Tất cả người dùng"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'catalogues':
        $content = 'views/admin/pages/userCatalogue.php';
        $title = "catalogues";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH TẤT CẢ NHÓM NGƯỜI DÙNG",
            "items" => [
                ["label" => "Nhóm người dùng"],
                ["label" => "Nhóm người dùng"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'customers':
        $content = 'views/admin/pages/customers.php';
        $title = "customers";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH KHÁCH HÀNG",
            "items" => [
                ["label" => "Người dùng"],
                ["label" => "Khách hàng"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'employees':
        $content = 'views/admin/pages/employees.php';
        $title = "employees";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH NHÂN VIÊN",
            "items" => [
                 ["label" => "Người dùng"],
                ["label" => "Nhân viên"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'orders':
        $content = 'views/admin/pages/orders.php';
        $title = "orders";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH ĐƠN HÀNG",
            "items" => [
                ["label" => "Đơn hàng"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'products':
        $content = 'views/admin/pages/products.php';
        $title = "products";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH SẢN PHẨM",
            "items" => [
                ["label" => "Sản phẩm"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    case 'vouchers':
        $content = 'views/admin/pages/vouchers.php';
        $title = "promotions";
        $breadcrumb = [
            "pageTitle" => "QUẢN LÝ DANH SÁCH KHUYẾN MÃI",
            "items" => [
                ["label" => "Khuyến mãi"],
                ["label" => "Danh sách"]
            ]
        ];
        break;

    default:
        $content = 'views/admin/pages/404.php';
        $title = "404 Not Found";
        $breadcrumb = [
            "pageTitle" => "Không tìm thấy trang",
            "items" => [
                ["label" => "Lỗi 404"]
            ]
        ];
        break;
}