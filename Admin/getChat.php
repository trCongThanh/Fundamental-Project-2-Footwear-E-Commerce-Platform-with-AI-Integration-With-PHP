<?php
include 'database.php';
// Lấy userId từ URL
if (isset($_GET['userId'])) {
    $userId = intval($_GET['userId']); // Ép kiểu an toàn

    // Lệnh SQL để lấy chatbox từ user
    $sql = "SELECT chatbox FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $chatbox = $row['chatbox'];

        // Xử lý nội dung chatbox
        $processedChatbox = processChatbox($chatbox);

        // Trả về nội dung chatbox sau xử lý
        echo json_encode([
            'status' => 'success',
            'chatbox' => $processedChatbox
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không tìm thấy chatbox'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không có userId'
    ]);
}

$conn->close();

// Hàm xử lý chatbox
function processChatbox($chatbox) {
    // Tìm tất cả các đoạn tin nhắn bằng regex
    preg_match_all('/(👤[^👤🛠️]+👤[^👤🛠️]+|🛠️[^👤🛠️]+🛠️[^👤🛠️]+)/u', $chatbox, $matches);

    $result = [];

    foreach ($matches[0] as $msg) {
        if (strpos($msg, '👤') === 0) { // Tin nhắn của user
            preg_match('/👤(.*?)👤(.*)/u', $msg, $parts);
            $username = trim($parts[1]);
            $text = trim($parts[2]);
            $result[] = "role: customer\n$username: $text";
        } elseif (strpos($msg, '🛠️') === 0) { // Tin nhắn của admin
            preg_match('/🛠️(.*?)🛠️(.*)/u', $msg, $parts);
            $username = trim($parts[1]);
            $text = trim($parts[2]);
            $result[] = "role: admin\n$username: $text";
        }
    }

    // Ghép lại với dấu ngắt dòng
    return implode("\n\n", $result);
}

?>
