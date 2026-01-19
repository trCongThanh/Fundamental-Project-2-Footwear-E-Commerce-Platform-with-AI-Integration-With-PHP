<?php
// Include the database connection file
include '../homepage/database.php';

// Fetch cart items for a specific cart ID (assuming `idCart` is passed via a session or GET parameter)
$idCart = 1; // Replace with dynamic value if needed

try {
    // Prepare the SQL query to fetch cart details
    $stmt = $conn->prepare("
       SELECT 
            cp.id AS id_carthasproduct, 
            cp.idCart, 
            cp.idProduct, 
            cp.color, 
            cp.quantity, 
            cp.size,
            p.name,
            p.price,
            p.idSale, 
            p.img,
            s.discount -- Include the discount if there's a sale
       FROM carthasproduct cp
       JOIN products p ON cp.idProduct = p.id
       LEFT JOIN sales s ON p.idSale = s.id
       WHERE cp.idCart = ?
    ");
    $stmt->bind_param('i', $idCart);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all cart items into an array
    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        // Process the `p.img` string to find the correct image for the selected color
        $imgParts = explode(',', $row['img']); // Split by commas
        $selectedColor = $row['color'];
        $baseImage = '';

        foreach ($imgParts as $imgPart) {
            $colorAndImage = explode('<>', $imgPart); // Split by <>
            if (count($colorAndImage) === 2 && $colorAndImage[0] === $selectedColor) {
                $baseImage = $colorAndImage[1]; // Assign the correct image filename
                break;
            }
        }

        // Add the discount logic
        $originalPrice = $row['price'];
        $discount = isset($row['discount']) ? floatval($row['discount']) : 0; // Convert discount to a numeric value

        // Validate and apply the discount
        if ($discount > 0 && $discount <= 100) {
            $latestPrice = $originalPrice * (1 - $discount / 100);
        } else {
            $latestPrice = $originalPrice; // Default to original price if discount is invalid
        }


        // Add processed image and price details to the row
        $row['baseImage'] = $baseImage;
        $row['originalPrice'] = $originalPrice;
        $row['latestPrice'] = $latestPrice;
        $cartItems[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {
    echo "Error fetching cart items: " . $e->getMessage();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Shopee Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow">
        <div class="container">
            <!-- Hamburger Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo Centered -->
            <a class="navbar-brand mx-auto" onclick="window.location.href='../homepage/homepage.php'">ShoeStore</a>

            <!-- Navbar Links and Buttons -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Links -->
                <ul class="navbar-nav me-auto flex-column flex-lg-row align-items-lg-center gap-0 gap-lg-5">
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=male'">Male</a></li>
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=female'">Female</a></li>
                    <li class="nav-item"><a class="nav-link" onclick="window.location.href='../homepage/homepage.php?gender=unisex'">Unisex</a></li>
                </ul>

                <!-- Right Buttons <i class="bi bi-search"></i> -->
                <div class="d-flex mt-3 mt-lg-0 justify-content-lg-end gap-0 gap-lg-5">
                    <button class="btn btn-outline-secondary me-2 nav-btn" onclick="window.location.href='../../Search/search.php?keyword=&price='">
                        <i class="bi bi-search"></i> Search
                    </button>

                    <button class="btn btn-outline-success me-2 nav-btn" id="cartButton">
                        <i class="bi bi-cart"></i> Cart
                    </button>
                    <script>
                        document.getElementById("cartButton").addEventListener("click", function() {
                            const currentUserId = <?php echo $idUser; ?>;
                            window.location.href = `../cart/cart.php?user_id=${currentUserId}`;
                        });
                    </script>
                    <button class="btn btn-outline-primary nav-btn" onclick="window.location.href='../profile/profile.php'">
                        <i class="bi bi-person"></i> Profile
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <style>
        /* Logo Styling */
        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Center Links on Responsive */
        @media (max-width: 991.98px) {
            .navbar-nav {
                align-items: flex-start;
                /* Links stack vertically */
            }

            .navbar-collapse .d-flex {
                justify-content: center;
                /* Buttons remain centered below menu */
                flex-wrap: wrap;
            }

            .nav-btn {
                width: 100%;
                /* Full width buttons on small screens */
                margin: 5px 0;
            }
        }

        /* Navbar Styling */
        .nav-link {
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #007bff;
            text-decoration: none;
        }

        .nav-btn {
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive: Align menu in column on small devices */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                display: flex;
                flex-direction: column;
            }

            .navbar-nav {
                flex-direction: column;
            }

            .nav-btn {
                width: 100%;
                margin: 5px 0;
            }
        }

        /* Social Media Links */
        .social-link {
            color: #fff;
            font-size: 1.5rem;
            transition: color 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            /* Xóa dấu gạch chân */
        }

        .social-link:hover {
            color: #007bff;
            /* Highlight color on hover */
            transform: scale(1.2);
            /* Slight zoom effect */
        }

        .social-link i {
            vertical-align: middle;
            /* Đảm bảo icon căn chỉnh đẹp */
        }
    </style>
    <div class="container py-4">
        <h2 class="mb-4">My cart:</h2>
        <div class="cart">
            <div class="row border-bottom pb-3 align-items-center gap-3">
                <div class="col-1 text-center">
                    <input type="checkbox" class="form-check-input select-all">
                </div>
                <div class="col-3 col-lg-2">Products</div>
                <div class="col-3 col-lg-2 text-center">Name</div>
                <div class="col-2 col-lg-1 text-center">Color</div>
                <div class="col-2 col-lg-1 text-center">Size</div>
                <div class="col-2 col-lg-2 text-center">Price</div>
                <div class="col-2 col-lg-2 text-center">Quantity</div>
                <div class="col-2 col-lg-2 text-center">Total</div>
            </div>

            <!-- PHP Loop to Render Cart Items -->
            <?php
            foreach ($cartItems as $item) {
                $itemTotal = $item['latestPrice'] * $item['quantity'];
                $imagePath = '../../Admin/img/' . htmlspecialchars($item['baseImage']);
                echo '
    <div class="cart-item row py-3 align-items-center" data-id="' . htmlspecialchars($item['id_carthasproduct']) . '">
        <div class="col-1 text-center">
            <input type="checkbox" class="form-check-input select-item">
        </div>
        <div class="col-3 col-lg-2">
            <img  style="width: 5rem; height: auto;" src="' . $imagePath . '" alt="' . htmlspecialchars($item['baseImage']) . '" class="productIMG me-2 rounded">
        </div>
        <div class="col-3 col-lg-2 text-center">' . htmlspecialchars($item['name']) . '</div>
        <div class="col-2 col-lg-1 text-center">' . htmlspecialchars($item['color']) . '</div>
        <div class="col-2 col-lg-1 text-center">' . htmlspecialchars($item['size']) . '</div>
        <div class="col-2 col-lg-2 text-center price" data-price="' . $item['latestPrice'] . '">' . number_format($item['latestPrice'], 0, ',', '.') . 'đ</div>
        <div class="col-2 col-lg-2 text-center">
            <div class="input-group quantity-group">
                <button class="btn btn-light btn-sm decrease-btn">-</button>
                <input type="number" class="form-control text-center quantity-input" value="' . htmlspecialchars($item['quantity']) . '" min="1">
                <button class="btn btn-light btn-sm increase-btn">+</button>
            </div>
        </div>
        <div class="col-2 col-lg-2 text-center total-price">' . number_format($itemTotal, 0, ',', '.') . 'đ</div>
    </div>';
            }
            ?>

        </div>

        <div class="row mt-4">
            <div class="col-lg-6 col-md-6 mb-3 mt-4">
                <button class="btn btn-danger btn-sm delete-selected p-2">Delete selected <br> products</button>
            </div>
            <div class="col-lg-6 col-md-6 text-end">
                <p class="mb-1">Total amount: <span class="fw-bold total-amount">0 VND</span></p>
                <button id='payButton' class="btn btn-warning btn-lg">Payment</button>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="mt-5 bg-dark text-white text-center py-4">
        <div class="mt-5 container3">
            <!-- Social Media Icons -->
            <div class="mb-3">
                <a href="https://facebook.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="social-link me-3">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://youtube.com" target="_blank" class="social-link">
                    <i class="bi bi-youtube"></i>
                </a>
            </div>
            <!-- About Us and Contact With Us Buttons -->
            <div class="mb-3">
                <a href="../../aboutus/about_us.php" class="btn btn-outline-light me-3">About Us</a>
                <a href="../../contact/contact.php" class="btn btn-outline-light">Contact With Us</a>
            </div>
            <!-- Copyright -->
            <p>&copy; 2024 ShoeStore. All rights reserved. Design by TPN Team VKU 23JIT</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>

<style>
    body {
        background-color: #f8f9fa;
    }

    .cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .quantity-group .btn {
        width: 30px;
        height: 30px;
        line-height: 1;
    }

    .total-amount {
        color: #d9534f;
        font-size: 1.5rem;
    }

    .cart {
        max-height: 500px;
        overflow-x: hidden;
        overflow-y: hidden;
    }

    /* Ensure the table content fits in the scrollable container */
    .cart .row {
        display: flex;
        flex-wrap: nowrap;
    }

    .cart .col-1,
    .cart .col-2,
    .cart .col-3 {
        flex: 1;
    }

    @media (max-width: 768px) {

        .cart .col-1,
        .cart .col-2,
        .cart .col-3 {
            flex: 1 1 100%;
            text-align: center;
        }

        .cart {
            max-height: 500px;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .productIMG {
            width: 100%;
            height: auto;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemCheckboxes = document.querySelectorAll('.select-item');
        const totalAmountEl = document.querySelector('.total-amount');
        const deleteSelectedBtn = document.querySelector('.delete-selected');

        // Cập nhật tổng tiền của từng mục
        const updateTotals = (item) => {
            const price = parseFloat(item.querySelector('.price').dataset.price);
            const quantity = parseInt(item.querySelector('.quantity-input').value, 10);
            const total = price * quantity;
            item.querySelector('.total-price').innerText = `${total.toLocaleString()}đ`;
            calculateTotal();
        };

        // Tính tổng tiền của các mục đã chọn
        const calculateTotal = () => {
            let total = 0;
            itemCheckboxes.forEach(itemCheckbox => {
                if (itemCheckbox.checked) {
                    const item = itemCheckbox.closest('.cart-item');
                    const priceText = item.querySelector('.total-price').innerText;
                    const price = parseFloat(priceText.replace(/[^0-9]/g, ''));
                    total += price;
                }
            });
            totalAmountEl.innerText = `${total.toLocaleString()}đ`;
        };

        // Xóa các mục đã chọn
        const deleteSelectedItems = () => {
            deleteSelected();
            const selectedItems = document.querySelectorAll('.select-item:checked');

            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một mục để xóa.');
                return;
            }

            // Xóa từng mục đã chọn
            selectedItems.forEach(item => {
                const cartItem = item.closest('.cart-item');
                item.checked = false;
                if (cartItem) {
                    cartItem.remove(); // Loại bỏ mục khỏi giao diện
                }
            });

            // Cập nhật danh sách checkbox và tính lại tổng tiền
            calculateTotal();

        };


        // Xử lý sự kiện tăng, giảm và nhập số lượng
        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const decreaseBtn = cartItem.querySelector('.decrease-btn');
            const increaseBtn = cartItem.querySelector('.increase-btn');
            const quantityInput = cartItem.querySelector('.quantity-input');

            decreaseBtn.addEventListener('click', () => {
                let currentQuantity = parseInt(quantityInput.value, 10);
                if (currentQuantity > 1) {
                    quantityInput.value = --currentQuantity;
                    updateTotals(cartItem);
                }
            });

            increaseBtn.addEventListener('click', () => {
                let currentQuantity = parseInt(quantityInput.value, 10);
                quantityInput.value = ++currentQuantity;
                updateTotals(cartItem);
            });

            quantityInput.addEventListener('input', () => {
                let currentQuantity = parseInt(quantityInput.value, 10);
                if (currentQuantity < 1) {
                    quantityInput.value = 1; // Đảm bảo số lượng ít nhất là 1
                }
                updateTotals(cartItem);
            });
        });

        // Xử lý khi thay đổi checkbox
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        // Gắn sự kiện cho nút "Xóa các mục đã chọn"
        deleteSelectedBtn.addEventListener('click', deleteSelectedItems);

        // Tính tổng tiền ban đầu
        calculateTotal();
    });
</script>
<script>
    let selectedItems = [];
    let deleteSelectedItems = [];
    let total = 0;

    function checkProduct() {
        // Collect selected items
        document.querySelectorAll('.select-item:checked').forEach((checkbox) => {
            const cartItem = checkbox.closest('.cart-item');
            const id = cartItem.dataset.id;
            const quantity = cartItem.querySelector('.quantity-input').value;
            const price = parseFloat(cartItem.querySelector('.price').dataset.price);

            selectedItems.push({
                id,
                quantity
            });
            total += price * quantity;
        });
    }
    document.getElementById('payButton').addEventListener('click', () => {


        checkProduct();

        if (selectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một mục để thanh toán.');
            return;
        }

        // Send selected items to the backend
        fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: selectedItems
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to payment page with cart ID and total
                    window.location.href = `../pay/checkpage.php?cart_id=<?php echo $idCart; ?>&total=${total}`;
                } else {
                    alert('Đã xảy ra lỗi khi cập nhật giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi kết nối đến server.');
            });
    });

    function deleteSelected() {
        checkProduct();
        deleteSelectedItems = selectedItems;
        if (deleteSelectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một mục để thanh toán.');
            return;
        }
        console.log('okkkkkkkkkkkkk')
        // Send selected items to the backend
        fetch('delete_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    items: deleteSelectedItems
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                } else {
                    alert('Đã xảy ra lỗi khi cập nhật giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi kết nối đến server.');
            });
    }
</script>