<?php
include('../database.php');
if (isset($_GET['sale_id'])) {
    $sale_id = intval($_GET['sale_id']);
    $sql = "SELECT id, name, img, gender, price FROM products WHERE idSale = $sale_id";
    $result = mysqli_query($conn, $sql);
    $sale_products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);

    foreach ($sale_products as $product) {
        // Tách màu sắc và ảnh từ chuỗi
        $imgGroups = explode(",", $product['img']);
        list($color, $imgFile) = explode("<>", $imgGroups[0]);

        echo "
        <div class='col-6 mb-3 product-item' data-id='" . htmlspecialchars($product['id']) . "'>
            <div class='position-relative border rounded p-2 shadow-sm'>
                <div class='product-box position-relative'>
                    <!-- Vòng tròn màu sắc -->
                    <div class='circle' style='background-color: " . htmlspecialchars($color) . ";'></div>
                    <!-- Ảnh sản phẩm -->
                    <img src='../img/" . htmlspecialchars($imgFile) . "' alt='Product Image' class='product-img'>
                    <!-- Tên sản phẩm -->
                    <label class='form-check-label mt-2 text-start w-100'>
                        " . htmlspecialchars($product['name']) . "
                    </label>
                    <!-- Giá sản phẩm -->
                    <label class='form-check-label mt-2 text-end w-100'>
                        " . htmlspecialchars($product['price']) . " VND
                    </label>
                    <!-- Nút xóa -->
                    <button type='button' class='btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-btn' data-id='" . $product['id'] . "'>
                        <i class='bx bxs-comment-x'></i>
                    </button>
                </div>
            </div>
        </div>";
    }
}
?>
