<?php
// Include database connection
include '../homepage/database.php';

try {
    // Get the input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['items']) || !is_array($data['items'])) {
        throw new Exception("Invalid input.");
    }

    // Prepare SQL query to update items
    $stmt = $conn->prepare("UPDATE carthasproduct SET quantity = ?, isPay = 'yes' WHERE id = ?");

    foreach ($data['items'] as $item) {
        $stmt->bind_param('ii', $item['quantity'], $item['id']);
        $stmt->execute();
    }

    $stmt->close();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
