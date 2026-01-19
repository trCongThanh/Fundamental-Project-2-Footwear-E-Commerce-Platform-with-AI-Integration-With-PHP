<?php
// Kết nối cơ sở dữ liệu
include '../homepage/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];
$new_comment = $data['new_comment'];

if (!$product_id || !$new_comment) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

try {
    // Lấy bình luận hiện tại
    $stmt = $conn->prepare("SELECT comment FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_comments = $row['comment'];

    // Thêm bình luận mới
    $updated_comments = $current_comments ? $current_comments . " || " . $new_comment : $new_comment;

    // Cập nhật trường comments
    $stmt = $conn->prepare("UPDATE products SET comment = ? WHERE id = ?");
    $stmt->bind_param("si", $updated_comments, $product_id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
