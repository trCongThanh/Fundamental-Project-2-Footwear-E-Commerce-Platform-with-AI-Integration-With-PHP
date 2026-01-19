<?php
include('../database.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['action']) && $data['action'] === 'addUser') {
    $username = $data['username'];
    $phone = $data['phone'];
    $email = $data['email'];
    $role = $data['role'];
    $avatar = $data['avatar'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (username, phone, email, password, role, avatar) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit; // Stop further execution
    }
    
    $stmt->bind_param("ssssss", $username, $phone, $email, $password, $role, $avatar);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding user: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .avatar-preview-container {
            position: relative;
            margin-bottom: 20px;
            text-align: center;
        }

        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .avatar-preview:hover {
            opacity: 0.8;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .avatar-preview-container:hover .avatar-overlay {
            opacity: 1;
        }

        .form-control,
        .form-select {
            height: calc(2.75rem + 2px);
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .btn-primary {
            border-radius: 5px;
            font-size: 1.1rem;
            padding: 12px;
        }
    </style>
</head>

<body>
     <!-- SIDEBAR -->
     <section id="sidebar">
        <a href="../index.php" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="../index.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="main">Main</li>
            <li>
                <a href="#"><i class='bx bxs-package icon'></i> Product <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="../productView.php">View Product</a></li>
                    <li><a href="../addnewProduct.php">Add Product</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class='bx bxs-user-circle icon'></i> User <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="userView.php">View User</a></li>
                    <li><a href="addnewUser.php">Add User</a></li>
                </ul>
            </li>
            <li class="divider" data-text="Commerce">Commerce</li>
            <li><a href="../phpOrders/ordersView.php"><i class='bx bx-cart-alt icon'></i> Orders</a></li>
            <li>
                <a href="#"><i class='bx bxs-purchase-tag-alt icon'></i> Sales <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="../phpSales/salesView.php">View Sales</a></li>
                    <li><a href="../phpSales/addSalesToProduct.php">Add sales to product</a></li>
                   
                </ul>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search... ">
                    <i class='bx bx-search icon'></i>
                </div>
            </form>
            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
                <span class="badge">8</span>
            </a>
            <span class="divider"></span>
            <div class="profile">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                <ul class="profile-link">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New User</h2>
        <div class="card">
            <div class="avatar-preview-container">
                <img id="userAvatarPreview" src="https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg"
                    alt="User Avatar" class="avatar-preview">
                <div class="avatar-overlay" onclick="triggerFileInput()">📷</div>
            </div>

            <form id="addUserForm">
                <div class="mb-3">
                    <label for="userName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="userName" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="userEmail" placeholder="Enter email address" required>
                </div>

                <div class="mb-3">
                    <label for="userPhone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="userPhone" placeholder="Enter phone number" required>
                </div>

                <div class="mb-3">
                    <label for="userPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Enter your password" required>
                </div>

                <div class="mb-4">
                    <label for="userRole" class="form-label">Role</label>
                    <select class="form-select" id="userRole" required>
                        <option selected disabled>Choose role...</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="button" onclick="addUser()" class="btn btn-primary w-100">Add User</button>
            </form>
        </div>
    </div>

    <input type="file" id="avatarFileInput" style="display: none;" accept="image/*" onchange="previewAvatar(event)">

    <script>
        let currentFileimg = null;
        let currentUserID = 0;
        let currentImgName = '';

        function triggerFileInput() {
            document.getElementById('avatarFileInput').click();
        }

        function previewAvatar(event) {
            currentFileimg = event.target.files[0];
            if (currentFileimg) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('userAvatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(currentFileimg);
            }
        }

        function addUser() {
            // Generate a new filename if a file is uploaded
            let newFileName = currentImgName;
            if (currentFileimg) {
                newFileName = generateFileName(currentFileimg.name);
            }

            const addUserData = {
                action: 'addUser',
                avatar: newFileName,
                username: document.getElementById('userName').value,
                phone: document.getElementById('userPhone').value,
                email: document.getElementById('userEmail').value,
                password: document.getElementById('userPassword').value,
                role: document.getElementById('userRole').value
            };

            // Send user data to addnewUser.php
            fetch('addnewUser.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(addUserData)
                })
                .then(response => response.text()) // Log as text first
                .then(text => {
                    console.log(text); // Check the exact content returned by userView.php
                    return JSON.parse(text);
                })
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        // Reset the form and avatar preview on success
                        document.getElementById('addUserForm').reset();
                        document.getElementById('userAvatarPreview').src = 'https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg';
                        currentFileimg = null;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            // Upload image if a new file is selected
            if (currentFileimg) {
                const formData = new FormData();
                formData.append('image', new File([currentFileimg], newFileName, {
                    type: currentFileimg.type
                }));

                fetch('uploadImage.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Image uploaded successfully');
                            currentImgName = newFileName; // Update only on successful upload
                            window.location.href = 'success.php'; // Redirect to success page
                        } else {
                            console.error('Image upload failed:', data.message);
                        }
                    })
                    .catch(error => console.error('Error uploading image:', error));
            }
            else  window.location.href = 'success.php'; // Redirect to success page
        }


        function generateFileName(originalFileName) {
            // Extract the file extension
            const extension = originalFileName.split('.').pop();
            // Generate a random ID (e.g., a number between 1 and 100000)
            const randomId = Math.floor(Math.random() * 100000);
            // Construct the new filename
            return `user${randomId}.${extension}`;
        }
    </script>
    <script src='../script.js'></script>
</body>

</html>