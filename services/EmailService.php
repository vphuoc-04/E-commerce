<?php
require_once __DIR__ . '/../configs/email.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer() {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_ENCRYPTION;
            $this->mailer->Port = SMTP_PORT;
            $this->mailer->CharSet = 'UTF-8';

            $this->mailer->setFrom(FROM_EMAIL, FROM_NAME);
        } catch (Exception $e) {
            error_log("Email configuration error: " . $e->getMessage());
        }
    }

    public function sendOTP($toEmail, $toName, $otpCode) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Mã xác thực OTP - Web Bán Hàng';
            
            $htmlBody = $this->getOTPEmailTemplate($otpCode);
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = "Mã xác thực của bạn là: $otpCode. Mã này có hiệu lực trong " . OTP_EXPIRE_MINUTES . " phút.";

            $this->mailer->send();
            return ['status' => 'success', 'message' => 'Email đã được gửi thành công'];
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Không thể gửi email: ' . $e->getMessage()];
        }
    }

    private function getOTPEmailTemplate($otpCode) {
        return "
        <!DOCTYPE html>
        <html lang='vi'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mã xác thực OTP</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f78da7; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
                .otp-code { 
                    background: #fff; 
                    border: 2px dashed #667eea; 
                    padding: 20px; 
                    text-align: center; 
                    font-size: 32px; 
                    font-weight: bold; 
                    color: #667eea; 
                    letter-spacing: 5px; 
                    margin: 20px 0;
                    border-radius: 8px;
                }
                .warning { 
                    background: #fff3cd; 
                    border: 1px solid #ffeaa7; 
                    color: #856404; 
                    padding: 15px; 
                    border-radius: 5px; 
                    margin: 20px 0;
                }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔐 Xác thực tài khoản</h1>
                    <p>Mã xác thực OTP của bạn</p>
                </div>
                <div class='content'>
                    <h2>Xin chào!</h2>
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại Web Bán Hàng. Để hoàn tất quá trình đăng ký, vui lòng sử dụng mã xác thực bên dưới:</p>
                    
                    <div class='otp-code'>$otpCode</div>
                    
                    <div class='warning'>
                        <strong>⚠️ Lưu ý quan trọng:</strong>
                        <ul>
                            <li>Mã này chỉ có hiệu lực trong " . OTP_EXPIRE_MINUTES . " phút</li>
                            <li>Không chia sẻ mã này với bất kỳ ai</li>
                            <li>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này</li>
                        </ul>
                    </div>
                    
                    <p>Nếu bạn gặp vấn đề, vui lòng liên hệ với chúng tôi qua email hỗ trợ.</p>
                </div>
                <div class='footer'>
                    <p>© 2024 Web Bán Hàng. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
