<?php
$idUser = "";
$username = "";
$idCart = "";
if (isset($_COOKIE["idUser"]) && isset($_COOKIE["username"]) && isset($_COOKIE["idCart"])) {
    $idUser = $_COOKIE["idUser"];
    $username = $_COOKIE["username"];
    $idCart = $_COOKIE["idCart"];
} else {
    // Nếu cookie không tồn tại, chuyển hướng người dùng đến trang php.login
    header("Location: login.php"); // Đảm bảo rằng đường dẫn đúng với tên tệp của bạn
    exit(); // Kết thúc script sau khi chuyển hướng
}
?>
<?php
include('../homepage/database.php');

$cart_id = $_GET['cart_id'] ?? '';
$total = $_GET['total'] ?? '';

if (empty($cart_id)) {
    die("Giỏ hàng không hợp lệ!");
}

$sql = "SELECT * FROM address WHERE user_id = ? AND is_default =1 LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();

$address = null;

if ($result->num_rows > 0) {
    $address = $result->fetch_assoc();
}

$stmt->close();
$conn->close();
?>



<div class="address-container">
    <button type="button" class="show-address" id="show-address" value="">Add address</button>

    <div class="address-form" id="address-form" style="display: none;">
        <div class="contact-info">
            <h3>Contact Information</h3>
            <div class="contact-input">
                <label for="name">Full name:</label>
                <input type="text" id="name" name="name" placeholder="Input your name" required />
            </div>
            <div class="contact-input">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" placeholder="Input your phone number" required />
            </div>
            <div class="contact-input">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Input your email" required />
            </div>
        </div>

        <div class="address-info">
            <h3>Address Information</h3>
            <div class="address-input">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" placeholder="Input your country" required />
            </div>
            <div class="address-input">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" placeholder="Input your city" />
            </div>
            <div class="address-input">
                <label for="address">Address:</label>
                <input type="text" id="address-line" name="address-line" placeholder="Input your address" required />
            </div>
        </div>

        <div class="set-default">
            <label for="set-default">
                <input type="checkbox" id="set-default" name="set-default" /> Set as default address
            </label>
        </div>

        <button type="button" id="apply-address" class="apply-address-btn">Apply</button>
    </div>
</div>

