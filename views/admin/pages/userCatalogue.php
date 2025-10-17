<?php

// Render
include 'views/constants/admin/userCatalogue.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/UserCatalogueApi.php?route=index&page=$pageNumber";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$userCatalogue = $result['data']['catalogues'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$tableConfig = new UserCatalogueRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();


$columns = $table;
$data = $userCatalogue;
$describe = $describe;
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/users.css">
</head>
<body>
<?php include 'views/includes/admin/StoreAndUpdateUserCatalogue.php'; ?>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>
