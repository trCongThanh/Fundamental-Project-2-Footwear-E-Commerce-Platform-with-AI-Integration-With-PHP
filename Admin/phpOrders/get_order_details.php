<?php
include('../database.php');
$orderId = $_GET['id'];

// Fetch order details from the database (adjust SQL as needed)
$query = "SELECT od.img, od.name, od.color, od.size, od.quantity, od.price, o.total_payment, u.username AS customerName, u.email AS customerEmail, u.phone AS customerPhone
          FROM order_detail od
          JOIN orders o ON o.id = od.idOrders
          JOIN user u ON u.id = o.idUser
          WHERE o.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'items' => $items,
]);

?>
