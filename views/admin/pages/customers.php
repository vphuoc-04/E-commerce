<?php

// Render
include 'views/constants/admin/customer.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/UserApi.php?route=index&page=$pageNumber&catalogue_id=2";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$customer = $result['data']['users'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$tableConfig = new CustomerRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();


$columns = $table;
$data = $customer;
$describe = $describe;
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/customers.css">
</head>
<body>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
