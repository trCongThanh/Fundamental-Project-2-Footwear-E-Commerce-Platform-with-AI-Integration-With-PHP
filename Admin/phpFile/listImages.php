<?php
// Check if the 'folder' parameter is provided
if (isset($_GET['folder'])) {
    // Path to the folder containing images (sanitize input)
    $folder = '../img/' . basename($_GET['folder']);

    // Ensure the folder exists
    if (!is_dir($folder)) {
        // Attempt to create the folder
        if (!mkdir($folder, 0777, true)) {
            // Return error if folder creation fails
            echo json_encode(['error' => 'Failed to create folder']);
            exit;
        }
    }

    // Get all files in the folder
    $files = array_diff(scandir($folder), array('.', '..'));

    // Filter only image files (optional, based on file extensions)
    $images = array_filter($files, function($file) use ($folder) {
        $filePath = $folder . '/' . $file;
        return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file) && is_file($filePath);
    });

    // Return images as JSON
    echo json_encode(array_values($images));
} else {
    // No folder parameter provided
    echo json_encode(['error' => 'Folder parameter is required']);
}
?>
