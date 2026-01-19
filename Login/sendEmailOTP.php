
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php'; // Corrected this line
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the JSON data from JavaScript
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    $action = $data['action'] ?? null;
if($action === 'signup'){
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];
    $otp = $data['otp'];
    $content = "
<div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; background-color: #f3f3f3; border: 1px solid #ddd; border-radius: 8px;'>
    <!-- Khung xanh nhạt -->
    <div style='background-color: #e0f7fa; color: #00796b; padding: 15px; text-align: center; border-radius: 5px;'>
        <h2>SHOES STORE</h2>
        <p style='font-weight: bold; margin: 0;'>XÁC THỰC TÀI KHOẢN KHI ĐĂNG KÍ</p>
    </div>
    
    <!-- Khoảng cách -->
    <div style='height: 20px;'></div>
    
    <!-- Khung vàng -->
    <div style='background-color: #fffde7; color: #f57c00; padding: 15px; border-radius: 5px; border: 1px solid #fbc02d; text-align: center;'>
        <p style='font-size: 16px; margin: 0;'>Bạn đang thực hiện xác thực thông tin tài khoản .</p>
        <p style='font-size: 24px; font-weight: bold; margin: 0;'>OTP - $otp</p>
    </div>

    <!-- Khoảng cách -->
    <div style='height: 20px;'></div>

    <!-- Khung xanh đậm -->
    <div style='background-color: #004d40; color: #ffffff; padding: 15px; border-radius: 5px; text-align: center;'>
        <p style='font-size: 14px; margin: 0;'>(*) Lưu ý: Mã OTP chỉ có giá trị trong vòng 30 phút.</p>
    </div>
</div>
";
    $mail = new PHPMailer(true); // Corrected variable name
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Corrected host name
        $mail->SMTPAuth = true;
        $mail->Username = 'shoestore251211@gmail.com'; // Your Gmail
        $mail->Password = 'utnmlfhjlvlgelwe'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Chọn TLS
        $mail->Port = 587; // Port cho TLS

        // Recipients
        $mail->setFrom('shoestore251211@gmail.com', 'Shoes Store'); // Set sender email and name
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'OTP from ShoeStore';
        $mail->Body    = $content;
        
        $mail->send();
        setcookie("otp", $otp, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("signUpUsername", $username, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("signUpEmail", $email, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie("signUpPassword", $password, time() + (86400 * 30), "/"); // 86400 = 1 day
        header("Location: checkOTP.php");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
    else{
    $email = $data['email'];
    $otp = $data['otp'];
    $content = "
<div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; background-color: #f3f3f3; border: 1px solid #ddd; border-radius: 8px;'>
    <!-- Khung xanh nhạt -->
    <div style='background-color: #e0f7fa; color: #00796b; padding: 15px; text-align: center; border-radius: 5px;'>
        <h2>SHOES STORE</h2>
        <p style='font-weight: bold; margin: 0;'>XÁC THỰC TÀI KHOẢN KHI ĐỔI MẬT KHẨU</p>
    </div>
    
    <!-- Khoảng cách -->
    <div style='height: 20px;'></div>
    
    <!-- Khung vàng -->
    <div style='background-color: #fffde7; color: #f57c00; padding: 15px; border-radius: 5px; border: 1px solid #fbc02d; text-align: center;'>
        <p style='font-size: 16px; margin: 0;'>Bạn đang thực hiện xác thực thay đổi mật khẻu trên hệ thống Shoe Store của chúng tôi .</p>
        <p style='font-size: 24px; font-weight: bold; margin: 0;'>OTP - $otp</p>
    </div>

    <!-- Khoảng cách -->
    <div style='height: 20px;'></div>

    <!-- Khung xanh đậm -->
    <div style='background-color: #004d40; color: #ffffff; padding: 15px; border-radius: 5px; text-align: center;'>
        <p style='font-size: 14px; margin: 0;'>(*) Lưu ý: Mã OTP chỉ có giá trị trong vòng 30 phút.</p>
    </div>
</div>
";
    $mail = new PHPMailer(true); // Corrected variable name
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Corrected host name
        $mail->SMTPAuth = true;
        $mail->Username = 'shoestore251211@gmail.com'; // Your Gmail
        $mail->Password = 'utnmlfhjlvlgelwe'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Chọn TLS
        $mail->Port = 587; // Port cho TLS

        // Recipients
        $mail->setFrom('shoestore251211@gmail.com', 'Shoes Store'); // Set sender email and name
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'OTP from ShoeStore';
        $mail->Body    = $content;
        
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
}
?>