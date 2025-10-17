<?php

// Render
include 'views/constants/admin/productCategory.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/ProductCategoryApi.php?route=index&page=$pageNumber";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$category = $result['data']['categories'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$tableConfig = new ProductCategoryRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();


$columns = $table;
$data = $category;
$describe = $describe;
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/">
</head>
<body>
<?php include 'views/includes/admin/StoreAndUpdateProductCategory.php'; ?>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>