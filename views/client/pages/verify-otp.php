<?php
$errors = [];
$success = $_GET['success'] ?? '';
$email = $_GET['email'] ?? '';

$baseUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/webbanhang/';
if (isset($_GET['error'])) {
    $errors[] = $_GET['error'];
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP - Web Bán Hàng</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>views/client/css/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-key"></i>
                    <span>Xác thực OTP</span>
                </div>
                <h1>Xác thực Email</h1>
                <p>Nhập mã xác thực đã được gửi đến email của bạn</p>
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

            <form method="post" action="<?= $baseUrl ?>handlers/auth-handler.php?action=verify-otp" class="login-form" novalidate>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i>Email</label>
                    <input id="email" name="email" type="email" value="<?= htmlspecialchars($email) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="otp_code"><i class="fas fa-key"></i>Mã OTP</label>
                    <input id="otp_code" name="otp_code" type="text" maxlength="6"
                           pattern="[0-9]{6}" placeholder="Nhập mã 6 số" required
                           style="text-align:center;font-size:24px;font-weight:bold;letter-spacing:8px;">
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-check"></i> Xác thực
                </button>
            </form>

            <div class="otp-actions">
                <button type="button" class="btn btn-outline" onclick="resendOTP()">
                    <i class="fas fa-redo"></i> Gửi lại mã
                </button>
                <a href="<?= $baseUrl ?>login" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
                </a>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = "<?= $baseUrl ?>";

        function resendOTP() {
            const email = document.getElementById('email').value;
            if (!email) return alert('Không tìm thấy email');
            
            fetch(baseUrl + 'handlers/auth-handler.php?action=resend-otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || (data.status === 'success' ? 'Đã gửi lại mã OTP!' : 'Gửi lại thất bại'));
                if (data.status === 'success') location.reload();
            })
            .catch(() => alert('Lỗi khi gửi lại mã OTP'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            const otp = document.getElementById('otp_code');
            otp.focus();
            otp.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    setTimeout(() => this.form.submit(), 300);
                }
            });
        });
    </script>
</body>
</html>
