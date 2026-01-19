<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $action = $data['action'] ?? null;
    if ($action === 'order') {
        $username = $data['username'];
        $email = $data['email'];
        $today = date('d-m-Y'); // Kết quả sẽ là: '2024-11-27'
        $orderId = $data['orderId'];
        $status = $data['status'];
        $message = $data['message'];
        $content = "
<body style=\"font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;\">
    <div style=\"max-width: 600px; margin: 20px auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;\">
        <div style=\"background: #232f3e; color: #ffffff; text-align: center; padding: 20px 10px;\">
            <h1 style=\"margin: 0;\">SHOESTORE</h1>
        </div>
        <div style=\"padding: 20px;\">
            <h2 style=\"color: #232f3e; font-size: 22px; margin-bottom: 10px;\">Hello $username,</h2>
            <p style=\"font-size: 16px; line-height: 1.5; color: #555;\">$message</p>
            
            <div style=\"margin: 20px 0; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 5px;\">
                <h3 style=\"font-size: 18px; color: #232f3e; margin-bottom: 10px;\">Order Details</h3>
                <div style=\"margin: 10px 0; display: flex; justify-content: space-between; font-size: 14px; color: #555;\">
                    <span><strong>Order ID:</strong></span>
                    <span>$orderId</span>
                </div>
                <div style=\"margin: 10px 0; display: flex; justify-content: space-between; font-size: 14px; color: #555;\">
                    <span><strong>Date Sent:</strong></span>
                    <span>$today</span>
                </div>
                <div style=\"margin: 10px 0; display: flex; justify-content: space-between; font-size: 14px; color: #555;\">
                    <span><strong>Status:</strong></span>
                    <span><strong style=\"color: green;\">$status</strong></span>
                </div>
            </div>
            
            <p style=\"font-size: 16px; line-height: 1.5; color: #555;\">You can track your shipment using the link below:</p>
            <a href=\"[Tracking Link]\" style=\"display: block; width: 100%; background: #ffa41b; color: #ffffff; text-align: center; padding: 12px 0; text-decoration: none; font-size: 16px; font-weight: bold; margin-top: 20px; border-radius: 5px;\">
                Track Your Order
            </a>
        </div>
        <div style=\"text-align: center; font-size: 12px; color: #aaa; margin: 20px 0; padding: 10px;\">
            <p style=\"margin: 5px 0;\">Need help? Visit our <a href=\"#\" style=\"color: #232f3e; text-decoration: none;\">Help Center</a>.</p>
            <p style=\"margin: 5px 0;\">&copy; 2024 SHOESTORE. All rights reserved.</p>
        </div>
    </div>
</body>";


        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shoestore251211@gmail.com';
            $mail->Password = 'utnmlfhjlvlgelwe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('shoestore251211@gmail.com', 'Shoes Store');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Voucher from ShoeStore';
            $mail->Body = $content;

            $mail->send();

            echo json_encode(['message' => 'Voucher sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        }
        exit;
    }
}
