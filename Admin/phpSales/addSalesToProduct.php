<?php
include('../database.php');

// Lấy danh sách sales
$sql = "SELECT * FROM sales";
$result = mysqli_query($conn, $sql);
$sales = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

// Lấy danh sách sản phẩm với idSale = 0
$sql = "SELECT id, name, img, gender, price FROM products WHERE idSale = 0";
$result = mysqli_query($conn, $sql);
$available_shoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="../index.php" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="../index.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="main">Main</li>
            <li>
                <a href="#"><i class='bx bxs-package icon'></i> Product <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="../productView.php">View Product</a></li>
                    <li><a href="../addnewProduct.php">Add Product</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class='bx bxs-user-circle icon'></i> User <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="../phpUser/userView.php">View User</a></li>
                    <li><a href="../phpUser/addnewUser.php">Add User</a></li>
                </ul>
            </li>
            <li class="divider" data-text="Commerce">Commerce</li>
            <li><a href="../phpOrders/ordersView.php"><i class='bx bx-cart-alt icon'></i> Orders</a></li>
            <li>
                <a href="#"><i class='bx bxs-purchase-tag-alt icon'></i> Sales <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="salesView.php">View Sales</a></li>
                    <li><a href="addSalesToProduct.php">Add sales to product</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search... ">
                    <!-- <i style="margin-top:9px;margin-right:190px" class='bx bx-search icon'></i> -->
                </div>
            </form>
            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
            </a>
            <span class="divider"></span>
            <div class="profile">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                <ul style="z-index: 100000px;" class="profile-link">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">MANAGE PRODUCT's SALE</h1>
        <div class="row">
            <!-- Sales Table -->
            <div class="col-md-12">
                <h3>Sales Table</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Product Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale) : ?>
                            <?php
                            // Đếm số lượng sản phẩm có idSale tương ứng với sale hiện tại
                            include('../database.php');
                            $sale_id = $sale['id'];
                            $count_sql = "SELECT COUNT(*) AS product_count FROM products WHERE idSale = $sale_id";
                            $count_result = mysqli_query($conn, $count_sql);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $product_count = $count_row['product_count'];
                            mysqli_free_result($count_result);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sale['id']); ?></td>
                                <td><?php echo htmlspecialchars($sale['name']); ?></td>
                                <td><?php echo $product_count; ?></td>
                                <td><!-- Button inside Sales Table -->
                                    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#addSalesModal">
                                        Add Sales to Product
                                    </button>
                                    <button type="button" class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#manageSalesModal"
                                        data-sale-id="<?php echo $sale['id']; ?>">
                                        Manage Product that has this Sale
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Sales Modal -->
    <div class="modal fade" id="addSalesModal" tabindex="-1" aria-labelledby="addSalesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSalesModalLabel">Add Sales to Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search and Filter Controls -->
                    <div class="d-flex justify-content-between mb-3">
                        <input type="text" id="productSearch" class="form-control me-2" placeholder="Search products...">
                        <select id="genderFilter" class="form-select" aria-label="Gender filter">
                            <option value="all" selected>All</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Unisex">Unisex</option>
                        </select>
                        <button type="button" id="selectAllButton" class="btn btn-secondary ms-2">Select All</button>
                    </div>

                    <form action="add_sales_to_product.php" method="POST">
                        <div class="mb-3">
                            <label>Select Products to Apply Sale</label>
                            <div class="row">
                                <?php foreach ($available_shoes as $shoe) {
                                    // Split "color<>image" pairs
                                    $imgGroups = explode(",", $shoe['img']);
                                    list($color, $imgFile) = explode("<>", $imgGroups[0]);
                                ?>
                                    <div class="col-6 mb-3 product-item" data-name="<?php echo htmlspecialchars($shoe['name']); ?>" data-gender="<?php echo $shoe['gender']; ?>">
                                        <div class="form-check position-relative border rounded p-2 shadow-sm">
                                            <input style="z-index: 100;" class="form-check-input position-absolute top-0 start-0 m-2"
                                                type="checkbox"
                                                name="products[]"
                                                value="<?php echo $shoe['id']; ?>"
                                                id="product_<?php echo $shoe['id']; ?>">
                                            <div class="product-box position-relative">
                                                <!-- Circle with dynamic color -->
                                                <div class="circle" style="background-color: <?php echo htmlspecialchars($color); ?>;"></div>
                                                <!-- Product image -->
                                                <img src="../img/<?php echo htmlspecialchars($imgFile); ?>" alt="Product Image" class="product-img">
                                                <label class="form-check-label mt-2 text-start w-100" for="product_<?php echo $shoe['id']; ?>">
                                                    <?php echo htmlspecialchars($shoe['name']); ?>
                                                </label>
                                                <label class="form-check-label mt-2 text-end w-100" for="product_<?php echo $shoe['id']; ?>">
                                                    <?php echo $shoe['price']; ?>VND
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Manage Sales Modal -->
    <div class="modal fade" id="manageSalesModal" tabindex="-1" aria-labelledby="manageSalesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageSalesModalLabel">Manage Products with Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="remove_sales_from_product.php" method="POST">
                        <div class="mb-3">
                            <label>Products with Sale</label>
                            <div class="row">
                                <?php
                                $sql = "SELECT id, name, img, gender, price FROM products WHERE idSale = $sale_id";
                                $result = mysqli_query($conn, $sql);
                                $sale_products = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                mysqli_free_result($result);
                                mysqli_close($conn);
                                ?>
                                <?php foreach ($sale_products as $product) {
                                    // Assume $sale_products contains products linked to the current sale
                                    $imgGroups = explode(",", $product['img']);
                                    list($color, $imgFile) = explode("<>", $imgGroups[0]);
                                ?>
                                    
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("click", function (event) {
        if (event.target.closest(".remove-btn")) {
            const button = event.target.closest(".remove-btn");
            const productId = button.getAttribute("data-id");

            if (productId) {
                // Gửi yêu cầu AJAX
                fetch("remove_sales_from_product.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `product_id=${productId}`,
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "success") {
                        // Xóa phần tử khỏi giao diện
                        const productItem = button.closest(".product-item");
                        if (productItem) {
                            productItem.remove();
                        }
                    } else {
                        console.error("Error removing product:", data);
                    }
                })
                .catch(error => console.error("Fetch error:", error));
            }
        }
    });
