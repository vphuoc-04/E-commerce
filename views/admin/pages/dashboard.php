<?php
session_start();
//require_once 'config.php';

// // Nếu chưa đăng nhập thì quay lại login
// if (empty($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

// $userName = $_SESSION['user_name'] ?? 'Người dùng';

// Lấy tham số lọc
$keyword   = trim($_GET['q'] ?? '');
$minPrice  = trim($_GET['min'] ?? '');
$maxPrice  = trim($_GET['max'] ?? '');
$sort      = $_GET['sort'] ?? 'asc';

$query  = "SELECT * FROM products WHERE 1=1";
$params = [];

// Tìm theo tên
if ($keyword !== '') {
    $query .= " AND name LIKE ?";
    $params[] = "%$keyword%";
}

// Lọc theo khoảng giá
if ($minPrice !== '' && is_numeric($minPrice)) {
    $query .= " AND price >= ?";
    $params[] = (float)$minPrice;
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $query .= " AND price <= ?";
    $params[] = (float)$maxPrice;
}

// Sắp xếp
$order = ($sort === 'desc') ? 'DESC' : 'ASC';
$query .= " ORDER BY price $order";

// Thực thi
// $stmt = $pdo->prepare($query);
// $stmt->execute($params);
// $products = $stmt->fetchAll();s
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
</head>
<body>
  <h1>Dashboard
</body>
</html>
