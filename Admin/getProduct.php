<?php
include('database.php');

// Set content type to JSON
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    if ($product) {
        echo json_encode(['success' => true, 'id' => $product['id'], 'price' => $product['price'], 'brands' => $product['brands'], 'name' => $product['name'], 'desc' => $product['desc'], 'gender' => $product['gender'], 'img' => $product['img']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No product ID provided']);
}
?>
