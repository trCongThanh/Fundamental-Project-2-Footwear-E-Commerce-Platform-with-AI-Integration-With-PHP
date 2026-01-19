<?php
include('../database.php');

// Ensure product ID is safe to use in SQL
$product = isset($_GET['product']) ? trim(mysqli_real_escape_string($conn, $_GET['product'])) : '';

// Fetch bigsale information based on product name
$sql = "SELECT bigsales FROM sales WHERE id = '$product'";
$result = mysqli_query($conn, $sql);
$sales = mysqli_fetch_assoc($result);
mysqli_free_result($result);
mysqli_close($conn);

// Split `bigsales` content into image and text
if (isset($sales['bigsales'])) {
    list($bannerImage, $saleText) = explode("<>", $sales['bigsales']);
} else {
    $bannerImage = '';
    $saleText = 'No sale information available';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Khuyến Mãi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Add your CSS here */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }
    .background-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        object-fit: cover;
        z-index: -1;
    }
    .sale-banner, .sale-info {
        max-width: 800px;
        width: 90%;
        border: 4px solid white;
        margin-top: 20px;
        text-align: center;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
    .sale-banner img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    .sale-info p {
        padding: 20px;
    }
</style>
<body>
    
    <!-- Background Video -->
    <video autoplay loop muted playsinline class="background-video">
        <source src="imgSales/sale.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>

    <!-- Sale Banner -->
    <div class="sale-banner">
        <img src="imgSales/<?php echo $bannerImage ?>" alt="Sale Banner">
    </div>

    <!-- Sale Info -->
    <section class="sale-info">
        <p><?php echo $saleText ?></p>
    </section>
</body>
</html>
