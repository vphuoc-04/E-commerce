<?php

// Render
include 'views/constants/admin/employee.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/UserApi.php?route=index&page=$pageNumber&catalogue_id=3";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$employee = $result['data']['users'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$tableConfig = new EmployeeRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();


$columns = $table;
$data = $employee;
$describe = $describe;

?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/orders.css">
</head>
<body>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
