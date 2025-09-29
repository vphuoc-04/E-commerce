<?php
require_once __DIR__ . '/../../../controllers/AuthController.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email === '' || $password === '') {
        $errors[] = "Vui lòng nhập email và mật khẩu.";
    } else {
        $authController = new AuthController();
        $result = $authController->login(['email' => $email, 'password' => $password]);
        
        if (isset($result['error'])) {
            $errors[] = $result['error'];
        } elseif ($result['status'] === 'success') {
            setcookie('token', $result['token'], [
                'expires' => time() + 3600, 
                'path' => '/',
                'secure' => false, 
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            setcookie('refresh_token', $result['refreshToken'], [
                'expires' => time() + 60*60*24*7,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            
            $_SESSION['user'] = $result['data'];
            
            header("Location: /WEBBANHANG/dashboard");
            exit;
        } else {
            $errors[] = "Đăng nhập thất bại";
        }
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/admin/css/login.css">
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

        <form method="post" action="login" novalidate>
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
