<?php
include '../Admin/database.php';
$currentRole = "";
// Check if there’s JSON data in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the JSON data from JavaScript
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON sent.']);
        exit;
    }

    // Ensure $conn is still open
    if (!$conn || $conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
        exit;
    }

    // Process the action
    $action = $data['action'] ?? null;
    if ($action === 'login') {
        // Login logic
        $usernameOrEmail = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        // Ensure fields are not empty
        if (!$usernameOrEmail || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'Username/Email and Password are required.']);
            exit;
        }

        $sql = "SELECT id, username, idCart, role, password FROM user WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $stmt->store_result();
        $result = $stmt->get_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $username, $idCart, $role, $hashed_password);
            $stmt->fetch();
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set cookies for idUser, username, and idCart with an expiration of 1 day

                // Clear existing cookies
                setcookie("idUser", "", time() - 3600, "/");
                setcookie("username", "", time() - 3600, "/");
                setcookie("idCart", "", time() - 3600, "/");
                setcookie("role", "", time() - 3600, "/");

                // Set new cookies
                setcookie("idUser", $user_id, time() + 86400, "/");
                setcookie("username", $username, time() + 86400, "/");
                setcookie("idCart", $idCart, time() + 86400, "/");
                setcookie("role", $role, time() + 86400, "/");

                // Include the role in the response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful!',
                    'role' => $role
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No user found with this username or email.']);
        }

        $stmt->close();
    } elseif ($action === 'signup') {
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$email || !$password) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required for sign up.']);
            exit;
        }

        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $conn->autocommit(false); // Start a transaction

        try {
            // Step 1: Insert into the `cart` table
            $cartSql = "INSERT INTO cart () VALUES ()"; // Adjust columns as needed
            if (!$conn->query($cartSql)) {
                throw new Exception('Error inserting into cart: ' . $conn->error);
            }

            // Get the ID of the newly created cart
            $idCart = $conn->insert_id;

            // Step 2: Insert into the `user` table
            $userSql = "INSERT INTO user (username, email, password, role, idCart) VALUES (?, ?, ?, 'customer', ?)";
            $stmt = $conn->prepare($userSql);
            if (!$stmt) {
                throw new Exception('Error preparing user insert statement: ' . $conn->error);
            }

            $stmt->bind_param("sssi", $username, $email, $hashed_password, $idCart);
            if (!$stmt->execute()) {
                throw new Exception('Error inserting into user: ' . $stmt->error);
            }

            $conn->commit(); // Commit the transaction
            echo json_encode(['status' => 'success', 'message' => 'Sign up successful!']);
        } catch (Exception $e) {
            $conn->rollback(); // Rollback the transaction on error
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } finally {
            $conn->autocommit(true); // Restore autocommit mode
            if (isset($stmt)) $stmt->close();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }

    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login & Sign Up</title>

</head>

