<?php
include '../Admin/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$conn || $conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
    }

    $email = $data['email'];
    $newPassword = $data['password'];

    // Validate email and password (basic validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }
    if (strlen($newPassword) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Prepare the SQL statement to update the password
    $sql = "UPDATE user SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die(json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]));
    }

    $stmt->bind_param("ss", $hashedPassword, $email);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            background-color: rgba(30, 30, 30, 0.7);
            backdrop-filter: blur(4px);
            text-align: center;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .form-control {
            background-color: #333333;
            border: 1px solid #555555;
            color: #ffffff;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            background-color: #444444;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            margin: 0 5px;
            font-size: 24px;
            text-align: center;
            border-radius: 8px;
            background-color: #333333;
            border: 1px solid #555555;
            color: #ffffff;
        }

        .otp-input:focus {
            outline: none;
            border-color: #007bff;
            background-color: #444444;
        }

        .btn-submit {
            width: 100%;
            background-color: #007bff;
            border: none;
        }

        .btn-submit:hover {
            background-color: #7db5ff;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://mir-s3-cdn-cf.behance.net/project_modules/1400/6174e552428211.5935be01632c6.gif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            z-index: -1;
        }

        @media (max-width: 600px) {
            .background {
                background-image: url('https://mir-s3-cdn-cf.behance.net/project_modules/1400/6174e552428211.5935be01632c6.gif');
            }
        }

        /* Fade-in and fade-out classes */
        .fade-in {
            opacity: 1;
        }

        .fade-out {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="background"></div>
    <div class="container fade-in" id="emailForm">
        <h2 class="mb-3">Recover Password</h2>
        <p>Enter the email address associated with your account.</p>
        <input type="email" id="emailInput" class="form-control mb-3" placeholder="Nhập email"  required>
        <button type="button" onclick="showOtpForm()" class="btn btn-submit">Send OTP</button>
    </div>

    <div class="container" id="otpForm" style="display: none;">
        <h2 class="mb-3">Enter OTP</h2>
        <p>Please enter the OTP sent to your email.</p>
        <div class="d-flex justify-content-center mb-3">
            <input type="text" class="otp-input" maxlength="1" required>
            <input type="text" class="otp-input" maxlength="1" required>
            <input type="text" class="otp-input" maxlength="1" required>
            <input type="text" class="otp-input" maxlength="1" required>
            <input type="text" class="otp-input" maxlength="1" required>
            <input type="text" class="otp-input" maxlength="1" required>
        </div>
        <button type="button" onclick="verifyOtp()" class="btn btn-submit">Submit OTP</button>
    </div>

    <div class="container" id="passwordForm" style="display: none;">
        <h2 class="mb-3">Change Password</h2>
        <p>Enter your new password.</p>
        <input type="password" id="newPassword" class="form-control mb-3" placeholder="Mật khẩu mới" required>
        <input type="password" id="confirmPassword" class="form-control mb-3" placeholder="Xác nhận mật khẩu" required>
        <button type="button" onclick="submitNewPassword()" class="btn btn-submit">Update Password</button>
    </div>

    <script>
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1) {
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        document.querySelector('.btn-submit').focus();
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
        function toggleForms(hideFormId, showFormId) {
            const hideForm = document.getElementById(hideFormId);
            const showForm = document.getElementById(showFormId);

            hideForm.classList.remove("fade-in");
            hideForm.classList.add("fade-out");

            setTimeout(() => {
                hideForm.style.display = "none";
                hideForm.classList.remove("fade-out");

                showForm.style.display = "block";
                showForm.classList.add("fade-in");
            }, 500); // Wait for fade-out effect to complete
        }

        function showOtpForm() {
            const emailInput = document.getElementById("emailInput").value;
            if (emailInput) {
                toggleForms("emailForm", "otpForm");
                sendOtpTochangePassword();
            } else {
                alert("Vui lòng nhập email.");
            }
        }

        function verifyOtp() {
            let otpInputs = document.querySelectorAll(".otp-input");
            let otpCode = "";
            otpInputs.forEach(input => {
                otpCode += input.value;
            });
            if (otpCode === otp) {
                toggleForms("otpForm", "passwordForm");
            } else {
                alert("OTP không hợp lệ.");
            }
        }

        function submitNewPassword() {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword === confirmPassword && newPassword.length >= 6) {
                var changePasswordData = {
                    email: signUpEmail,
                    password: newPassword
                };
                fetch('changePassword.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(changePasswordData)
                    })
                    .then(response => response.text()) // Đổi sang text để kiểm tra phản hồi
                    .then(data => {
                        console.log('Raw response from PHP:', data); // Kiểm tra phản hồi gốc
                        try {
                            const jsonData = JSON.parse(data); // Chuyển đổi sang JSON
                            console.log('Parsed JSON:', jsonData);
                            alert(jsonData.message);
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
                    alert("Mật khẩu đã được cập nhật thành công!");
                    window.location.href = 'login.php';
        } else {
            alert("Mật khẩu không khớp hoặc không hợp lệ. Vui lòng thử lại.");
        }
        }
    </script>
    <script>
        let otp;
        let signUpEmail;
        function sendOtpTochangePassword() {
            signUpEmail = document.getElementById("emailInput").value;

            otp = generateOTP();
            var sendmailData = {
                action: 'changePassword',
                email: signUpEmail,
                otp: otp
            };
            fetch('sendEmailOTP.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(sendmailData)
                })
                .then(response => response.text()) // Đổi sang text để kiểm tra phản hồi
                .then(data => {
                    console.log('Raw response from PHP:', data); // Kiểm tra phản hồi gốc
                    try {
                        const jsonData = JSON.parse(data); // Chuyển đổi sang JSON
                        console.log('Parsed JSON:', jsonData);
                        alert(jsonData.message);
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }

        function generateOTP(length = 6) {
            // Tạo mã OTP 6 chữ số
            return Math.floor(100000 + Math.random() * 900000).toString();
        }
    </script>
</body>

</html>