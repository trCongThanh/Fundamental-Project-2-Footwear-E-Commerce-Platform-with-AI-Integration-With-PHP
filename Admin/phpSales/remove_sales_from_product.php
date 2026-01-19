<?php
include('../database.php');

if (isset($_POST['product_id'])) {
    // Nhận ID sản phẩm từ yêu cầu POST
    $product_id = intval($_POST['product_id']);

    // Thực hiện truy vấn cập nhật
    $sql = "UPDATE products SET idSale = 0 WHERE id = $product_id";
    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
