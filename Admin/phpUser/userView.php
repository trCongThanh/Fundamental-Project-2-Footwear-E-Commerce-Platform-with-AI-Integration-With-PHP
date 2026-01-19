<?php
$idUser = "";
$username = "";
$idCart = "";
if (isset($_COOKIE["idUser"]) && isset($_COOKIE["username"]) && isset($_COOKIE["idCart"])) {
    $idUser = $_COOKIE["idUser"];
    $username = $_COOKIE["username"];
    $idCart = $_COOKIE["idCart"];
} else {
    // Nếu cookie không tồn tại, chuyển hướng người dùng đến trang php.login
    header("Location: login.php"); // Đảm bảo rằng đường dẫn đúng với tên tệp của bạn
    exit(); // Kết thúc script sau khi chuyển hướng
}
?>
<?php
include('../database.php');

// Lấy dữ liệu từ bảng user
$sql = "SELECT * FROM user where id <> $idUser";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
?>
<?php
include('../database.php');
// Get the raw POST data
$json = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json, true);

// Check if required fields are present
if (isset($data['action']) && $data['action'] === 'editUser') {

    // Assign variables for easier reference
    $userId = $data['id'];
    $username = $data['username'];
    $phone = $data['phone'];
    $email = $data['email'];
    $role = $data['role'];
    $avatar = $data['avatar'];
    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE user SET username=?, phone=?, email=?, role=?, avatar=? WHERE id=?");

    // Bind parameters
    $stmt->bind_param("sssssi", $username, $phone, $email, $role, $avatar, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were affected (i.e., user updated)
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes were made.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .table thead {
            background-color: #007bff;
            color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            max-height: 100px;
            border-radius: 50%;
        }

        .avatar-container img {
            max-height: 100px;
            border-radius: 50%;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 24px;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 50%;
            cursor: pointer;
        }

        .avatar-container:hover .avatar-overlay {
            opacity: 1;
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
        <div class="container my-5">
            <h2 class="text-start mb-4">User Management</h2>
            <!-- Search Bar -->
            <div class="d-flex justify-content-end mb-3">
                <form class="d-flex" role="search">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>

            <!-- User Table with responsive wrapper -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Avatar</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($user['avatar'] ? 'imgUser/' . $user['avatar'] : 'https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg'); ?>"
                                            style="height: 50px; width: 50px; border-radius: 50%;" alt="User Avatar">
                                    </td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo $user['avatar']; ?>')">Delete</button>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="openVoucherModal('<?php echo $user['username']; ?>', '<?php echo $user['email']; ?>')">
                                            Send Voucher
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="voucherModalLabel">Send Voucher</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="voucherForm">
                            <div class="mb-3">
                                <label for="voucherCode" class="form-label">Voucher Code</label>
                                <input type="text" class="form-control" id="voucherCode" placeholder="Enter voucher code" required>
                            </div>
                            <div class="mb-3">
                                <label for="voucherValue" class="form-label">Voucher Value</label>
                                <input type="text" class="form-control" id="voucherValue" placeholder="Enter voucher value" required>
                            </div>
                            <input type="hidden" id="voucherUsername">
                            <input type="hidden" id="voucherEmail">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="sendVoucher()">Send Voucher</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openVoucherModal(username, email) {
                document.getElementById('voucherUsername').value = username;
                document.getElementById('voucherEmail').value = email;
                const voucherModal = new bootstrap.Modal(document.getElementById('voucherModal'));
                voucherModal.show();
            }

            function sendVoucher() {
                const username = document.getElementById('voucherUsername').value;
                const email = document.getElementById('voucherEmail').value;
                const voucherCode = document.getElementById('voucherCode').value;
                const voucherValue = document.getElementById('voucherValue').value;

                const sendmailData = {
                    action: 'voucher',
                    username: username,
                    email: email,
                    voucherCode: voucherCode,
                    voucherValue: voucherValue
                };

                fetch('../../Login/sendVoucher.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(sendmailData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const voucherData = {
                            action: 'insert_voucher',
                            id: voucherCode, // ID của voucher mới
                            discount: voucherValue // Giá trị giảm giá của voucher
                        };

                        fetch('updateVoucher.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(voucherData)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Voucher added successfully!');
                                } else {
                                    alert('Error adding voucher: ' + data.error);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        try {
                            const jsonData = JSON.parse(data);
                            alert(jsonData.message);
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                            alert('Voucher sent successfully!');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);

                    });

            }
        </script>
        <script>
            function deleteUser(userId, avatar) {
                if (confirm("Are you sure you want to delete this user?")) {
                    fetch('deleteUser.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: userId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert("User deleted successfully!");
                                document.querySelector(`tr[data-id="${userId}"]`).remove();
                                if (avatar != "" && avatar != null && avatar != undefined) deleteImage("imgUser/" + avatar);
                                window.location.reload();
                            } else {
                                alert(`Error: ${data.message}`);
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            // alert("An error occurred while deleting the user.");
                        });
                }
            }

            function deleteImage(imgtoDelete) {
                console.log(imgtoDelete);
                if (imgtoDelete != "") {
                    fetch('deleteimgProduct.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                imagePath: imgtoDelete
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.message); // In thông báo từ PHP
                            alert(data.message); // Hiển thị thông báo cho người dùng
                        })
                        .catch(error => console.error('Lỗi:', error));
                    imgtoDelete = "";
                }
            }
        </script>
        <script>
            document.getElementById('searchInput').addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    // Lấy dữ liệu từ các cột trong hàng
                    const name = row.children[2].textContent.toLowerCase();
                    const email = row.children[3].textContent.toLowerCase();
                    const phone = row.children[4].textContent.toLowerCase();
                    const role = row.children[5].textContent.toLowerCase();

                    // Hiển thị hoặc ẩn hàng nếu khớp với từ khóa
                    if (name.includes(query) || email.includes(query) || phone.includes(query) || role.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>

        <!-- Edit User Modal (similar to Add User Modal) -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-2 text-center">
                                <div class="avatar-container">
                                    <img width="100px" height="100px" id="editUserAvatar" src="https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg" alt="User Avatar">
                                    <div class="avatar-overlay">
                                        <i class="fas fa-picture"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="editUserName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editUserName" required>
                            </div>
                            <div class="mb-2">
                                <label for="editUserPhone" class="form-label">Phone</label>
                                <input type="phone" class="form-control" id="editUserPhone" required>
                            </div>
                            <div class="mb-2">
                                <label for="editUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editUserEmail" required>
                            </div>
                            <div class="mb-2">
                                <label for="userRole" class="form-label">Role</label>
                                <select class="form-select" id="userRole" required>
                                    <option selected disabled>Choose...</option>
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button onclick="saveEditUser()" type="submit" class="btn btn-primary w-100 mt-3">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.querySelector('.avatar-overlay').addEventListener('click', function() {
                // Tạo một input file ẩn
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';

                // Khi người dùng chọn ảnh, cập nhật src của ảnh avatar
                fileInput.addEventListener('change', function() {
                    const file = fileInput.files[0];
                    if (file) {
                        currentFileimg = file;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('editUserAvatar').src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Kích hoạt input file để người dùng chọn ảnh
                fileInput.click();
            });
            let currentFileimg = null;
            let currentUserID = 0;
            let currentImgName = '';

            function editUser(userId) {
                currentUserID = userId;
                const getUserFromIdData = {
                    action: 'getIdFromUser',
                    id: userId
                };
                console.log(getUserFromIdData);
                fetch('getUser.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(getUserFromIdData)
                    })
                    .then(response => response.text()) // Log as text first
                    .then(text => {
                        console.log(text); // Check the exact content returned by userView.php
                        return JSON.parse(text);
                    })
                    .then(data => {
                        // Kiểm tra và gán giá trị cho các trường trong form
                        if (data.avatar && data.avatar.trim() !== '') {
                            document.getElementById('editUserAvatar').src = "imgUser/" + data.avatar;
                            currentImgName = data.avatar;
                        } else {
                            document.getElementById('editUserAvatar').src = 'https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg';
                        }
                        document.getElementById('editUserName').value = data.username || '';
                        document.getElementById('editUserPhone').value = data.phone || '';
                        document.getElementById('editUserEmail').value = data.email || '';
                        document.getElementById('userRole').value = data.role || '';
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            }

            function saveEditUser() {
                // Generate a new filename
                var newFileName;
                newFileName = currentImgName; // cho current newFileName = currentImgName; để đảm bảo nếu đã có ảnh trước đó thì sẽ không đưa về '' khi update
                if (currentFileimg != null) {
                    newFileName = generateFileName(currentFileimg.name);
                }
                console.log(newFileName);
                const editUserData = {
                    action: 'editUser',
                    id: currentUserID,
                    avatar: newFileName,
                    username: document.getElementById('editUserName').value,
                    phone: document.getElementById('editUserPhone').value,
                    email: document.getElementById('editUserEmail').value,
                    role: document.getElementById('userRole').value
                };
                fetch('userView.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(editUserData)
                    })
                    .then(response => response.text()) // Log as text first
                    .then(text => {
                        console.log(text); // Check the exact content returned by userView.php
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
                if (currentFileimg != null) {
                    const formData = new FormData();


                    // Append the file with the new name
                    formData.append('image', new File([currentFileimg], newFileName, {
                        type: currentFileimg.type
                    }));

                    // Send the image to the PHP script for upload
                    fetch('uploadImage.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); // Handle success or error messages
                        })
                        .catch(error => {
                            console.error('Error uploading image:', error);
                        });
                }

                currentImgName = newFileName; // đảm bỏa không spam thêm ảnh
                currentFileimg = null;
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
        <script src="../script.js"></script>
</body>

</html>