</script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    var manageSalesModal = document.getElementById('manageSalesModal');
    manageSalesModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var saleId = button.getAttribute('data-sale-id'); // Extract sale ID from data attribute

        // Optional: Update the modal title to include the sale ID
        var modalTitle = manageSalesModal.querySelector('.modal-title');
        modalTitle.textContent = 'Manage Products with Sale ID: ' + saleId;

        // Load products dynamically via AJAX or update a hidden input for form submission
        var modalBody = manageSalesModal.querySelector('.modal-body');

        // Example using AJAX to fetch products for the given sale ID
        fetch(`fetch_products_by_sale.php?sale_id=${saleId}`)
            .then(response => response.text())
            .then(data => {
                modalBody.innerHTML = data; // Replace modal body with fetched content
            })
            .catch(error => {
                console.error('Error fetching sale products:', error);
                modalBody.innerHTML = '<p class="text-danger">Failed to load products.</p>';
            });
    });
});
</script>

    <style>
        .product-box {
            position: relative;
            text-align: center;
        }

        .circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .product-img {
            width: 90px;
            height: 70px;
            position: relative;
            z-index: 2;
            margin-top: 10px;
        }

        /* Border for each product */
        .product-item .form-check {
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .product-item .form-check:hover {
            border-color: #007bff;
        }
    </style>

    <script>
        // JavaScript for search functionality
        document.getElementById('productSearch').addEventListener('input', function() {
            let searchQuery = this.value.toLowerCase();
            document.querySelectorAll('.product-item').forEach(function(item) {
                let productName = item.getAttribute('data-name').toLowerCase();
                if (productName.includes(searchQuery)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // JavaScript for gender filter
        document.getElementById('genderFilter').addEventListener('change', function() {
            let selectedGender = this.value;
            document.querySelectorAll('.product-item').forEach(function(item) {
                let itemGender = item.getAttribute('data-gender');
                if (selectedGender === 'all' || itemGender === selectedGender) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // JavaScript for Select All / Deselect All functionality
        let selectAll = true;
        document.getElementById('selectAllButton').addEventListener('click', function() {
            document.querySelectorAll('.product-item').forEach(function(item) {
                if (item.style.display !== 'none') {
                    let checkbox = item.querySelector('input[type="checkbox"]');
                    checkbox.checked = selectAll;
                }
            });
            this.textContent = selectAll ? 'Deselect All' : 'Select All';
            selectAll = !selectAll;
        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='../script.js'></script>
</body>

</html>