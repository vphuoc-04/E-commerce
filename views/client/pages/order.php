<?php $title = "Mua hàng"; ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
</head>
<body>
  <h1><?= $title ?></h1>

  <p>Thông tin sản phẩm bạn chọn:</p>
  <ul>
    <li>Tên sản phẩm: Laptop Dell</li>
    <li>Giá: 15.000.000 VND</li>
    <li>Số lượng: <input type="number" value="1" min="1"></li>
  </ul>

  <button>Xác nhận mua hàng</button>
</body>
</html>
