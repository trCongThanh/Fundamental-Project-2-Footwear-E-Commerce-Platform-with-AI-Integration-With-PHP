<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "shopbangiay";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Failed to connect: " . $conn->connect_error]);
    exit;
}

// Retrieve idUser from GET request
if (!isset($_GET['idUser']) || !is_numeric($_GET['idUser'])) {
    echo json_encode(["status" => "error", "message" => "Invalid or missing idUser"]);
    exit;
}

$idUser = (int)$_GET['idUser'];

// Secure SQL query using prepared statements
$sql = "SELECT chatbox FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $idUser);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'chatbox' => $row['chatbox']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'SQL query preparation failed']);
}

// Close the connection
$conn->close();
?>