<div class="purchase-form-container" id="purchase-form-container">
    <form id="purchase-form" name="purchase-form" method="post" class="purchase-form">
        <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
        <input type="hidden" id="total" name="total_amount" value="<?php echo $total_amount; ?>">
        <h2>Detail Order</h2>

        <div class="pro-describe" style="flex-direction: column;">
            <?php
            // Include the database connection file
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "shopbangiay";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Assuming the `cart_id` is passed dynamically
            $cart_id = 1; // Replace with dynamic value if needed

            if (empty($cart_id)) {
                die("Giỏ hàng không hợp lệ!");
            }

            try {
                // Fetch cart items with product and sales details
                $stmt = $conn->prepare("
        SELECT 
            cp.id AS id_carthasproduct, 
            cp.idCart, 
            cp.idProduct, 
            cp.color, 
            cp.quantity, 
            cp.size,
            p.name AS product_name,
            p.price AS product_price,
            p.img AS product_img,
            s.discount 
        FROM carthasproduct cp
        JOIN products p ON cp.idProduct = p.id
        LEFT JOIN sales s ON p.idSale = s.id
        WHERE cp.idCart = ? AND cp.isPay = 'yes'
    ");
                $stmt->bind_param('i', $cart_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Extract the appropriate image based on the color
                        $imgParts = explode(',', $row['product_img']);
                        $selectedColor = $row['color'];
                        $baseImage = '';

                        foreach ($imgParts as $imgPart) {
                            $colorAndImage = explode('<>', $imgPart);
                            if (count($colorAndImage) === 2 && $colorAndImage[0] === $selectedColor) {
                                $baseImage = $colorAndImage[1];
                                break;
                            }
                        }

                        // Calculate the discounted price
                        $originalPrice = $row['product_price'];
                        $discount = isset($row['discount']) ? floatval($row['discount']) : 0;
                        $latestPrice = ($discount > 0 && $discount <= 100)
                            ? $originalPrice * (1 - $discount / 100)
                            : $originalPrice;

                        echo '<div class="product-describe">
                <label class="w-checkbox" style="display:none;">
                    <input type="checkbox" name="product_checkbox[]" value="' . $latestPrice * $row['quantity'] . '" class="w-checkbox-input product-checkbox" 
                    data-price="' . $latestPrice . '" data-product-id="' . $row['idProduct'] . '" />
                    <span class="w-form-label" for="product_checkbox"></span>
                </label>
                <div class="div-block-5">
                    <img class="imgShoe" src="../../Admin/img/' . $baseImage . '" loading="lazy" width="100" alt="Product Image" class="image-6" />
                    <div class="shoeName text-block-7">' . $row['product_name'] . '</div>
                </div>
                <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                    <div class="text-block-17">Quantity</div>
                    <div class="shoeQuantity text-block-17">' . $row['quantity'] . '</div>
                </div>
                <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                    <div class="text-block-17">Color</div>
                    <div class="color-options">
                        <div style="border-radius: 20px;;height: 2vw; width: 2.5vw;background-color: ' . $row['color'] . '"></div>
                    </div>
                </div>
                <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                    <div class="text-block-17">Size</div>
                    <div class="size text-block-17">' . $row['size'] . '</div>
                </div>
                <div class="div-block-7" style="flex-direction: column; grid-row-gap: 1vw;">
                    <div class="text-block-17">Price</div>
                    <div class="price text-block-17">' . number_format($latestPrice, 2) . ' VND</div>
                </div>
            </div>';
                    }
                } else {
                    echo "<p>Giỏ hàng của bạn hiện đang trống.</p>";
                }
                $stmt->close();
            } catch (Exception $e) {
                echo "Error fetching cart items: " . $e->getMessage();
            }

            $conn->close();
            ?>
        </div>
        <!-- xử lý sự kiện thoát trang cập nhật isPat=no -->
        <script>
            // Replace this with your cart_id from the server
            const cartId = <?php echo $idCart; ?>; // Dynamic value if needed
            let isGoToPay=false;
            // Function to update isPay = 'no'
            function updateIsPayToNo() {
                if(!isGoToPay){
                fetch('updateIsPay.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `cart_id=${cartId}&isPay=no`,
                    })
                    .then((response) => {
                        if (!response.ok) {
                            console.error('Failed to update isPay status.');
                        }
                    })
                    .catch((error) => console.error('Error:', error));
                }
            }

            // Trigger the function when the page is about to be unloaded
            window.addEventListener('beforeunload', (event) => {
                // Call the function to update the isPay status
                updateIsPayToNo();

                // Optionally display a confirmation message (optional and may be blocked by some browsers)
                event.returnValue = 'Are you sure you want to leave this page?';
            });
        </script>
        <div class="discount-voucher-container">
            <button type="button" class="show-discount" id="show-discount">Voucher</button>

            <div class="discount-voucher" id="discount-voucher" style="display: none;">
                <div class="voucher-input">
                    <label for="voucher-code">Voucher ID:</label>
                    <input type="text" id="voucher-code" name="voucher-code" placeholder="Nhập mã giảm giá" />
                </div>
                <button type="button" id="apply-discount" class="apply-discount-btn">Apply</button>
            </div>
        </div>

        <div class="additional-fees">
            <div class="fee-row">
                <span>Subtotal:</span>
                <span id="total"><?php echo $total . ' VND' ?></span>
            </div>
            <div class="fee-row">
                <span>Shipping fee :</span>
                <span id="shipping-fee"> 5 $</span>
            </div>
            <div class="fee-row">
                <label for="total-amount">Total payment:</label>
                <div id="total-amount"><?php echo $total . ' VND' ?></div>
            </div>
            <div class="discount-section" style="display: none;">
                <div class="fee-row">
                    <span>Product discount :</span>
                    <span id="product-discount">0 $</span>
                </div>
                <div class="fee-row">
                    <span>Shipping discount :</span>
                    <span id="shipping-discount">0 $</span>
                </div>
            </div>
        </div>


        <label for="payment-method">Payment method:</label>
        <select id="payment-method" name="payment-method" required>
            <option value="">Select method</option>
            <option value="cash-on-delivery">Cash on delivery</option>
            <option value="bank-transfer">Banking transactions using QR code</option>
        </select>



        <div id="bank-transfer-fields" style="display: none;">

            <div id="qr-code-container" style="margin-top: 20px; text-align: center;">
                <img src="../uploads/ảnhqr.png" alt="Bank Transfer QR Code" style="max-width: 100%; height: auto; border: 2px solid #ddd; padding: 10px; border-radius: 8px;">
            </div>
        </div>

        <button href="" id="submit-button" type="submit" class="submit-button">Purchase confirmation</button>

        <div class="w-form-done" style="display: none;">
            <div class="success-message">
                <p>Successful purchase! Information has been recorded.</p>
            </div>
        </div>

        <div class="w-form-fail" style="display: none;">
            <div class="error-message">
                <p>Transaction failed! Please fill in address information.</p>
            </div>
        </div>
    </form>
