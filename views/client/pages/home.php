<?php
include 'views/constants/client/home.php';

// Customs
include_once 'views/customs/CustomCard.php';
include_once 'views/customs/CustomButton.php';
include_once 'views/customs/CustomLoading.php';
include_once 'views/customs/CustomBanner.php';

$pageNumber = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageNumber < 1) $pageNumber = 1;

$apiUrl = "http://localhost/webbanhang/apis/ProductApi.php?route=index&page=$pageNumber";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

$products = $result['data']['products'] ?? [];
$pagination = $result['data']['pagination'] ?? [];

$columns = $home;

$spinner = new CustomLoading("25px", "25px", "#ffffff");
$buyButton = new CustomButton("Mua ngay", false, false, "buy-button", $spinner);
$cartButton = new CustomButton("Thêm vào giỏ hàng", false, false, "cart-button", $spinner);

$card = new CustomCard([
    new class {
        public function render($item) {
            return sprintf(
                '<div class="product-image"><img src="%s" alt="%s"></div>',
                htmlspecialchars($item['image']),
                htmlspecialchars($item['name'])
            );
        }
    },
    new class { 
        public function render($item) {
            return "<h3>{$item['name']}</h3>";
        }
    },
    new class { 
        public function render($item) {
            return "<div class='price'>" . number_format($item['price']) . " VNĐ</div>";
        }
    },
    $buyButton,
    $cartButton
]);

$banner = new CustomBanner(
    "Chào mừng bạn đến với cửa hàng của chúng tôi!",
    "Khám phá các sản phẩm mới nhất với ưu đãi hấp dẫn.",
    "http://localhost/WEBBANHANG/assets/banner.jpg",
    "Xem ngay",
    "?route=products"
);
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/client/css/home.css">
</head>
<body>
    <div class="container">
        <?= $banner->render([]) ?>
        <div class="product-grid">
            <?php foreach ($products as $item): ?>
                <?= $card->render($item) ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>