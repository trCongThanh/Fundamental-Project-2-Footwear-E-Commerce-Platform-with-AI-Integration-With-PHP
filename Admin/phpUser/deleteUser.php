<?php
include('../database.php'); // Include database connection

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['id'])) {
    $userId = $input['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

?>
