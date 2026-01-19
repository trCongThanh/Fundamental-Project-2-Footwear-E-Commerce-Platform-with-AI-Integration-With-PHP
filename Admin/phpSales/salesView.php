<?php
include('../database.php');

// Lấy dữ liệu từ bảng user
$sql = "SELECT * FROM sales";
$result = mysqli_query($conn, $sql);
$sales = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
?>
<?php
include('../database.php');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['action']) && $data['action'] === 'addSale') {
    $name = $data['name'];
    $pos = $data['pos'];
    $color = $data['color'];
    $bigsales = $data['bigsales'];

    $stmt = $conn->prepare("INSERT INTO sales (name, pos, color, bigsales) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit; // Stop further execution
    }

    $stmt->bind_param("ssss", $name, $pos, $color, $bigsales);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding user: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
<?php
include('../database.php');
// Get the raw POST data
$json = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json, true);

// Check if required fields are present
if (isset($data['action']) && $data['action'] === 'editSale') {

    // Assign variables for easier reference
    $saleId = $data['id'];
    $name = $data['name'];
    $pos = $data['pos'];
    $color = $data['color'];
    $bigsales = $data['bigsales'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE sales SET name = ?, pos = ?, color = ?, bigsales = ? WHERE id = ?");

    // Bind parameters
    $stmt->bind_param("ssssi", $name, $pos, $color, $bigsales, $saleId);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were affected (i.e., user updated)
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes were made.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khuyến Mãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script> <!-- TinyMCE library -->
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <style>
        *,
        html {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        figure {
            margin: 0px;
        }

        img {
            max-width: 100%;
        }

        /* ================== Badge Products CSS ========================*/
        .products {
            max-width: 100%;
            margin: 0 auto;
        }

        .products ul {
            margin: 0px;
            text-align: center;
        }

        .products ul li {
            width: 320px;
            height: 213px;
            background: #f8f8f8;
            display: inline-block;
            position: relative;
            margin: 15px;
            padding: 0px;
            box-sizing: border-box;
        }

        /* ================== Badge Overlay CSS ========================*/
        .badge-overlay {
            position: absolute;
            left: 0%;
            top: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
            /*  pointer-events:none; */
            -webkit-transition: width 1s ease, height 1s ease;
            -moz-transition: width 1s ease, height 1s ease;
            -o-transition: width 1s ease, height 1s ease;
            transition: width 0.4s ease, height 0.4s ease;

        }

        /* ================== Badge CSS ========================*/
        .badge {
            margin: 0;
            padding: 0;
            color: white;
            padding: 10px 10px;
            font-size: 15px;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            line-height: normal;
            text-transform: uppercase;
            background: #ed1b24;
        }

        .badge::before,
        .badge::after {
            content: "";
            position: absolute;
            top: 0;
            margin: 0 -1px;
            width: 100%;
            height: 100%;
            background: inherit;
            min-width: 55px;
        }

        .badge::before {
            right: 100%;
        }

        .badge::after {
            left: 100%;
        }

        /* ================== Badge Position CSS ========================*/
        .top-left {
            position: absolute;
            top: 0;
            left: 0;
            -ms-transform: translateX(-30%) translateY(0%) rotate(-45deg);
            -webkit-transform: translateX(-30%) translateY(0%) rotate(-45deg);
            transform: translateX(-30%) translateY(0%) rotate(-45deg);
            -ms-transform-origin: top right;
            -webkit-transform-origin: top right;
            transform-origin: top right;
        }

        .top-right {
            position: absolute;
            top: 0;
            right: 0;
            -ms-transform: translateX(30%) translateY(0%) rotate(45deg);
            -webkit-transform: translateX(30%) translateY(0%) rotate(45deg);
            transform: translateX(30%) translateY(0%) rotate(45deg);
            -ms-transform-origin: top left;
            -webkit-transform-origin: top left;
            transform-origin: top left;
        }

        .bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            -ms-transform: translateX(-30%) translateY(0%) rotate(45deg);
            -webkit-transform: translateX(-30%) translateY(0%) rotate(45deg);
            transform: translateX(-30%) translateY(0%) rotate(45deg);
            -ms-transform-origin: bottom right;
            -webkit-transform-origin: bottom right;
            transform-origin: bottom right;
        }

        .bottom-right {
            position: absolute;
            bottom: 0;
            right: 0;
            -ms-transform: translateX(30%) translateY(0%) rotate(-45deg);
            -webkit-transform: translateX(30%) translateY(0%) rotate(-45deg);
            transform: translateX(30%) translateY(0%) rotate(-45deg);
            -ms-transform-origin: bottom left;
            -webkit-transform-origin: bottom left;
            transform-origin: bottom left;
        }

        .top-full {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .middle-full {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            text-align: center;
            -ms-transform: translateX(0%) translateY(-50%) rotate(0deg);
            -webkit-transform: translateX(0%) translateY(-50%) rotate(0deg);
            transform: translateX(0%) translateY(-50%) rotate(0deg);
        }

        .bottom-full {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }

        /* ================== Badge color CSS ========================*/
        .badge.red {
            background: #ed1b24;
        }

        .badge.orange {
            background: #fa7901;
        }

        .badge.pink {
            background: #ee2b8b;
        }

        .badge.blue {
            background: #00adee;
        }

        .badge.green {
            background: #b4bd00;
        }

        .big-sale-elements {
            display: none;
        }

        .badge.dynamic-color {
            /* This serves as a placeholder and can be overwritten */
            background: #ccc;
        }

        /* Styles for the styled text editor */
        .styled-text-editor {
            border: 1px solid #ced4da;
            padding: 10px;
            min-height: 100px;
            resize: vertical;
        }

        a.tox-promotion-link {
            visibility: hidden;
        }

        .tox-statusbar__right-container {
            visibility: hidden;
        }

        /* Font Awesome icon styles */
        .icon-pencil,
        .icon-trash {
            position: absolute;
            font-size: 1em;
            cursor: pointer;
            color: #fff;
            padding: 5px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        /* Position for edit and delete icons */
        .icon-pencil {
            top: 10px;
            right: 40px;
            /* Adjust as needed */
            background-color: aqua;
            /* Color for edit */
        }

        .icon-trash {
            top: 10px;
            right: 10px;
            /* Adjust as needed */
            background-color: red;
            /* Color for delete */
        }
    </style>
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
    <div class="container mt-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Sale</button>
    </div>

    <div class="products mt-3">
        <ul id="productList">
            <!-- Static Example Product -->
            <li>
                <figure>
                    <img src="imgSales/sample.png" alt="Sample Product">
                </figure>
                <div class="badge-overlay">
                    <span class="top-left badge orange badge-click">Sale 50% Off</span>

                    <!-- Edit and Delete Icons with Font Awesome -->
                    <span class="icon-pencil" onclick="editProduct('sample-id')"><i class="fas fa-pencil-alt"></i></span>
                    <span class="icon-trash" onclick="deleteProduct('sample-id')"><i class="fas fa-trash"></i></span>
                </div>
            </li>

            <!-- Dynamically Rendered Products -->
            <?php foreach ($sales as $sale): ?>
                <li>
                    <!-- Product Image -->
                    <figure>
                        <img src="imgSales/sample.png" alt="<?= htmlspecialchars($sale['name']) ?>">
                    </figure>

                    <!-- Badge Overlay with Edit/Delete Icons -->
                    <div class="badge-overlay">
                        <span
                            class="<?= htmlspecialchars($sale['pos']) ?> badge dynamic-color"
                            style="background-color: <?= htmlspecialchars($sale['color']) ?>;"
                            <?php if (!empty($sale['bigsales'])): ?>
                            onclick="window.location.href='info.php?product=<?= urlencode($sale['id']) ?>'"
                            <?php endif; ?>>
                            <?= htmlspecialchars($sale['name']) ?>
                        </span>

                        <!-- Font Awesome Edit and Delete Icons -->
                        <span class="icon-pencil" onclick="editProduct(<?= urlencode($sale['id']) ?>)"><i class="fas fa-pencil-alt"></i></span>
                        <span class="icon-trash" onclick="deleteProduct(<?= urlencode($sale['id']) ?>, '<?= $sale['bigsales'] ?>')">
                            <i class="fas fa-trash"></i>
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <!-- Toggle Switch for Sale Type -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="saleTypeSwitch">
                            <label class="form-check-label" for="saleTypeSwitch">Big Sale</label>
                        </div>
                        <!-- Big Sale Additional Fields -->
                        <div id="bigSaleFields" style="display: none;">
                            <div class="mb-3">
                                <label for="bannerUpload" class="form-label">Upload Banner</label>
                                <input type="file" class="form-control" id="bannerUpload">
                            </div>
                            <div class="mb-3">
                                <label for="formattedText" class="form-label">Additional Information</label>
                                <textarea id="formattedText" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="badgeText" class="form-label">Badge Text</label>
                            <input type="text" class="form-control" id="badgeText" required>
                        </div>
                        <div class="mb-3">
                            <label for="badgeColor" class="form-label">Badge Color</label>
                            <input type="color" class="form-control" id="badgeColor" required>
                        </div>
                        <div class="mb-3">
                            <label for="badgePosition" class="form-label">Badge Position</label>
                            <select class="form-select" id="badgePosition" required>
                                <option value="top-left">Top Left</option>
                                <option value="top-right">Top Right</option>
                                <option value="bottom-left">Bottom Left</option>
                                <option value="bottom-right">Bottom Right</option>
                                <option value="top-full">Top Full</option>
                                <option value="middle-full">Middle Full</option>
                                <option value="bottom-full">Bottom Full</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <!-- Toggle Switch for Sale Type -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="editSaleTypeSwitch">
                            <label class="form-check-label" for="editSaleTypeSwitch">Big Sale</label>
                        </div>
                        <!-- Big Sale Additional Fields -->
                        <div id="editBigSaleFields" style="display: none;">
                            <div class="mb-3">
                                <img id="editBannerPreview" src="" alt="">
                            </div>
                            <div class="mb-3">
                                <label for="editBannerUpload" class="form-label">Upload Banner</label>
                                <input type="file" class="form-control" id="editBannerUpload">
                            </div>
                            <div class="mb-3">
                                <label for="editFormattedText" class="form-label">Additional Information</label>
                                <textarea id="editFormattedText" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editBadgeText" class="form-label">Badge Text</label>
                            <input type="text" class="form-control" id="editBadgeText" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBadgeColor" class="form-label">Badge Color</label>
                            <input type="color" class="form-control" id="editBadgeColor" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBadgePosition" class="form-label">Badge Position</label>
                            <select class="form-select" id="editBadgePosition" required>
                                <option value="top-left">Top Left</option>
                                <option value="top-right">Top Right</option>
                                <option value="bottom-left">Bottom Left</option>
                                <option value="bottom-right">Bottom Right</option>
                                <option value="top-full">Top Full</option>
                                <option value="middle-full">Middle Full</option>
                                <option value="bottom-full">Bottom Full</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(productId, bigsales) {
            if (confirm("Are you sure you want to delete this product?")) {
                // Send AJAX request to delete the product
                fetch('deleteSales.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'deleteSales',
                            id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert("Sales deleted successfully!");
                            const [imageFileName, description] = bigsales.split("<>");
                            console.log(imageFileName, description);
                            // Remove the product from the DOM
                           // document.querySelector(`#product-${productId}`).remove();
                            deleteImage(`phpSales/imgSales/${imageFileName}`);
                            location.reload();
                        } else {
                            alert("Error deleting product: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    </script>
    <script>
        let currentId = 0;
        // Toggle visibility of additional fields based on "Big Sale" switch
        document.getElementById('editSaleTypeSwitch').addEventListener('change', function() {
            document.getElementById('editBigSaleFields').style.display = this.checked ? 'block' : 'none';
        });

        // Pre-fill the modal with product data when editing a product
        function editProduct(Id) {
            currentId = Id;
            const getSalesFromIdData = {
                action: 'getSalesfromId',
                id: Id
            };
            console.log(Id)
            fetch('getSales.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(getSalesFromIdData)
                })
                .then(response => response.text()) // Log as text first
                .then(text => {
                    console.log(text); // Check the exact content returned by userView.php
                    return JSON.parse(text);
                })
                .then(data => {
                    //document.getElementById('editSaleTypeSwitch').checked = true; // Replace with actual sale type
                    //document.getElementById('editBigSaleFields').style.display = 'block'; // Show or hide big sale fields based on product data

                    // Show the edit modal
                    if (data.bigsales != 'null' && data.bigsales != '' && data.bigsales != null) {

                        const [imageFileName, description] = data.bigsales.split("<>");
                        document.getElementById('editSaleTypeSwitch').checked = true;
                        document.getElementById('editBigSaleFields').style.display = 'block';
                        document.getElementById('editFormattedText').value = description || '';
                        document.getElementById('editBannerPreview').src = "imgSales/" + imageFileName;
                        console.log(imageFileName);
                    } else {
                        document.getElementById('editSaleTypeSwitch').checked = false;
                        document.getElementById('editBigSaleFields').style.display = 'none';
                        document.getElementById('editFormattedText').value = '';
                        document.getElementById('editBannerPreview').src = '';
                    }
                    document.getElementById('editBadgeText').value = data.name || '';
                    document.getElementById('editBadgeColor').value = data.color || '';
                    document.getElementById('editBadgePosition').value.value = data.pos || '';
                    new bootstrap.Modal(document.getElementById('editProductModal')).show();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }
        let checkUpdateNewImg = false;
        document.getElementById('editBannerUpload').addEventListener('change', function(event) {
            // Kiểm tra nếu có file được chọn
            if (event.target.files && event.target.files[0]) {
                const file = event.target.files[0];

                // In ra thông tin file được chọn
                console.log('File name:', file.name);
                console.log('File size:', file.size, 'bytes');
                console.log('File type:', file.type);
                checkUpdateNewImg = true;
            } else {
                console.log('No file selected');
            }
        });

        function saveEditSale() {
            currentBanner = document.getElementById('editBannerPreview').src;
            var newFileName = currentImgName; // giữ tên file cũ nếu không có file mới

            if (checkUpdateNewImg && currentFileimg != null) {
                // Generate a new filename for the uploaded file
                newFileName = generateFileName(currentFileimg.name);
            }

            console.log(newFileName);

            var editSaleData;
            if (document.getElementById('editSaleTypeSwitch').checked) {
                editSaleData = {
                    action: 'editSale',
                    id: currentId,
                    name: document.getElementById('editBadgeText').value,
                    color: document.getElementById('editBadgeColor').value,
                    pos: document.getElementById('editBadgePosition').value,
                    bigsales: `${newFileName}<>${document.getElementById('editFormattedText').value}`
                };
            } else {
                editSaleData = {
                    action: 'editSale',
                    id: currentId,
                    name: document.getElementById('editBadgeText').value,
                    color: document.getElementById('editBadgeColor').value,
                    pos: document.getElementById('editBadgePosition').value,
                    bigsales: ''
                };
            }

            fetch('salesView.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(editSaleData)
                })
                .then(response => response.text())
                .then(text => {
                    console.log(text);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });

            if (checkUpdateNewImg && currentFileimg != null) {
                const formData = new FormData();
                formData.append('image', new File([currentFileimg], newFileName, {
                    type: currentFileimg.type
                }));

                // Gửi ảnh mới tới PHP script để upload
                fetch('uploadImage.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error uploading image:', error);
                    });

                // Xóa file cũ nếu có
                if (currentBanner) {
                    deleteImage(`phpSales/imgSales/${currentBanner}`);
                }

                checkUpdateNewImg = false; // Reset lại cờ sau khi hoàn thành
            }

            currentImgName = newFileName;
            currentFileimg = null;
        }
    </script>
    <script>
        tinymce.init({
            selector: '#formattedText'
        });

        // Toggle display of big sale fields
        document.getElementById('saleTypeSwitch').addEventListener('change', function() {
            event.preventDefault();
            const bigSaleFields = document.getElementById('bigSaleFields');
            bigSaleFields.style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('addProductForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get form values
            const imageUrl = "https://images.pexels.com/photos/776538/pexels-photo-776538.jpeg?auto=compress&cs=tinysrgb&h=350";
            const badgeText = document.getElementById('badgeText').value;
            const badgeColor = document.getElementById('badgeColor').value;
            const badgePosition = document.getElementById('badgePosition').value;
            const isBigSale = document.getElementById('saleTypeSwitch').checked;

            // Optional fields for big sale
            const bannerUpload = document.getElementById('bannerUpload').files[0];
            const additionalInfo = isBigSale ? tinymce.get('formattedText').getContent() : '';
            var newFileName;
            if (isBigSale) newFileName = generateFileName(bannerUpload.name);

            // Create new product list item
            const newProduct = document.createElement('li');
            newProduct.innerHTML = `
        <figure><img src="${imageUrl}" alt="New Product"></figure>
        <div class="badge-overlay">
            <span class="${badgePosition} badge dynamic-color" style="background-color: ${badgeColor}; cursor: ${isBigSale ? 'pointer' : 'default'};"
                ${isBigSale ? `onclick="
                    document.cookie = 'banner=${newFileName}; max-age=${86400 * 30}; path=/';
                    document.cookie = 'info=${encodeURIComponent(tinymce.get('formattedText').getContent())}; max-age=${86400 * 30}; path=/';
                    window.location.href = 'info.php';
                "` : ''}>
                ${badgeText}
            </span>
        </div>
    `;

            /////////////////////////////

            // Append to product list
            document.getElementById('productList').appendChild(newProduct);

            // // Close modal
            // const addProductModal = document.getElementById('addProductModal');
            // const modalInstance = bootstrap.Modal.getInstance(addProductModal);
            // modalInstance.hide();


            // Clear form and reset TinyMCE editor
            document.getElementById('addProductForm').reset();
            tinymce.get('formattedText').setContent('');
            document.getElementById('bigSaleFields').style.display = 'none'; // Reset big sale fields
            ////////////////////
            const addSale = {
                action: 'addSale',
                name: badgeText,
                pos: badgePosition,
                color: badgeColor,
                bigsales: `${newFileName}<>${additionalInfo}`
            };

            // Send user data to addnewUser.php
            fetch('salesView.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(addSale)
                })
                .then(response => response.text()) // Log as text first
                .then(text => {
                    console.log(text); // Check the exact content returned by userView.php
                    return JSON.parse(text);
                })
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        // Reset the form and avatar preview on success
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            ///////////////////
            if (isBigSale) {
                const formData = new FormData();
                formData.append('image', new File([bannerUpload], newFileName, {
                    type: bannerUpload.type
                }));

                fetch('uploadImage.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Image uploaded successfully');
                        } else {
                            console.error('Image upload failed:', data.message);
                        }
                    })
                    .catch(error => console.error('Error uploading image:', error));
            }
        });

        function generateFileName(originalFileName) {
            // Extract the file extension
            const extension = originalFileName.split('.').pop();
            // Generate a random ID (e.g., a number between 1 and 100000)
            const randomId = Math.floor(Math.random() * 100000);
            // Construct the new filename
            return `sales${randomId}.${extension}`;
        }

        function deleteImage(imgtoDelete) {
            if (imgtoDelete != "") {
                fetch('../deleteimgProduct.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            imagePath: imgtoDelete
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.message); // In thông báo từ PHP
                        alert(data.message); // Hiển thị thông báo cho người dùng
                    })
                    .catch(error => console.error('Lỗi:', error));
                imgtoDelete = "";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='../script.js'></script>
</body>

</html>