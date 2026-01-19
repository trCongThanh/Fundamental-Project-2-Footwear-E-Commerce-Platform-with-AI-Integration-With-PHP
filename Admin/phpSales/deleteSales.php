<?php
include('../database.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['action']) && $data['action'] === 'deleteSales' && isset($data['id'])) {
    $productId = $data['id'];

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM sales WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} 
?>