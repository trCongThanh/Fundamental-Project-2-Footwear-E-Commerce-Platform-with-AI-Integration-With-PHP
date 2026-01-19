<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php'; // Đảm bảo tệp này chứa mã kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy giá trị sale_id và danh sách sản phẩm từ form
    $sale_id = $_POST['sale_id'] ?? null; // Lấy sale_id (nếu có)
    $products = $_POST['products'] ?? []; // Danh sách id sản phẩm

    // Kiểm tra dữ liệu đầu vào
    if (!$sale_id) {
        echo "Sale ID is missing.";
        exit;
    }

    if (empty($products)) {
        echo "No products selected.";
        exit;
    }

    // Chuẩn bị truy vấn SQL
    try {
        $conn->beginTransaction(); // Bắt đầu transaction

        $stmt = $conn->prepare("UPDATE products SET idSale = :sale_id WHERE id = :product_id");

        foreach ($products as $product_id) {
            $stmt->execute([
                ':sale_id' => $sale_id,
                ':product_id' => $product_id,
            ]);
        }

        $conn->commit(); // Xác nhận các thay đổi
        echo "Sale applied successfully!";
    } catch (PDOException $e) {
        $conn->rollBack(); // Hoàn tác các thay đổi nếu xảy ra lỗi
        echo "Error applying sale: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
