<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopbangiay";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
$isPay = isset($_POST['isPay']) ? $_POST['isPay'] : '';

if (empty($cart_id) || empty($isPay)) {
    echo "Invalid data!";
    exit;
}

$stmt = $conn->prepare("UPDATE carthasproduct SET isPay = ? WHERE idCart = ?");
$stmt->bind_param('si', $isPay, $cart_id);

if ($stmt->execute()) {
    echo "isPay status updated successfully!";
} else {
    echo "Failed to update isPay status!";
}

$stmt->close();
$conn->close();
?>
