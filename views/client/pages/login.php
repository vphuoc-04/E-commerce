<?php
$errors = [];
$success = $_GET['success'] ?? '';
$email = $_GET['email'] ?? '';
$requires_verification = isset($_GET['requires_verification']) ? (bool)$_GET['requires_verification'] : false;

if (isset($_GET['error'])) {
    $errors[] = $_GET['error'];
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Web Bán Hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/client/css/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Web Bán Hàng</span>
                </div>
                <h1>Đăng nhập</h1>
                <p>Chào mừng bạn quay trở lại!</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                          <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($requires_verification): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Tài khoản chưa được xác thực!</strong>
                        <p>Vui lòng kiểm tra email và nhập mã OTP để xác thực tài khoản.</p>
                        <button type="button" class="btn btn-outline" onclick="showOTPForm()">
                            <i class="fas fa-key"></i> Nhập mã OTP
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" action="handlers/auth-handler.php?action=login" class="login-form" novalidate>
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        value="<?= htmlspecialchars($email) ?>" 
                        placeholder="Nhập email của bạn"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Mật khẩu
                    </label>
                    <div class="password-input">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            placeholder="Nhập mật khẩu"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" value="1">
                        <span class="checkmark"></span>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Đăng nhập
                </button>
            </form>

            <!-- OTP Verification Form (Hidden by default) -->
            <div id="otpForm" class="otp-form" style="display: none;">
                <h3><i class="fas fa-key"></i> Xác thực OTP</h3>
                <form method="post" action="verify-otp" class="otp-verification-form">
                    <div class="form-group">
                        <label for="otp_email">Email</label>
                        <input 
                            id="otp_email" 
                            name="email" 
                            type="email" 
                            value="<?= htmlspecialchars($email) ?>" 
                            readonly
                        >
                    </div>
                    <div class="form-group">
                        <label for="otp_code">Mã OTP</label>
                        <input 
                            id="otp_code" 
                            name="otp_code" 
                            type="text" 
                            placeholder="Nhập mã 6 số"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            required
                        >
                    </div>
                    <div class="otp-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i>
                            Xác thực
                        </button>
                        <button type="button" class="btn btn-outline" onclick="resendOTP()">
                            <i class="fas fa-redo"></i>
                            Gửi lại mã
                        </button>
                    </div>
                </form>
            </div>

            <div class="login-footer">
                <div class="divider">
                    <span>hoặc</span>
                </div>
                <p>Chưa có tài khoản? 
                    <a href="register" class="register-link">
                        <i class="fas fa-user-plus"></i>
                        Đăng ký ngay
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.remove('fa-eye');
                toggleBtn.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('fa-eye-slash');
                toggleBtn.classList.add('fa-eye');
            }
        }

        function showOTPForm() {
            document.getElementById('otpForm').style.display = 'block';
            document.querySelector('.login-form').style.display = 'none';
        }

        function resendOTP() {
            const email = document.getElementById('otp_email').value;
            if (email) {
                fetch('resend-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Mã OTP mới đã được gửi đến email của bạn!');
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Có lỗi xảy ra khi gửi lại mã OTP');
                });
            }
        }

        // Auto-focus on OTP input when shown
        document.addEventListener('DOMContentLoaded', function() {
            const otpForm = document.getElementById('otpForm');
            if (otpForm.style.display !== 'none') {
                document.getElementById('otp_code').focus();
            }
        });
    </script>
</body>
</html>
