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
    if ($action === 'voucher') {
        $username = $data['username'];
        $email = $data['email'];
        $voucherCode = $data['voucherCode'];
        $voucherValue = $data['voucherValue'];

        $content = "
        <div style='max-width: 600px; margin: 30px auto; border: 1px solid #4b5865; border-radius: 10px; padding: 20px; text-align: center; 
            background-image: url(\"https://img.freepik.com/premium-vector/shoes-pattern_761585-388.jpg\"); 
            background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; overflow: hidden;'>
            
            <div style='position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1;'></div>
            
            <div style='position: relative; z-index: 2;'>
                <div style='opacity: 0.85;padding: 15px; background-color: #4b5865; border-radius: 8px; margin-bottom: 20px;'>
                    <h1 style='margin: 0; color: #ffffff;'>SHOE STORE</h1>
                    <p style='margin: 5px 0 0; font-size: 16px; color: #c7d5e0;'>A Special Offer Just for You, $username</p>
                </div>
                
                <div style='opacity: 0.85;background-color: #2a475e; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <h2 style='color: #66c0f4;'>Get Ready to Save Big!</h2>
                    <p style='font-size: 16px; margin: 15px 0; color: #b8c6d9;'>
                        We're excited to offer you an exclusive discount voucher for your next purchase at Shoe Store. Use the code below to enjoy your savings:
                    </p>
                    <div style='background-color: #1b2838; padding: 10px; border: 2px dashed #66c0f4; display: inline-block; margin: 10px auto;'>
                        <p style='font-size: 20px; font-weight: bold; color: #66c0f4; margin: 0;'>VOUCHER CODE: <span style='color: #ffffff;'>$voucherCode</span></p>
                    </div>
                    <p style='font-size: 14px; margin: 15px 0; color: #b8c6d9;'>Use this voucher code at checkout to get $voucherValue off your order. Hurry, offer valid for a limited time!</p>
                </div>
                
                <div style='background-color: #4b5865; padding: 10px; border-radius: 8px;'>
                    <p style='margin: 0; font-size: 12px; color: #e5e5e5;'>Thank you for choosing Shoe Store. For support, contact us at <a href='mailto:shoestore251211@gmail.com' style='color: #66c0f4; text-decoration: none;'>support@shoestore.com</a>.</p>
                    <p style='margin: 5px 0 0; font-size: 12px; color: #e5e5e5;'>Follow us for more updates and offers!</p>
                </div>
            </div>
        </div>";

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
