<?php
// Include the database connection
include '../homepage/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Decode JSON input for POST, or parse parameters for GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
    } else {
        $input = [
            'userId' => $_GET['userId'] ?? null,
            'totalAmount' => $_GET['totalAmount'] ?? null,
            'products' => isset($_GET['products']) ? json_decode($_GET['products'], true) : null,
            'address' => $_GET['address'] ?? null,
            'cart_id' => $_GET['cart_id'] ?? null
        ];
    }

    // Validate input fields
    if (isset($input['userId'], $input['totalAmount'], $input['products'], $input['address'], $input['cart_id'])) {
        $userId = $input['userId'];
        $totalAmount = $input['totalAmount'];
        $products = $input['products'];
        $address = $input['address'];
        $cart_id = $input['cart_id'];
        $status = 'On Delivery';
        // Insert the order into the 'orders' table
        $query = "INSERT INTO orders (status, idUser, total_payment, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sids", $status, $userId, $totalAmount, $address);

        if ($stmt->execute()) {
            $orderId = $conn->insert_id; // Get the newly created order ID

            // Loop through the products and insert each into the 'order_details' table
            foreach ($products as $product) {
                $quantity = $product['quantity'];
                $price = $product['price'];
                $img = $product['img'];
                $name = $product['name'];
                $size = $product['size'];
                $color = $product['color'];
                $query = "INSERT INTO order_detail (idOrders, quantity, price, img, name, size, color) 
                          VALUES (?, ?, ?, ? , ?, ?, ?)";
                $detailStmt = $conn->prepare($query);
                $detailStmt->bind_param("iidssss", $orderId, $quantity, $price, $img, $name, $size, $color);

                if (!$detailStmt->execute()) {
                    // If any detail fails, return an error and stop execution
                    echo json_encode(['success' => false, 'message' => 'Failed to insert order details.']);
                    $detailStmt->close();
                    $stmt->close();
                    $conn->close();
                    exit;
                }
                $detailStmt->close();
            }

            // If everything is successful, return a success response
            echo json_encode(['success' => true, 'order_id' => $orderId]);
            header("Location: confirmation.php?order_id=$orderId&cart_id=$cart_id");
        } else {
            // Handle errors during the order insertion
            echo json_encode(['success' => false, 'message' => 'Order submission failed.']);
        }

        $stmt->close();
        $conn->close();
    } else {
        // Handle missing required fields
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
} else {
    // Handle invalid request methods
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
