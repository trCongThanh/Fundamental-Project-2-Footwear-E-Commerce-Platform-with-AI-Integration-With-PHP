<?php
$idUser = "";
$username = "";
$idCart = "";

// Kiểm tra cookie
if (isset($_COOKIE["idUser"]) && isset($_COOKIE["username"]) && isset($_COOKIE["idCart"])) {
    $idUser = $_COOKIE["idUser"];
    $username = $_COOKIE["username"];
    $idCart = $_COOKIE["idCart"];
} else {
    // Chuyển hướng nếu không có cookie
    header("Location: login.php");
    exit();
}

include('../homepage/database.php');

// Truy vấn thông tin người dùng
$sql = "SELECT username, avatar, email, phone FROM user WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

// Truy vấn địa chỉ của người dùng
$sql2 = "SELECT name, address_line, country, city FROM address WHERE user_id=? AND is_default=1";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $idUser);
$stmt2->execute();
$result2 = $stmt2->get_result();
$address = $result2->fetch_assoc();
$stmt2->close();

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: grey;
        }

        .container {
            background-color: white;
            border-radius: 10px;
        }

        .d-flex-column {
            display: flex;
            flex-direction: column;
            /* Xếp theo chiều dọc */
            align-items: center;
            /* Canh giữa các phần tử */
            gap: 1rem;
            /* Khoảng cách giữa các phần tử */
        }

        html * {
            -webkit-font-smoothing: antialiased;
            font-family: Roboto, Helvetica, Arial, sans-serif;

        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow">
        <div class="container">
            <!-- Hamburger Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo Centered -->
            <a class="navbar-brand mx-auto" onclick="window.location.href='../homepage/homepage.php'">ShoeStore</a>

            <!-- Navbar Links and Buttons -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Links -->
                <ul class="navbar-nav me-auto flex-column flex-lg-row align-items-lg-center gap-0 gap-lg-5">
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=male'">Male</a></li>
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=female'">Female</a></li>
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=unisex'">Unisex</a></li>
                </ul>

                <!-- Right Buttons <i class="bi bi-search"></i> -->
                <div class="d-flex mt-3 mt-lg-0 justify-content-lg-end gap-0 gap-lg-5">
                    <button class="btn btn-outline-secondary me-2 nav-btn" onclick="window.location.href='../../Search/search.php?keyword=&price='">
                        <i class="bi bi-search"></i> Search
                    </button>

                    <button class="btn btn-outline-success me-2 nav-btn" id="cartButton">
                        <i class="bi bi-cart"></i> Cart
                    </button>
                    <script>
                        document.getElementById("cartButton").addEventListener("click", function() {
                            const currentUserId = <?php echo $idUser; ?>;
                            window.location.href = `../cart/cart.php?user_id=${currentUserId}`;
                        });
                    </script>
                    <button class="btn btn-outline-primary nav-btn" onclick="window.location.href='../profile/profile.php'">
                        <i class="bi bi-person"></i> Profile
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <style>
        /* Logo Styling */
        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Center Links on Responsive */
        @media (max-width: 991.98px) {
            .navbar-nav {
                align-items: flex-start;
                /* Links stack vertically */
            }

            .navbar-collapse .d-flex {
                justify-content: center;
                /* Buttons remain centered below menu */
                flex-wrap: wrap;
            }

            .nav-btn {
                width: 100%;
                /* Full width buttons on small screens */
                margin: 5px 0;
            }
        }

        /* Navbar Styling */
        .nav-link {
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #007bff;
            text-decoration: none;
        }

        .nav-btn {
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive: Align menu in column on small devices */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                display: flex;
                flex-direction: column;
            }

            .navbar-nav {
                flex-direction: column;
            }

            .nav-btn {
                width: 100%;
                margin: 5px 0;
            }
        }

        /* Social Media Links */
        .social-link {
            color: #fff;
            font-size: 1.5rem;
            transition: color 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            /* Xóa dấu gạch chân */
        }

        .social-link:hover {
            color: #007bff;
            /* Highlight color on hover */
            transform: scale(1.2);
            /* Slight zoom effect */
        }

        .social-link i {
            vertical-align: middle;
            /* Đảm bảo icon căn chỉnh đẹp */
        }
    </style>
    <div style="min-height: 510px;" class="container mt-5">
        <h1 class="text-center animate__animated animate__fadeInDown">User Profile</h1>
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="favourite-tab" data-bs-toggle="tab" data-bs-target="#favourite" type="button" role="tab">Favourite</button>
            </li>
        </ul>
        <div class="tab-content mt-4">
            <!-- Info Tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="d-flex-column align-items-center">
                    <?php $imageAvatar = '../../Admin/phpUser/imgUser/' . $profile['avatar']; ?>
                    <img style="height: 160px; width: 160px; object-fit: cover; margin-left: 50px;"
                        src="<?php echo $profile['avatar'] ? $imageAvatar : 'https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg'; ?>"
                        alt="Circle Image"
                        class="avatar me-5">
                    <div>
                        <h4 class="text-center" id="username"><?php echo $profile['username']; ?></h4>
                        <p><strong>Email:</strong> <span id="email"><?php echo $profile['email']; ?></span></p>
                        <p><strong>Phone:</strong> <span id="phone"><?php echo $profile['phone']; ?></span></p>
                        <p>
                            <strong>Address:</strong>
                            <?php
                            echo ($address['address_line'] ?? 'No address') . ', ' .
                                ($address['city'] ?? 'No city') . ', ' .
                                ($address['country'] ?? 'No country');
                            ?>
                        </p>
                    </div>
                </div>
                <div class="text-center pb-3">
                    <button class="btn btn-primary mt-3" id="editBtn">Edit</button>
                    <button class="btn btn-secondary mt-3" id="changePassBtn">Change Password</button>
                    <button class="btn btn-danger mt-3" id="logout">Logout</button>
                    <script>
                        document.getElementById("logout").addEventListener("click", function() {
                            window.location.href = '../../Login/login.php';
                            // Clear existing cookies
                            setcookie("idUser", "", time() - 3600, "/");
                            setcookie("username", "", time() - 3600, "/");
                            setcookie("idCart", "", time() - 3600, "/");
                            setcookie("role", "", time() - 3600, "/");


                        })
                    </script>
                </div>
            </div>
            <!-- Orders Tab -->
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <h3>Order History</h3>
                <p>Display user's orders here...</p>
            </div>
            <!-- Favourite Tab -->
            <div class="tab-pane fade" id="favourite" role="tabpanel">
                <h3>Favourite Items</h3>
                <p>Display user's favourites here...</p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="mt-3 bg-dark text-white text-center py-4">
        <div class="container3">
            <!-- Social Media Icons -->
            <div class="mb-3">
                <a href="https://facebook.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://youtube.com" target="_blank" class="social-link">
                    <i class="bi bi-youtube"></i>
                </a>
            </div>
            <!-- About Us and Contact With Us Buttons -->
            <div class="mb-3">
                <a href="../../aboutus/about_us.php" class="btn btn-outline-light me-3">About Us</a>
                <a href="../../contact/contact.php" class="btn btn-outline-light">Contact With Us</a>
            </div>
            <!-- Copyright -->
            <p>&copy; 2024 ShoeStore. All rights reserved. Design by TPN Team VKU 23JIT</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('editBtn').addEventListener('click', () => {
            Swal.fire({
                title: 'Edit Profile',
                html: `
                    <input type="text" id="swal-username" class="swal2-input" placeholder="Username" value="${document.getElementById('username').textContent}">
                    <input type="email" id="swal-email" class="swal2-input" placeholder="Email" value="${document.getElementById('email').textContent}">
                    <input type="text" id="swal-phone" class="swal2-input" placeholder="Phone" value="${document.getElementById('phone').textContent}">
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => {
                    const username = document.getElementById('swal-username').value;
                    const email = document.getElementById('swal-email').value;
                    const phone = document.getElementById('swal-phone').value;
                    // TODO: Update to database using AJAX or form submission
                    return {
                        username,
                        email,
                        phone
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        username,
                        email,
                        phone
                    } = result.value;
                    document.getElementById('username').textContent = username;
                    document.getElementById('email').textContent = email;
                    document.getElementById('phone').textContent = phone;
                    Swal.fire('Saved!', 'Your profile has been updated.', 'success');
                }
            });
        });

        document.getElementById('changePassBtn').addEventListener('click', () => {
            window.location.href = 'forgotpassword.php';
        });
    </script>
</body>

</html>