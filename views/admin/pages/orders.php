<?php

// Render
include 'views/constants/admin/order.php';

$order = [
    
];

$tableConfig = new OrderRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();

$columns = $table;
$data = $order;
$describe = $describe;

?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/orders.css">
</head>
<body>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
