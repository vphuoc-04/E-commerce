<?php
// register.php
session_start();
//require_once 'config.php';

$errors = [];
$success = false;

// Khai báo biến dùng trong HTML (để giữ giá trị sau submit)
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và lọc dữ liệu đầu vào
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password_confirm'] ?? '';

    // Validate cơ bản
    if ($name === '') $errors[] = "Bạn phải nhập tên.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";
    if (strlen($pass) < 6) $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
    if ($pass !== $pass2) $errors[] = "Mật khẩu xác nhận không khớp.";

    // Kiểm tra email đã tồn tại
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email này đã được đăng ký.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        $success = true;
        // Xoá giá trị để form trống lại (tuỳ bạn)
        $name = $email = '';
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng ký</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
      <h1>Đăng ký tài khoản</h1>

      <!-- Hiển thị lỗi (biến PHP $errors được dùng trực tiếp trong HTML) -->
      <?php if (!empty($errors)): ?>
        <div class="error">
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <p class="success">Đăng ký thành công. <a href="login.php">Đăng nhập ngay</a></p>
      <?php endif; ?>

      <!-- Form: chú ý sử dụng biến PHP trong các thẻ HTML (value, placeholder) -->
      <form method="post" action="register.php" novalidate>
          <div class="input">
        <label for="name">Họ & tên</label>
        <input id="name" name="name" type="text" placeholder="Nguyễn Văn A"
              value="<?= htmlspecialchars($name) ?>" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="you@example.com"
              value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" placeholder="Ít nhất 6 ký tự" required>

        <label for="password_confirm">Xác nhận mật khẩu</label>
        <input id="password_confirm" name="password_confirm" type="password" required>

        <div class="row">
          <button type="submit">Đăng ký</button>
        </div>
      </form>

      <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</body>
</html>
