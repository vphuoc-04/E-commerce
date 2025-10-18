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
    <div class="footer-top">
        <div class="footer-content">
            <div class="footer-column footer-logo">
                <!-- <img src="<?= $logo ?>" alt="Logo doanh nghiệp"> -->
                <h3><?= $name ?></h3>
                <p class="tagline">Mỹ phẩm chính hãng - Tự tin mỗi ngày</p>
            </div>

            <div class="footer-column footer-info">
                <h4>Liên hệ</h4>
                <p><i class="fa-solid fa-location-dot"></i> <?= $address ?></p>
                <p><i class="fa-solid fa-envelope"></i> <?= $email ?></p>
                <p><i class="fa-solid fa-phone"></i> <?= $phone ?></p>
            </div>

            <div class="footer-column footer-desc">
                <h4>Giới thiệu</h4>
                <p><?= $description ?></p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> <?= $name ?>. All rights reserved.</p>
    </div>
</footer>
