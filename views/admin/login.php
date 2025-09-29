<?php
// login.php
session_start();
// require_once 'config.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $errors[] = "Vui lòng nhập email và mật khẩu.";
    } else {
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($pass, $user['password'])) {
            // Đăng nhập thành công -> lưu session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // Redirect hoặc hiển thị
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = "Email hoặc mật khẩu không đúng.";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="container">
    <h1>Đăng nhập</h1>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="login.php" novalidate>
      <div class="input">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" required>
      </div>

      <div style="margin-top:10px">
        <button type="submit">Đăng nhập</button>
      </div>
    </form>

    <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
  </div>
</body>
</html>