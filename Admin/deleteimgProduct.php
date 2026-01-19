<?php
function deleteImage($imagePath) {
    if (file_exists($imagePath)) {
        if (unlink($imagePath)) {
            return "Hình ảnh đã được xóa thành công.";
        } else {
            return "Không thể xóa hình ảnh.";
        }
    } else {
        return "Hình ảnh không tồn tại.";
    }
}

// Lấy đường dẫn từ yêu cầu và trả về kết quả
if (isset($_POST['imagePath'])) {
    $imagePath = $_POST['imagePath'];
    $result = deleteImage($imagePath);
    echo json_encode(["message" => $result]);
} else {
    echo json_encode(["message" => "Thiếu đường dẫn hình ảnh."]);
}
