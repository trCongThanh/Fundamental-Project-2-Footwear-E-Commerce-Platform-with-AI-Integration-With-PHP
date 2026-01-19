<?php
include '../database.php';

// Nhận dữ liệu JSON từ fetch
$inputData = file_get_contents("php://input");
$data = json_decode($inputData, true);

// Kiểm tra dữ liệu đầu vào
if (!$data || !isset($data['userId'], $data['chatbox'], $data['role'])) {
    echo "Invalid input data!";
    exit;
}

// Gán giá trị từ dữ liệu JSON
$userId = isset($data['userId']) ? (int)$data['userId'] : 0;
$senderId = isset($data['senderId']) ? (int)$data['senderId'] : 0;
$messageText = isset($data['chatbox']) ? trim($conn->real_escape_string($data['chatbox'])) : '';
$role = isset($data['role']) ? $conn->real_escape_string($data['role']) : '';

// Kiểm tra dữ liệu cần thiết
if (empty($userId) || empty($messageText) || empty($role)) {
    echo "Missing required input data!";
    exit;
}

// Lấy `username` từ bảng `user`
$sqlUser = "SELECT username FROM user WHERE id = $senderId";
$result = $conn->query($sqlUser);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
} else {
    echo "User not found!";
    exit;
}

// Lấy nội dung chat hiện tại từ `user`
$sqlChatbox = "SELECT chatbox FROM user WHERE id = $userId";
$resultChatbox = $conn->query($sqlChatbox);

$currentChatbox = '';
if ($resultChatbox && $resultChatbox->num_rows > 0) {
    $rowChatbox = $resultChatbox->fetch_assoc();
    $currentChatbox = $rowChatbox['chatbox'];
}

// Tạo định dạng tin nhắn mới
if ($role === "admin") {
    $formattedMessage = "🛠️".$username."🛠️".$messageText;
} else { // Mặc định là customer
    $formattedMessage = "👤".$username."👤".$messageText;
}

// Nối tin nhắn mới vào nội dung hiện tại
$updatedChatbox = $currentChatbox . " " . $formattedMessage;

// Cập nhật lại `chatbox` trong bảng `user`
$sqlUpdate = "UPDATE user SET chatbox = '$updatedChatbox' WHERE id = $userId";

if ($conn->query($sqlUpdate)) {
    echo "Chatbox updated successfully!";
} else {
    echo "Failed to update chatbox: " . $conn->error;
}

// Đóng kết nối
$conn->close();
?>
