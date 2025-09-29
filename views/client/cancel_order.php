<?php $title = "Hủy đơn hàng"; ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
</head>
<body>
  <h1><?= $title ?></h1>

  <form>
    <label>Nhập mã đơn hàng cần hủy:</label><br>
    <input type="text" placeholder="Ví dụ: #1002"><br><br>

    <label>Lý do hủy:</label><br>
    <textarea rows="4" cols="40" placeholder="Nhập lý do..."></textarea><br><br>

    <button type="submit">Xác nhận hủy đơn</button>
  </form>
</body>
</html>
