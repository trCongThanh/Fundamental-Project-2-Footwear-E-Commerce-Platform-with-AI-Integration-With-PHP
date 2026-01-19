<?php
include('../database.php');
?>

<?php
// Kiểm tra xem phương thức có phải là POST không và dữ liệu JSON có hợp lệ không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'getSalesfromId' && isset($data['id'])) {
        $saleId = $data['id'];

        // Thực hiện truy vấn để lấy thông tin người dùng từ database
        $sql = "SELECT * FROM sales WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $saleId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra kết quả và trả về JSON
        if ($user = $result->fetch_assoc()) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Invalid request']);
        echo json_encode($data);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
