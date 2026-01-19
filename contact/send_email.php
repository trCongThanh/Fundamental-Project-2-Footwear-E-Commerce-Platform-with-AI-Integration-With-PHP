<?php
    require "PHPMailer-master/src/PHPMailer.php";
    require "PHPMailer-master/src/SMTP.php";
    require "PHPMailer-master/src/Exception.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer_name = $_POST['name'];
        $customer_email = $_POST['email'];
        $customer_phone = isset($_POST['phone']) ? $_POST['phone'] : 'Not provided';
        $customer_message = $_POST['message'];

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            $from_email = 'nhatdl.23it@vku.udn.vn'; // Your intermediate email
            $from_password = 'rapm itte jhzi qnsa'; // Your email password
            $from_name = 'Nhật'; // Sender's name

            $mail->Username = $from_email;
            $mail->Password = $from_password;
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom($from_email, $from_name);

            // Destination email
            $to_email = 'torikun2005@gmail.com'; // Replace with the destination email
            $mail->addAddress($to_email);

            // Email subject
            $mail->Subject = "New Message from Contact Form";

            // Email content
            $mail_content = "
                <h2>Customer Contact Information</h2>
                <p><b>Name:</b> {$customer_name}</p>
                <p><b>Email:</b> {$customer_email}</p>
                <p><b>Phone:</b> {$customer_phone}</p>
                <p><b>Message:</b><br>{$customer_message}</p>
            ";
            $mail->isHTML(true);
            $mail->Body = $mail_content;

            // Send email
            if ($mail->send()) { 
                // Return a JSON response on success
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Mail could not be sent."]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Mail could not be sent! Error: " . $e->getMessage()]);
        }
    }
?>