</div>

<script>
    let currentTotalAmount = 0;
    var addressSummary = '';
    let currentAddress = '';
    <?php if ($address): ?>
        var name = "<?php echo $address['name']; ?>";
        var phone = "<?php echo $address['phone']; ?>";
        var country = "<?php echo $address['country']; ?>";
        var city = "<?php echo $address['city']; ?>";
        var addressLine = "<?php echo $address['address_line']; ?>";
        var isDefault = "<?php echo $address['is_default']; ?>" === '1' ? true : false;
        console.log(name, phone, country, city, addressLine, isDefault);
        document.getElementById("show-address").value = true;

        addressSummary = `
        <p><strong>${name}</strong> | ${phone} | ${addressLine}, ${city || ''}, ${country} | ${isDefault ? "Default Address" : "Not Default"}</p>
    `;
        currentAddress = `${addressLine}, ${city || ''}, ${country}`;
        document.getElementById("show-address").innerHTML = addressSummary;

    <?php else: ?>
        addressSummary = "";
        document.getElementById("show-address").value = "";
    <?php endif; ?>


    let productDiscount = 0;
    let shippingDiscount = 0;

    productDiscount = parseFloat(document.getElementById('product-discount').innerText) || 0;
    shippingDiscount = parseFloat(document.getElementById('shipping-discount').innerText) || 0;

    function updateTotalAmount() {
        let totalAmount = <?php echo $total; ?> - productDiscount + shippingDiscount;
        $total_amount = <?php echo $total; ?>;
        document.getElementById('total-amount').innerHTML = totalAmount + " VND";
        document.getElementById('total').value = totalAmount;
    }

    updateTotalAmount();


    document.getElementById("show-address").addEventListener("click", function() {
        const addressForm = document.getElementById("address-form");
        addressForm.style.display = addressForm.style.display === "none" ? "block" : "none";
        const purchaseForm = document.getElementById("purchase-form-container");
        purchaseForm.style.display = purchaseForm.style.display === "none" ? "block" : "none";
    });

    document.getElementById("apply-address").addEventListener("click", function() {
        document.getElementById("show-address").value = true;
        var name = document.getElementById("name").value;
        var phone = document.getElementById("phone").value;
        var country = document.getElementById("country").value;
        var city = document.getElementById("city").value;
        var addressLine = document.getElementById("address-line").value;
        var isDefault = document.getElementById("set-default").checked ? 1 : 0;

        if (name && phone && country && addressLine) {
            hasAddress = true;
            var addressSummary = `
            <p><strong>${name}</strong> | ${phone} | ${addressLine}, ${city || ''}, ${country} | ${isDefault ? "Default Address" : "Not Default"}</p>
        `;

            document.getElementById("show-address").innerHTML = addressSummary;

            document.getElementById("address-form").style.display = "none";
            document.getElementById("purchase-form-container").style.display = "block";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_address.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };

            var user_id = <?php echo $cart_id; ?>;

            var data = "user_id=" + encodeURIComponent(user_id) +
                "&name=" + encodeURIComponent(name) +
                "&phone=" + encodeURIComponent(phone) +
                "&country=" + encodeURIComponent(country) +
                "&city=" + encodeURIComponent(city) +
                "&addressLine=" + encodeURIComponent(addressLine) +
                "&isDefault=" + isDefault;

            xhr.send(data);

        } else {
            alert("Please fill out all required fields.");
        }
    });

    function toggleDiscountSection() {
        const discountSection = document.querySelector('.discount-section');
        discountSection.style.display = discountSection.style.display === 'none' ? 'block' : 'none';
    }
    document.getElementById("show-discount").addEventListener("click", function() {
        const discountVoucher = document.getElementById("discount-voucher");
        discountVoucher.style.display = discountVoucher.style.display === "none" ? "flex" : "none";
    });


    document.getElementById('submit-button').addEventListener('click', function() {
        event.preventDefault();
        if (document.getElementById('payment-method').value == 'cash-on-delivery') {
            let totalAmount = 0;
            // Collect dynamic data
            const userId = <?php echo $idUser; ?>; // User ID from form
            if(currentTotalAmount != 0) totalAmount = currentTotalAmount;
            else totalAmount = <?= $total ?>; // Total amount passed from PHP
            let address = currentAddress
            const products = []; // Array to store product details

            // Collect product details dynamically
            document.querySelectorAll('.product-describe').forEach(product => {
                const img = product.querySelector('.imgShoe').getAttribute('src');
                const quantity = product.querySelector('.shoeQuantity').innerHTML;
                const color = product.querySelector('.color-options div').style.backgroundColor;
                const size = product.querySelector('.size').textContent;
                const price = product.querySelector('.price').textContent.trim();
                const name = product.querySelector('.shoeName').textContent.trim();
                console.log(name);
                products.push({
                    name: name,
                    img: img,
                    quantity: parseInt(quantity),
                    color: color,
                    size: size,
                    price: parseFloat(price.replace(/,/g, '')),
                });
            });
            isGoToPay=true;
            // Convert products to a JSON string for passing as query parameter
            const productsJSON = JSON.stringify(products);

            // Construct URL with dynamic query parameters
            const queryString = `?userId=${encodeURIComponent(userId)}&totalAmount=${encodeURIComponent(totalAmount)}&products=${encodeURIComponent(productsJSON)}&address=${encodeURIComponent(address)}&cart_id=${encodeURIComponent(<?php echo $cart_id; ?>)}`;

            // Redirect to process_purchase.php with query parameters
            window.location.href = 'process_purchase.php' + queryString;
        }
    });
    document.getElementById("apply-discount").onclick = function() {
        const discountCode = document.getElementById("voucher-code").value;
        console.log(discountCode);
        if (!discountCode) {
            alert("Please enter a discount code.");
            return;
        }

        // AJAX call to validate and fetch discount value
        fetch('validate_voucher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `discountCode=${encodeURIComponent(discountCode)}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const discountValue = parseFloat(data.discount); // Assume this is the discount percentage (e.g., 10 for 10%)
                    const totalAmountElement = document.getElementById("total-amount");
                    currentTotalAmount = <?= $total ?>;

                    // Calculate the discount amount (percentage of the total)
                    const discountAmount = (currentTotalAmount * discountValue) / 100;

                    // Apply the discount
                    currentTotalAmount -= discountAmount;

                    // Ensure the total amount is not negative
                    if (currentTotalAmount < 0) currentTotalAmount = 0;

                    // Update the total amount in the UI
                    totalAmountElement.textContent = currentTotalAmount.toFixed(2) + " $";
                    
                    // Change the text color to red if total amount is 0
                    if (currentTotalAmount === 0) {
                        totalAmountElement.style.color = "red";
                    } else {
                        // Reset color for non-zero amounts
                        totalAmountElement.style.color = "red";
                        totalAmountElement.style.fontWeight = "bold";
                    }

                    alert(`Voucher applied successfully! You saved ${discountValue.toFixed(2)}.`);
                } else {
                    alert("Invalid or expired voucher code.");
                }
            })
            .catch(error => console.error('Error:', error));
    };
