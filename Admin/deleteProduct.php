<?php
include('database.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

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
