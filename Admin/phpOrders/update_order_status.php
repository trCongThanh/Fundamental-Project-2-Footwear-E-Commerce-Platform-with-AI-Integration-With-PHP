<?php
include('../database.php');
// Example PHP to handle order status update
$data = json_decode(file_get_contents('php://input'), true);

$orderId = $data['orderId'];
$status = $data['status'];

// Update the order status in the database (adjust SQL as needed)
$query = "UPDATE orders SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $orderId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
