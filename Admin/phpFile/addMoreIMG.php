<?php
header('Content-Type: application/json');

// Base directory (always starts with __DIR__)
$baseDir = __DIR__ . '/../img/';

// Get the dynamic path and custom image name from POST
$dynamicPath = isset($_POST['dynamicPath']) ? trim($_POST['dynamicPath'], '/\\') : '';
$imageName = isset($_POST['imageName']) ? basename($_POST['imageName']) : '';

// Validate dynamic path
$uploadDir = $baseDir . $dynamicPath . '/';
if (strpos(realpath($uploadDir), realpath($baseDir)) !== 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid upload directory.']);
    exit;
}

// Check if an image file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    // Ensure directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Use custom image name if provided, otherwise use the uploaded file's original name
    $uploadFile = $uploadDir . ($imageName ?: basename($_FILES['image']['name']));

    // Move the uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => true, 'message' => 'Image uploaded successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save the image.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error.']);
}
?>
