<?php
// Xử lý đăng xuất
session_start();

// Xóa tất cả session data
$_SESSION = array();

// Xóa session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy session
session_destroy();

// Redirect về trang chủ
header('Location: home');
exit;
?>
