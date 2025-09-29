<?php
session_start();

// Nếu đã đăng nhập thì chuyển thẳng vào dashboard
if (!empty($_SESSION['user_id'])) {
    header("Location: views/login.php");
    exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Trang chủ</title>
  
</head>
<body>
  <div class="container">
    <h1>Xin chào!</h1>
    <p>Chào mừng bạn đến hệ thống.</p>
    <a href="views/login.php">Đăng nhập</a>
    <a href="views/register.php">Đăng ký</a>
  </div>
</body>
</html>
