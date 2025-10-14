<?php
$errors = [];
$success = $_GET['success'] ?? '';
$name = $_GET['name'] ?? '';
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
    <title>Đăng ký - Web Bán Hàng</title>
    <link rel="stylesheet" href="http://localhost/WEBBANHANG/views/client/css/register.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Web Bán Hàng</span>
                </div>
                <h1>Đăng ký tài khoản</h1>
                <p>Tạo tài khoản mới để bắt đầu mua sắm</p>
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
                <div class="alert alert-info">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Vui lòng xác thực email!</strong>
                        <p>Chúng tôi đã gửi mã xác thực đến email của bạn. Vui lòng kiểm tra và nhập mã OTP bên dưới.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" action="handlers/auth-handler.php?action=register" class="register-form" novalidate>
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i>
                        Họ và tên
                    </label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        placeholder="Nhập họ và tên đầy đủ"
                        value="<?= htmlspecialchars($name) ?>" 
                        required
                        autocomplete="name"
                    >
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        placeholder="Nhập địa chỉ email"
                        value="<?= htmlspecialchars($email) ?>" 
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
                            placeholder="Tối thiểu 6 ký tự"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Nhập mật khẩu</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirm">
                        <i class="fas fa-lock"></i>
                        Xác nhận mật khẩu
                    </label>
                    <div class="password-input">
                        <input 
                            id="password_confirm" 
                            name="password_confirm" 
                            type="password" 
                            placeholder="Nhập lại mật khẩu"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" value="1" required>
                        <span class="checkmark"></span>
                        Tôi đồng ý với <a href="#" class="terms-link">Điều khoản sử dụng</a> và <a href="#" class="terms-link">Chính sách bảo mật</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-register">
                    <i class="fas fa-user-plus"></i>
                    Đăng ký tài khoản
                </button>
            </form>

            <div id="otpForm" class="otp-form" style="display: none;">
                <h3><i class="fas fa-key"></i> Xác thực Email</h3>
                <p class="otp-description">Chúng tôi đã gửi mã xác thực 6 số đến email <strong><?= htmlspecialchars($email) ?></strong></p>
                
                <form method="post" action="verify-otp" class="otp-verification-form">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    
                    <div class="otp-input-container">
                        <input 
                            id="otp_code" 
                            name="otp_code" 
                            type="text" 
                            placeholder="Nhập mã 6 số"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            required
                            autocomplete="off"
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

            <div class="register-footer">
                <div class="divider">
                    <span>hoặc</span>
                </div>
                <p>Đã có tài khoản? 
                    <a href="login" class="login-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập ngay
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleBtn = passwordInput.parentElement.querySelector('.password-toggle i');
            
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

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let strengthLabel = '';
            let strengthColor = '';
            
            if (password.length >= 6) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            switch (strength) {
                case 0:
                case 1:
                    strengthLabel = 'Rất yếu';
                    strengthColor = '#e53e3e';
                    break;
                case 2:
                    strengthLabel = 'Yếu';
                    strengthColor = '#dd6b20';
                    break;
                case 3:
                    strengthLabel = 'Trung bình';
                    strengthColor = '#d69e2e';
                    break;
                case 4:
                    strengthLabel = 'Mạnh';
                    strengthColor = '#38a169';
                    break;
                case 5:
                    strengthLabel = 'Rất mạnh';
                    strengthColor = '#2f855a';
                    break;
            }
            
            strengthFill.style.width = (strength / 5 * 100) + '%';
            strengthFill.style.backgroundColor = strengthColor;
            strengthText.textContent = strengthLabel;
            strengthText.style.color = strengthColor;
        }

        function resendOTP() {
            const email = document.querySelector('input[name="email"]').value;
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
        document.getElementById('password').addEventListener('input', checkPasswordStrength);

        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($requires_verification): ?>
                document.getElementById('otpForm').style.display = 'block';
                document.querySelector('.register-form').style.display = 'none';
                document.getElementById('otp_code').focus();
            <?php endif; ?>
        });
    </script>
</body>
</html>
