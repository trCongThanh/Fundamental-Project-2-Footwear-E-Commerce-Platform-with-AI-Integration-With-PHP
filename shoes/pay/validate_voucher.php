<?php
// Include database connection
include '../homepage/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $discountCode = $_POST['discountCode'];

    // Fetch discount value from the database
    $stmt = $conn->prepare("SELECT discount FROM voucher WHERE id = ?");
    $stmt->bind_param("s", $discountCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'discount' => $row['discount']]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
