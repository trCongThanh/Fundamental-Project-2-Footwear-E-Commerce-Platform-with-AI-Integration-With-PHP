<?php
// Thông tin cấu hình cơ sở dữ liệu
$host = "localhost";       // Tên máy chủ
$username = "root";        // Tên đăng nhập MySQL
$password = "";            // Mật khẩu MySQL
$database = "shopbangiay"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("fail connect: " . $conn->connect_error);
}

?>
