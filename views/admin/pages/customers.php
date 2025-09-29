<?php
include 'views/constants/customer.php';

$customer = [
    
];

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
