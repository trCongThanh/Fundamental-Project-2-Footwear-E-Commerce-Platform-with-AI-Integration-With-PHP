<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopbangiay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Lấy dữ liệu JSON từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['idproduct'], $data['idcart'], $data['quantity'], $data['color'])) {
    $idproduct = $conn->real_escape_string($data['idproduct']);
    $idcart = $conn->real_escape_string($data['idcart']);
    $quantity = $conn->real_escape_string($data['quantity']);
    $color = $conn->real_escape_string($data['color']);
    $size= $conn->real_escape_string($data['size']);
    $isPay = $conn->real_escape_string($data['isPay']);
    // Thêm dữ liệu vào bảng cart_has_product
    $sql = "INSERT INTO carthasproduct (idCart, idProduct, quantity, color, size, isPay) 
            VALUES ('$idcart', '$idproduct', '$quantity', '$color', '$size', '$isPay')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Product added to cart successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input data"]);
}

// Đóng kết nối
$conn->close();
?>
