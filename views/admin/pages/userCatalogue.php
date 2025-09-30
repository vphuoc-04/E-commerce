<?php
include 'views/constants/user_catalogue.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/UserCatalogueApi.php?route=index&page=$pageNumber";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$user = $result['data']['catalogues'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$columns = $table;
$data = $user;
$describe = $describe;
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý Catalogue Người dùng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/users.css">
</head>
<body>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
