<?php
// Include database connection
include '../homepage/database.php';

header('Content-Type: application/json'); // Ensure JSON response

try {
    // Get the input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if idCart exists in the received data
    if (!isset($data['idCart']) || empty($data['idCart'])) {
        throw new Exception("Cart ID is missing.");
    }

    $idCart = $data['idCart'];

    // Prepare SQL query to delete items
    $stmt = $conn->prepare("DELETE FROM carthasproduct WHERE idCart = ? AND isPay = 'yes'");
    $stmt->bind_param('i', $idCart);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Failed to delete items from the database.");
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
