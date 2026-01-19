
<?php
include('../homepage/database.php');

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$cart_id = isset($_GET['cart_id']) ? intval($_GET['cart_id']) : 0;
$products = isset($_GET['products']) ? json_decode($_GET['products'], true) : null;
if ($order_id === 0) {
    echo "Thông tin không hợp lệ.";
    exit;
}

$sql_order = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows > 0) {
    $order = $order_result->fetch_assoc();

    $sql_items = "SELECT *
              FROM order_detail
              WHERE idOrders = ?";

    $stmt = $conn->prepare($sql_items);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $items_result = $stmt->get_result();
?>
    <a href="../cart/cart.php" class="back-button">Back to Cart</a>
    <h2 class="order-title">Order Confirmation</h2>
    <div class="order-summary">
        <p><strong>Invoice Number: </strong> <?php echo $order['id']; ?></p>
        <?php
        $total_payment = str_replace(',', '', $order['total_payment']); // Remove commas if they exist
        $total_payment = floatval($total_payment); // Convert to float
        
        ?>
        <p><strong>Total Payment: </strong> <?php echo number_format($order['total_payment'], 0); ?> VND</p>
        <p><strong>Order Date: </strong> <?php echo $order['order_date']; ?></p>
        <p><strong>Delivery Date: </strong> <?php echo $order['delivery_date']; ?></p>
    </div>

    <h3 class="order-items-title">Products in the Order:</h3>

    <div class="products-list">
        <?php
        if ($items_result->num_rows > 0) {
            while ($item = $items_result->fetch_assoc()) {
                $product_name = htmlspecialchars($item['name']);
                $product_img = htmlspecialchars($item['img']);

                $product_price = number_format($item['price'], 2);
                $quantity = $item['quantity'];
                $color = htmlspecialchars($item['color']);
                $size = htmlspecialchars($item['size']);
                $total_price = $quantity * $item['price'];
        ?>
                <div class="product-describe">
                    <div class="div-block-5">
                        <img src="<?php echo $product_img; ?>" loading="lazy" width="200" alt="Product Image" class="image-6" />
                        <div class="text-block-17"><?php echo $product_name; ?></div>
                    </div>
                    <div class="div-block-7" style="align-items :center;flex-direction: column; grid-row-gap: 1vw;">
                        <div class="text-block-17">Quantity</div>
                        <div class="text-block-17"><?php echo $quantity; ?></div>
                    </div>
                    <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                        <div class="text-block-17">Color</div>
                        <div class="color-options">
                            <a href="#" class="button-6 w-button"
                                style="background-color: <?php echo $color; ?>; box-shadow: 0 0 8px rgba(64, 64, 64, 0.6);">
                            </a>
                        </div>
                    </div>
                    <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                        <div class="text-block-17">Size</div>
                        <div class="text-block-17"><?php echo $size; ?></div>

                    </div>
                    <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                        <div class="text-block-17">Price</div>
                        <?php
                         $product_price = str_replace(',', '', $product_price); // Remove commas if they exist
                         $product_price = floatval($product_price); // Convert to float
                         
                         $product_price = number_format($product_price, 0, '.', ','); // Format the price without decimals
                         
                        ?>
                        <div class="text-block-17"><?php echo $product_price; ?> VND</div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>Không có sản phẩm trong đơn hàng.</p>";
        }
        ?>
    </div>
<?php
} else {
    echo "Không tìm thấy đơn hàng.";
}

$stmt->close();
$conn->close();
?>

<script>
    deletePay();

function deletePay() {
    // Get the cart ID dynamically
    let cart_id = "<?php echo $cart_id; ?>";
    console.log(cart_id); // Debug: ensure the correct cart_id is being sent

    fetch('delete_pay.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                idCart: cart_id // Ensure the key matches the backend expectation
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Items deleted successfully!');
            } else {
                console.error('Error:', data.error || 'Unknown error');
                alert('Error occurred while updating the cart: ' + (data.error || 'Unknown error.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error connecting to the server.');
        });
}

</script>
<!-- CSS -->
<style>
    .product-describe {
        width: 95%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    @media screen and (max-width: 991px) {
        .product-describe {
            grid-column-gap: 1vw;
            grid-row-gap: 1vw;
            margin-left: 1vw;
        }
    }

    .div-block-5 {
        display: flex;
        align-items: center;
    }

    .image-6 {
        margin-right: 15px;
        border-radius: 5px;
    }

    .text-block-7 {
        font-size: 16px;
        color: #2c3e50;
    }

    .button-6 {
        text-align: center;
        border-radius: 10px;
        padding: 0.5vw 1.5vw;
        font-family: Georgia, Times, Times New Roman, serif;
        font-size: 1vw;
        line-height: .5rem;
    }

    .div-block-7 {
        margin-left: 20px;
        display: flex;
        flex-direction: column !important;
        justify-content: center;
        align-items: center;
        grid-row-gap: 2vw !important;
    }

    .text-block-8,
    .text-block-10 {
        font-size: 14px;
        color: #7f8c8d;
    }

    .text-block-9 {
        font-size: 16px;
        color: #2c3e50;
        font-weight: bold;
    }

    .div-block-6 {
        margin-top: 5px;
    }

    .back-button {
        display: inline-block;
        background-color: white;
        color: black;
        padding: 12px 24px;
        font-size: 16px;
        text-align: center;
        border-radius: 5px;
        margin-top: 30px;
        text-decoration: none;
        transition: background-color 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }

    .back-button:hover {
        background-color: #3898ec;
    }

    .back-button:active {
        background-color: #3898ec;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .order-title {
        text-align: center;
        margin-top: 30px;
        font-size: 28px;
        color: #2c3e50;
        font-weight: bold;
    }

    .order-summary {
        background-color: #ffffff;
        padding: 30px;
        margin: 20px auto;
        width: 80%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        color: #2c3e50;
    }

    .order-summary p {
        font-size: 16px;
        line-height: 1.8;
    }
</style>