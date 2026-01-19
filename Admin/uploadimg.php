<?php
header('Content-Type: application/json');
// Check if an image file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/img/';
    $uploadFile = $uploadDir . basename($_FILES['image']['name']);

    // Ensure the img directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    //thêm việc resize hình ảnh về 1506x776 trước khi Move the uploaded file to the img folder
    // Move the uploaded file to the img folder
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => true, 'message' => 'Image uploaded successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save the image.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error.']);
}
?>