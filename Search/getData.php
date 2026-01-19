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
    echo json_encode(["error" => "Failed to connect: " . $conn->connect_error]);
    exit;
}

// Query for products
$productsQuery = "SELECT * FROM products";
$productsResult = $conn->query($productsQuery);

if (!$productsResult) {
    echo json_encode(["error" => "Failed to fetch products: " . $conn->error]);
    exit;
}

$products = [];
while ($row = $productsResult->fetch_assoc()) {
    $products[] = $row;
}

// Query for sales
$salesQuery = "SELECT * FROM sales";
$salesResult = $conn->query($salesQuery);

if (!$salesResult) {
    echo json_encode(["error" => "Failed to fetch sales: " . $conn->error]);
    exit;
}

$sales = [];
while ($row = $salesResult->fetch_assoc()) {
    $sales[] = $row;
}

// Close connection
$conn->close();

// Output JSON response
echo json_encode([
    "products" => $products,
    "sales" => $sales
]);
