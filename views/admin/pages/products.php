<?php

// Render
include 'views/constants/admin/product.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/ProductApi.php?route=index&page=$pageNumber";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$product = $result['data']['products'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$tableConfig = new ProductRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();


$columns = $table;
$data = $product;
$describe = $describe;

?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/products.css">
</head>
<body>
<?php
// build category options for product select
$categoryApiUrl = "http://localhost/webbanhang/apis/ProductCategoryApi.php?route=index&page=1&limit=1000";
$catResp = file_get_contents($categoryApiUrl);
$catData = json_decode($catResp, true);
$categoriesList = $catData['data']['categories'] ?? [];
$categoryOptions = [];
foreach ($categoriesList as $c) { $categoryOptions[$c['id']] = $c['name']; }
include 'views/includes/admin/StoreAndUpdateProduct.php';
?>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
