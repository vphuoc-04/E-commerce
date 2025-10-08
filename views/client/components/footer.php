<?php
// Gọi API để lấy thông tin business (ví dụ id = 1)
$apiUrl = "http://localhost/webbanhang/apis/BusinessApi.php?route=show&id=1";
$response = file_get_contents($apiUrl);
$result = json_decode($response, true);

// Lấy dữ liệu business (hoặc mảng rỗng nếu lỗi)
$business = $result['data'] ?? [];

$name        = htmlspecialchars($business['name'] ?? 'Tên doanh nghiệp');
$address     = htmlspecialchars($business['address'] ?? 'Địa chỉ chưa cập nhật');
$email       = htmlspecialchars($business['email'] ?? 'Email chưa cập nhật');
$phone       = htmlspecialchars($business['phone'] ?? 'Số điện thoại chưa cập nhật');
$description = htmlspecialchars($business['description'] ?? 'Mô tả doanh nghiệp');
$logo        = htmlspecialchars($business['logo'] ?? 'http://localhost/WEBBANHANG/assets/default-logo.png');
?>

<link rel="stylesheet" href="http://localhost/WEBBANHANG/views/client/css/component.css">

<footer class="footer-container">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="<?= $logo ?>" alt="Logo doanh nghiệp">
            <h3><?= $name ?></h3>
        </div>
        <div class="footer-info">
            <p><strong>Địa chỉ:</strong> <?= $address ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Điện thoại:</strong> <?= $phone ?></p>
        </div>
        <div class="footer-desc">
            <p><?= $description ?></p>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= $name ?>. All rights reserved.</p>
        </div>
    </div>
</footer>
