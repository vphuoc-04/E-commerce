<?php
include_once 'views/customs/CustomButton.php';
include_once 'views/customs/CustomLoading.php';
include_once 'views/customs/CustomBanner.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<h2>Không tìm thấy sản phẩm</h2>";
    exit;
}

$apiUrl = "http://localhost/webbanhang/apis/ProductApi.php?route=show&id={$id}";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

if ($result['status'] !== 'success') {
    echo "<h2>{$result['message']}</h2>";
    exit;
}

$product = $result['data'] ?? [];

$spinner = new CustomLoading("25px", "25px", "#ffffff");
$buyButton = new CustomButton("Mua ngay", false, false, "buy-button", $spinner);
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($product['name'] ?? 'Chi tiết sản phẩm') ?></title>
    <link rel="stylesheet" href="http://localhost/webbanhang/views/client/css/single-product.css">
</head>
<body>
    <div class="single-product-container">
        <div class="product-image">
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="price"><?= number_format($product['price'] ?? 0, 0, ',', '.') ?> ₫</p>
            <p class="stock">Còn lại: <?= htmlspecialchars($product['stock'] ?? 0) ?></p>
            <p class="desc"><?= nl2br(htmlspecialchars($product['description'] ?? 'Không có mô tả')) ?></p>

            <div class="product-actions">
                <?= $buyButton->render([]) ?>
            </div>
        </div>
    </div>
</body>
</html>