</script>
<style>
    #bank-transfer-fields {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #bank-transfer-fields label {
        font-weight: bold;
        font-size: 14px;
        display: block;
        margin-bottom: 8px;
    }

    #bank-transfer-fields input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    #qr-code-container {
        margin-top: 20px;
        text-align: center;
    }

    #qr-code-container img {
        max-width: 100%;
        height: auto;
        border: 2px solid #ddd;
        padding: 10px;
        border-radius: 8px;
    }

    .button-8 {
        text-align: center;
        font-family: Georgia, Times, Times New Roman, serif;
        line-height: .5rem;
        font-size: 1rem;
        color: black;
        padding: 2px 15px;
        background-color: white !important;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .address-form {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .address-form h3 {
        margin-bottom: 15px;
        color: #333;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .contact-info .contact-input {
        margin-bottom: 15px;
    }

    .contact-info label {
        display: block;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .contact-info input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
    }

    .contact-info input:focus {
        border-color: #84e6f8;
        outline: none;
    }

    .address-info .address-input {
        margin-bottom: 15px;
    }

    .address-info label {
        display: block;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .address-info input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
    }

    .address-info input:focus {
        border-color: #84e6f8;
        outline: none;
    }

    .set-default {
        margin-top: 20px;
        text-align: center;
    }

    .set-default label {
        font-size: 14px;
        color: #333;
    }

    .set-default input {
        margin-right: 8px;
    }

    .apply-address-btn {
        background-color: #dc586d;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        width: 100%;
        max-width: 150px;
        margin: 20px auto 0;
        display: block;
    }

    .apply-address-btn:hover {
        background-color: #45a049;
    }

    .apply-address-btn:active {
        background-color: #3c8c3b;
    }

    .purchase-form-container {
        width: 90%;
        max-width: 100vw;
        margin: 0 auto;
        padding: 25px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .purchase-form h2 {
        text-align: center;
        font-size: 26px;
        margin-bottom: 20px;
    }

    .purchase-form label {
        font-weight: bold;
    }

    .purchase-form input,
    .purchase-form select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .product-describe {
        display: flex;
        align-items: center;
        border: 1px solid #eee;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 6px;
        background-color: #f5f5f5;
        grid-column-gap: 2vw;
        grid-row-gap: 2vw;
    }

    .product-describe img {
        width: 60px;
        border-radius: 4px;
    }

    .text-block-7 {
        margin-left: 10px;
        font-weight: bold;
        color: #333;
    }

    .div-block-5 {
        grid-column-gap: 5vw;
        grid-row-gap: 1vw;
        justify-content: flex-start;
        align-items: center;
        margin-left: 3vw;
        display: flex;
    }

    .div-block-7 {
        grid-column-gap: 14vw;
        grid-row-gap: 14vw;
        justify-content: flex-start;
        align-items: center;
        margin-left: 4vw;
        display: flex;
    }

    .div-block-7 {
        grid-column-gap: 10vw;
        grid-row-gap: 10vw;
    }

    .discount-voucher-container {
        margin-top: 20px;
        text-align: center;
    }

    .show-discount {
        background-color: white;
        color: black;
        border: 1px solid #ccc;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        /* Added transition for smooth change */
        width: 100%;
    }

    .show-discount:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        /* Increased shadow on hover */
    }

    .show-discount span {
        margin-right: 8px;
    }

    .show-discount .arrow {
        font-size: 18px;
    }

    .discount-voucher {
        display: none;
        margin-top: 10px;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        flex-direction: column;
    }

    .voucher-input {
        margin-bottom: 15px;
    }

    .voucher-input label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .voucher-input input {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .apply-discount-btn {
        background-color: #84e6f8;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
    }

    .apply-discount-btn:hover {
        background-color: #45a049;
    }


    .additional-fees {
        text-align: right;
        margin-top: 20px;
        font-size: 16px;
    }

    .fee-row {
        display: flex;
        justify-content: space-between;
        /* Space between label and value */
        margin-bottom: 10px;
    }

    .fee-row span {
        display: inline-block;
        margin-right: 10px;
    }



    .discount-section {
        margin-top: 15px;
    }

    #apply-discount {
        background-color: #DC586D;
        color: white;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        border-radius: 5px;
    }

    #apply-discount:hover {
        background-color: #45a049;
    }

    .submit-button {
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        background-color: #DC586D;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 18px;
        transition: background-color 0.3s;
    }

    .submit-button:hover {
        background-color: #FB9590;
    }

    .w-form-done,
    .w-form-fail {
        display: none;
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
        text-align: center;
    }

    .success-message {
        background-color: #e0ffe0;
        border: 1px solid #4CAF50;
        color: #4CAF50;
    }

    .error-message {
        background-color: #ffe0e0;
        border: 1px solid #F44336;
        color: #F44336;
    }

    .address-container {
        margin-top: 20px;
        text-align: center;
    }

    .show-address {
        background-color: white;
        color: black;
        border: 1px solid #ccc;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        margin: 10px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: left;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        width: 98%;
    }

    .show-address:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        /* Increased shadow on hover */
    }

    .show-address span {
        margin-right: 8px;
    }

    .show-address .arrow {
        font-size: 18px;
    }

    .address-form {
        display: none;
        margin-top: 15px;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        flex-direction: column;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    .address-input {
        margin-bottom: 15px;
    }

    .address-input label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
        color: #333;
    }

    .address-input input {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
        color: #333;
    }



    .apply-address-btn:hover {
        background-color: #45a049;
    }

    .apply-address-btn:active {
        background-color: #3c8c3b;
    }
</style>
<style>

</style>