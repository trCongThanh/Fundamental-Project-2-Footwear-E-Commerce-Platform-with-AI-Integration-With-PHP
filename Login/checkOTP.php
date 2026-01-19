<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }

        .otp-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            background-color: rgba(30, 30, 30, 0.7);
            backdrop-filter: blur(4px);
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

        .btn-submit:focus {
            background-color: #7db5ff;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://cdn.shopify.com/s/files/1/0223/7241/7610/files/ido-baner-full-black_2048x2048.gif?v=1596444626');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        /* Small screens */
        @media (max-width: 600px) {
            .background {
                background-image: url('https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExazh3aXI1empqdmY1bTRrMmx3bHJmZ2tsMTN1a3E2ZHhwbzdoMWhxaCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Z0qrajAVaSJcK87b9X/giphy.webp');
            }
        }
    </style>
</head>

<body>
    <div class="background"></div>
    <div class="otp-container text-center p-5">
        <h2 class="mb-3">Enter OTP Confirmation</h2>
        <p>Please enter the OTP sent to your email.</p>
        <form id="otpForm">
            <div class="d-flex justify-content-center mb-3">
                <input type="text" class="otp-input" maxlength="1" required>
                <input type="text" class="otp-input" maxlength="1" required>
                <input type="text" class="otp-input" maxlength="1" required>
                <input type="text" class="otp-input" maxlength="1" required>
                <input type="text" class="otp-input" maxlength="1" required>
                <input type="text" class="otp-input" maxlength="1" required>
            </div>
            <button type="button" id="submitOTP" class="btn btn-submit">Submit</button>
        </form>
        <p class="mt-3"><a id="resendOTP" href="#" class="text-light">Resend another OTP</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let correctOTP = "<?php echo isset($_COOKIE['otp']) ? $_COOKIE['otp'] : ''; ?>";
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

        document.querySelector('#submitOTP').addEventListener('click', () => {
            const otpValues = Array.from(document.querySelectorAll('.otp-input')).map(input => input.value);
            if (otpValues.join('') === correctOTP) {
                var signUpUsername = "<?php echo isset($_COOKIE['signUpUsername']) ? $_COOKIE['signUpUsername'] : ''; ?>";
                var signUpEmail = "<?php echo isset($_COOKIE['signUpEmail']) ? $_COOKIE['signUpEmail'] : ''; ?>";
                var signUpPassword = "<?php echo isset($_COOKIE['signUpPassword']) ? $_COOKIE['signUpPassword'] : ''; ?>";
                var signUpData = {
                    action: 'signup',
                    username: signUpUsername,
                    email: signUpEmail,
                    password: signUpPassword
                };

                fetch('login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(signUpData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response from PHP:', data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                // Clear OTP container and show success message
                document.querySelector('.otp-container').innerHTML = `
                        <h3 class="mb-4">Your account has been created successfully!</h2>
                        <p class="mb-4 text-start">Congratulations! You’re all set up and can now enjoy everything our website has to offer. Welcome to our community!!!</p>
                        <a href="login.php" class="btn btn-submit">Turn back to login page</a>
                    `;
            } else {
                alert("Mã OTP không chính xác. Vui lòng thử lại.");
            }
        });
        document.querySelector('#resendOTP').addEventListener('click', () => {
            var signUpUsername = "<?php echo isset($_COOKIE['signUpUsername']) ? $_COOKIE['signUpUsername'] : ''; ?>";
            var signUpEmail = "<?php echo isset($_COOKIE['signUpEmail']) ? $_COOKIE['signUpEmail'] : ''; ?>";
            var signUpPassword = "<?php echo isset($_COOKIE['signUpPassword']) ? $_COOKIE['signUpPassword'] : ''; ?>";

            var otp = generateOTP();
            var sendmailData = {
                username: signUpUsername,
                email: signUpEmail,
                password: signUpPassword,
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
                    window.location.href = 'checkOTP.php'
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
        });
        function generateOTP(length = 6) {
            // Tạo mã OTP 6 chữ số
            return Math.floor(100000 + Math.random() * 900000).toString();
        }
    </script>
</body>

</html>