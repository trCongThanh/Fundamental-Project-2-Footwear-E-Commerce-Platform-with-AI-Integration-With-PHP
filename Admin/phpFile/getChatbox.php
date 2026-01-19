<?php
include('../database.php');

// Lấy chatbox từ user với id = 7 (hoặc ID bất kỳ)
$sql = "SELECT chatbox FROM user WHERE id = ?";
$result = mysqli_query($conn, $sql);

if ($result) {
    $chatbox = mysqli_fetch_assoc($result)['chatbox'];
    echo json_encode(['status' => 'success', 'chatbox' => $chatbox]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy dữ liệu']);
}

mysqli_free_result($result);
mysqli_close($conn);
?>
