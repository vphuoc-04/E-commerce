<?php

// Render
include_once 'views/constants/admin/user.php';

// Controller
include __DIR__ . '/../../../controllers/UserController.php';

$userController = new UserController();

// LUÔN có page
$pageNumber = $userController->getPage();

// API URL sẽ tự động bao gồm tất cả filter hiện tại
$apiUrl = $userController->buildUserApiUrl($pageNumber);

$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$user = $result['data']['users'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

// Lấy filter config từ controller
$filterFieldsConfig = $userController->getFilterFieldsConfig();

// Build catalogue options for the user sheet select
$cataloguesOptions = [];
foreach ($filterFieldsConfig as $fieldCfg) {
    if (($fieldCfg['name'] ?? '') === 'catalogue_id' && isset($fieldCfg['options']) && is_array($fieldCfg['options'])) {
        $cataloguesOptions = $fieldCfg['options'];
        break;
    }
}

$tableConfig = new UserRender();

$describe = $tableConfig->getDescribe();
$table = $tableConfig->getTable();
$buttonAction = $tableConfig->getButtonAction();
$buttonTableActions = $tableConfig->getButtonTableActions();

$columns = $table;
$data = $user;
$describe = $describe;

$currentFilters = $_GET;
$filterFields = $filterFieldsConfig; 
    $showFilter = true; 
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'views/includes/admin/StoreAndUpdateUser.php'; ?>
<div class="container">
    <?php include 'views/customs/CustomTable.php'; ?>
</div>
</body>
</html>