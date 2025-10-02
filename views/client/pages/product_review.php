<?php $title = "Bình luận & Đánh giá sản phẩm"; ?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; }
    .review-form { border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .review-list { margin-top: 20px; }
    .review-item { border-bottom: 1px solid #ddd; padding: 10px 0; }
    .stars { color: gold; font-size: 18px; }
  </style>
</head>
<body>
  <h1><?= $title ?></h1>

  <!-- Form bình luận -->
  <div class="review-form">
    <h3>Viết đánh giá của bạn</h3>
    <form>
      <label>Chọn số sao:</label><br>
      <select>
        <option>⭐</option>
        <option>⭐⭐</option>
        <option>⭐⭐⭐</option>
        <option>⭐⭐⭐⭐</option>
        <option>⭐⭐⭐⭐⭐</option>
      </select><br><br>

      <label>Bình luận:</label><br>
      <textarea rows="4" cols="50" placeholder="Nhập nội dung đánh giá..."></textarea><br><br>

      <button type="submit">Gửi đánh giá</button>
    </form>
  </div>

  <!-- Danh sách bình luận -->
  <div class="review-list">
    <h3>Đánh giá gần đây</h3>

    <div class="review-item">
      <div class="stars">⭐⭐⭐⭐⭐</div>
      <p><b>Nguyễn Văn A</b>: Sản phẩm rất tốt, giao hàng nhanh!</p>
      <small>Ngày: 10/09/2025</small>
    </div>

    <div class="review-item">
      <div class="stars">⭐⭐⭐</div>
      <p><b>Trần Thị B</b>: Chất lượng ổn, nhưng giao hơi chậm.</p>
      <small>Ngày: 12/09/2025</small>
    </div>
  </div>
</body>
</html>