<body>
    <video class="video-background" autoplay muted loop>
        <source src="video1.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>

    <div class="overlay"></div>
    <!-- Navbar -->
    <div class="navbar">
        <img src="logo.png" alt="Logo">
        <div class="menu-toggle">☰</div>
        <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Sales</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <!-- Login & Sign Up Form -->
    <div class="form-container">
        <div class="form-box" id="formBox">
            <!-- Login Form -->
            <div class="form login-form">
                <h2>Login</h2>
                <p style="text-align: left; font-size: 14px;"><i>“The cowards never started and the weak died along the way. That leaves us, ladies and gentlemen. Us.”</i></p>
                <p style="text-align: right; font-size: 12px; margin-top: -12px;">Phil Knight - Founder of Nike </p>
                <input id="loginusername" type="text" placeholder="Username" required>
                <input id="loginpassword" type="password" placeholder="Password" required>
                <button onclick="login()" type="submit">Login</button>
                <p style="text-align:center; margin-top:20px;">Don't have an account? <a style="color: grey" href="#" id="signUpLink">Sign up</a></p>
                <p style="text-align:center; margin-top:20px;">Forgot password? <a style="color: grey" href="changePassword.php" id="ForgotLink">Recovery Account</a></p>
            </div>

            <!-- Sign Up Form -->
            <div class="form signup-form">
                <h2>Sign Up</h2>
                <p style="text-align: left; font-size: 14px;"> <i>“No athlete should be held back by the limitations of his shoes.”</i></p>
                <p style="text-align: right; font-size: 12px; margin-top: -12px;">Adi Dassler - Founder of Adidas </p>
                <input id="signupusername" type="text" placeholder="Username" required>
                <input id="signupemail" type="email" placeholder="Email" required>
                <input id="signuppassword" type="password" placeholder="Password" required>
                <button onclick="signup()" type="submit">Sign Up</button>
                <p style="text-align:center; margin-top:20px;">Already have an account? <a style="color:grey" href="#" id="loginLink">Login</a></p>
            </div>
        </div>
    </div>
    <!-- Thêm SweetAlert2 từ CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle between login and signup form
        const formBox = document.getElementById('formBox');
        const signUpLink = document.getElementById('signUpLink');
        const loginLink = document.getElementById('loginLink');

        signUpLink.addEventListener('click', () => {
            formBox.classList.add('flipped');
        });

        loginLink.addEventListener('click', () => {
            console.log('Login clicked');
            formBox.classList.remove('flipped');
        });

        // Toggle menu in responsive mode
        const menuToggle = document.querySelector('.menu-toggle');
        const navbarMenu = document.querySelector('.navbar ul');

        menuToggle.addEventListener('click', () => {
            navbarMenu.classList.toggle('active');
        });
    </script>
    <script>
        function login() {
            let loginUsername = document.getElementById("loginusername").value;
            let loginPassword = document.getElementById("loginpassword").value;
            console.log('loginUsername:', loginUsername);
            console.log('loginPassword:', loginPassword);
            var loginData = {
                action: 'login',
                username: loginUsername,
                password: loginPassword
            };

            fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(loginData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from PHP:', data);
                    console.log('Current role:', data.role);
                    
                    // Sử dụng SweetAlert2 để hiển thị thông báo
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login successful!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            preConfirm: () => {
                                // Chuyển hướng đến homepage.php khi người dùng nhấn OK

                                if (data.role === 'customer') window.location.href = '../Shoes/homepage/homepage.php';
                                else window.location.href = '../Admin/index.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            confirmButtonText: 'Try again'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text: 'Please try again later.',
                        confirmButtonText: 'OK'
                    });
                });

        }

        function signup() {
            var signUpUsername = document.getElementById("signupusername").value;
            var signUpEmail = document.getElementById("signupemail").value;
            var signUpPassword = document.getElementById("signuppassword").value;

            var otp = generateOTP();
            var sendmailData = {
                action: 'signup',
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
        }

        function generateOTP(length = 6) {
            // Tạo mã OTP 6 chữ số
            return Math.floor(100000 + Math.random() * 900000).toString();
        }
    </script>
</body>

</html>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f0f0f0;
    }

    .navbar {
        width: 100%;
        background-color: #333;
        padding: 10px;
        position: fixed;
        top: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .navbar img {
        height: 40px;
    }

    .navbar ul {
        list-style-type: none;
        display: flex;
        justify-content: flex-end;
        color: white;
        flex-grow: 1;
    }

    .navbar ul li {
        display: inline-block;
        margin-left: 20px;
    }

    .navbar ul li a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
    }

    .menu-toggle {
        display: none;
        cursor: pointer;
        color: white;
        background-color: #333;
        padding: 10px;
        text-align: center;
    }

    .menu-toggle:hover {
        background-color: #555;
    }

    /* Form container */
    .form-container {
        perspective: 1000px;
        width: 100%;
        max-width: 400px;
        position: relative;
        margin-top: 80px;
        opacity: 0.9;
    }

    .form-box {
        width: 100%;
        height: 500px;
        background-color: rgba(255, 255, 255, 0);
        border-radius: 10px;
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        transform-style: preserve-3d;
        transition: transform 0.6s;
    }

    .form-box.flipped {
        transform: rotateX(180deg);
    }

    .form {
        border-radius: 20px;
        position: absolute;
        width: 100%;
        height: 100%;
        padding: 40px;
        backface-visibility: hidden;
    }

    .form h2 {
        margin-bottom: 10px;
        color: #333;
        text-align: center;
    }

    .form p {
        margin-bottom: 20px;
        color: #666;
        text-align: center;
    }

    .form input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form button {
        width: 100%;
        padding: 10px;
        background-color: #333;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .form button:hover {
        background-color: #555;
    }

    /* Login form background and text color */
    .login-form {
        background-color: white;
        color: black;
    }

    .login-form h2,
    .login-form p {
        color: black;
    }

    /* Sign up form background and text color */
    .signup-form {
        background-color: black;
        color: white;
        transform: rotateX(180deg);
    }

    .signup-form h2,
    .signup-form p {
        color: white;
    }

    @media (max-width: 768px) {
        .navbar ul {
            flex-direction: column;
            display: none;
            background-color: #333;
            padding: 20px;
        }

        .navbar ul.active {
            display: flex;
        }

        .menu-toggle {
            display: block;
        }

        .navbar img {
            display: none;
        }
    }

    .video-background {
        position: fixed;
        top: 55%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -1;
        transform: translate(-50%, -50%);
        object-fit: cover;
        /* Đảm bảo video phủ kín */
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Màu đen với độ trong suốt 50% */
        z-index: 0;
    }
</style>