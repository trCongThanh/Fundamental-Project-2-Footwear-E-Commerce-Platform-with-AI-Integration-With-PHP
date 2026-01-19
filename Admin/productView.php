<?php
include('database.php'); ?>
<?php
$sql = "SELECT id,name,img FROM products";
$result = mysqli_query($conn, $sql);
$shoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
//print_r($shoes);
?>

<?php
// Định nghĩa biến toàn cục
$currentID = 0;

function changeID($id)
{
    global $currentID; // Sử dụng biến toàn cục
    $currentID = $id; // Chỉnh sửa giá trị
}

function printGlobalVariable() {}
?>

<?php
function getfirstImg($input)
{
    // Convert the input string to an array by splitting at each comma
    $items = explode(',', $input);

    // Initialize an array to store the base image names
    $baseImages = [];

    // Loop through each item and extract the base name
    foreach ($items as $item) {
        // Split each item by '<>' and take the second part
        $parts = explode('<>', $item);
        $filenameWithExtension = $parts[1]; // This is "1.png" or "1_aqua.png"

        // Split by underscore to get the base filename before any suffix
        $filenameBase = explode('_', $filenameWithExtension)[0];

        // Store in the array
        $baseImages[] = $filenameBase;
    }

    // Return the first base image name from the array
    return $baseImages[0]; // Outputs: "1.png"
}
function getfirstColor($input)
{
    // Convert the input string to an array by splitting at each comma
    $items = explode(',', $input);

    // Get the first item and split it by '<>'
    $firstItem = explode('<>', $items[0]);

    // Return the first color
    return $firstItem[0]; // Outputs: "purple"
}
?>
<?php
// Include database connection file
require 'database.php'; // Ensure this file defines $conn for the database connection

// Check if there’s JSON data in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the JSON data from JavaScript
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Access the data fields
    $product_id = $data['id'] ?? 0;
    $product_name = $data['name'] ?? 'Unknown';
    $product_price = $data['price'] ?? 0;
    $product_description = $data['desc'] ?? 'Unknown';
    $product_brands = $data['brands'] ?? 'Unknown';
    $product_gender = $data['gender'] ?? 'Unknown';
    $product_img = $data['img'] ?? 'Unknown';
    // Ensure $conn is still open
    if (!$conn || $conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Corrected SQL Update Query
    $sql = "UPDATE products SET name=?, price=?, `desc`=?, `img`=?, brands=?, gender=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Correct the bind_param call
    $stmt->bind_param("sdssssi", $product_name, $product_price, $product_description, $product_img, $product_brands, $product_gender, $product_id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleimgCutter.css">
    <title>Hello, world!</title>
    <style>
        .product {
            width: 100%;
            max-width: 26rem;
            height: 14rem;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
            border-radius: 2rem;
            position: relative;
            overflow: hidden;
            background-color: #ffffff;
            transition: background-color 0.3s ease;
            margin: 0 auto;
        }

        .product:hover {
            background-color: var(--dynamic-color);
        }

        .product img {
            width: 70%;
            height: auto;
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .product:hover img {
            transform: scale(1.2) rotate(-25deg);
        }

        .circle {
            width: 9em;
            height: 9rem;
            border-radius: 50%;
            background-color: var(--dynamic-color);
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: background-color 0.3s ease;
        }

        .product:hover .circle {
            background-color: white;
        }

        .product h4 {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.9rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 2px black;
            z-index: 2;
            transition: font-size 0.3s ease;
        }

        .product:hover h4 {
            transform: translateY(-3.5rem) translateX(-4.5rem) rotate(-25deg);
            font-size: 1.1rem;
            color: transparent;
            text-shadow: none;
        }

        @media (max-width: 576px) {
            .product h4 {
                padding-bottom: 4rem;
            }

            .product img {
                width: 50%;
                height: auto;
            }
        }
    </style>
    <style>
        .form-group {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            /* Maximum 5 items per row */
            gap: 15px;
            /* Space between items */
            align-items: center;
            /* Align vertically */
        }

        .form-check {
            position: relative;
            margin-bottom: 15px;
            /* Space below each item */
        }

        .form-check-input {
            display: none;
            /* Hide original radio button */
        }

        .form-check-label {
            display: inline-block;
            width: 40px;
            /* Square width */
            height: 40px;
            /* Square height */
            border: 2px solid black;
            /* Square border */
            border-radius: 5px;
            /* Slightly rounded corners */
            text-align: center;
            /* Center content */
            line-height: 40px;
            /* Center number in square */
            cursor: pointer;
            /* Pointer on hover */
            transition: background-color 0.3s, color 0.3s;
            /* Transition effects */
            font-size: 1rem;
            /* Font size */
        }

        .form-check-input:checked+.form-check-label {
            background-color: black;
            /* Background color when selected */
            color: white;
            /* Text color when selected */
        }

        /* Responsive styles for smaller screens */
        @media (max-width: 576px) {
            .form-check-label {
                width: 30px;
                /* Adjust square width for small screens */
                height: 30px;
                /* Adjust square height for small screens */
                line-height: 30px;
                /* Center content */
                font-size: 0.8rem;
                /* Smaller font size */
            }

            .form-group {
                grid-template-columns: repeat(4, 1fr);
                /* 3 items per row on small screens */
            }
        }

        .colorContain {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(1.5rem, 1fr));
            gap: 0.5rem;
            max-width: 10rem;
            /* Constrain width for six items per row on larger screens */
        }

        .color-check {
            position: relative;
        }

        .color-radio {
            display: none;
            /* Hide the original radio button */
        }

        .color-label {
            display: flex;
            align-self: center;
            justify-content: center;
            padding-top: 0.12rem;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.3s;
            border: 2px solid #fff;
        }

        .color-radio:checked+.color-label {
            transform: scale(1.2);
            border: 2px solid #000;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .colorContain {
                max-width: 15rem;
                grid-template-columns: repeat(8, 1fr);
                /* 4 items per row on medium screens */
            }
        }

        @media (max-width: 480px) {
            .colorContain {
                max-width: 20rem;
                grid-template-columns: repeat(6, 1fr);
                /* 3 items per row on small screens */
            }
        }

        #productModal {
            z-index: 1060;
            /* Default Bootstrap z-index for modals */
        }

        .image-container {
            position: relative;
            display: inline-block;
        }


        .remove-button {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ff4d4d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #e60000;
        }
    </style>
    <style>
        /* Modal Styles */
        .brads-modal {
            display: none;
            position: fixed;
            z-index: 1080;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            /* Dark semi-transparent background */
        }

        /* Modal Content */
        .brads-modal-content {
            background-color: #1c1c1c;
            color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.1);
            border: none;
        }

        /* Close Button */
        .brads-close {
            color: #ccc;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .brads-close:hover,
        .brads-close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        /* Grid layout */
        .brads-image-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        /* Image box with background image */
        .brads-image-box {
            position: relative;
            cursor: pointer;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            background-size: cover;
            background-position: center;
            height: 150px;
            /* Adjust height to fit the grid */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Text overlay */
        .brads-image-box span {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            text-shadow: 0px 0px 10px rgba(0, 0, 0, 0.7);
            /* Text shadow for better visibility */
        }

        /* Selection effect */
        .brads-image-box.selected {
            transform: scale(1.05);
            box-shadow: 0px 0px 15px 5px rgba(255, 255, 0, 0.8);
            /* Glowing border effect */
        }

        .brads-image-box:hover {
            transform: scale(1.03);
            /* Slight hover effect */
        }

        /* Button */
        .brads-btn {
            background-color: #fff;
            color: #1c1c1c;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .brads-btn:hover {
            background-color: #ddd;
        }

        .brads-btn:focus {
            outline: none;
        }

        /* Responsive styles */
        @media (min-width: 600px) {
            .brads-modal-content {
                width: 70%;
            }

            #productImage {
                min-height: 168px;
                min-width: 326px;
            }

        }

        @media (min-width: 900px) {
            .brads-modal-content {
                width: 50%;
            }

            #productImage {
                min-height: 240px;
                min-width: 467px;
            }

        }

        a {
            text-decoration: none !important;
        }
    </style>
    <style>
        .slider {
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            /* Adjust as needed */
            margin: auto;
        }

        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }

        .slider-wrapper img {
            width: 100%;
            flex-shrink: 0;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 10px;
            z-index: 10;
        }

        .slider-btn.prev {
            left: 10px;
        }

        .slider-btn.next {
            right: 10px;
        }

        .dots-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .dot {
            height: 12px;
            width: 12px;
            margin: 0 5px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
        }

        .dot.active {
            background-color: #717171;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            position: relative;
        }

        .gallery img {
            width: calc(33.33% - 10px);
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .gallery .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            z-index: 10;
        }

        .add-image {
            margin: 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .add-image-btn {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }


        .view-switch button {
            margin: 10px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .view-switch button.active {
            background-color: #007bff;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="index.php" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="index.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
            <li class="divider" data-text="main">Main</li>
            <li>
                <a href="#"><i class='bx bxs-package icon'></i> Product <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="productView.php">View Product</a></li>
                    <li><a href="addnewProduct.php">Add Product</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class='bx bxs-user-circle icon'></i> User <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="phpUser/userView.php">View User</a></li>
                    <li><a href="phpUser/addnewUser.php">Add User</a></li>
                </ul>
            </li>
            <li class="divider" data-text="Commerce">Commerce</li>
            <li><a href="phpOrders/ordersView.php"><i class='bx bx-cart-alt icon'></i> Orders</a></li>
            <li>
                <a href="#"><i class='bx bxs-purchase-tag-alt icon'></i> Sales <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="phpSales/salesView.php">View Sales</a></li>
                    <li><a href="phpSales/addSalesToProduct.php">Add sales to product</a></li>
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
                    <input style="width: 209px;height: 38px; margin-top:15px" type="text" placeholder="Search... ">
                    <!-- <i style="margin-top:9px;margin-right:190px" class='bx bx-search icon'></i> -->
                </div>
            </form>
            <a href="#" class="nav-link">
                <i class='bx bxs-bell icon'></i>
                <span class="badge">5</span>
            </a>
            <a href="#" class="nav-link">
                <i class='bx bxs-message-square-dots icon'></i>
                <span class="badge">8</span>
            </a>
            <span class="divider"></span>
            <div class="profile">
                <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                <ul class="profile-link">
                    <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                    <li><a href="#"><i class='bx bxs-cog'></i> Settings</a></li>
                    <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
                </ul>
            </div>
        </nav>
        <!-- NAVBAR -->
        <div style="margin-top: 20px;" class="container">
            <div class="row justify-content-start">
                <div class="col-7"></div>
                <div class="col-5">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search product..." aria-label="Search" aria-describedby="basic-addon1">

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Display Products -->
                <div id="productList" class="row align-self-start">
                    <?php foreach ($shoes as $shoe) { ?>
                        <div class="col-md-3 text-center mb-4 product-item" data-name="<?php echo htmlspecialchars($shoe['name']); ?>">
                            <div style="--dynamic-color: <?php echo getfirstColor($shoe['img']); ?>;" class="product">
                                <div class="row align-self-start">
                                    <div style="--dynamic-color: <?php echo getfirstColor($shoe['img']); ?>;" class="circle"></div>
                                    <div class="col-md-12 pt-4">
                                        <img style="min-height: 106px;" src="img/<?php echo getfirstImg($shoe['img']); ?>" alt="Product Shoes">
                                    </div>
                                    <div class="col-md-12 pt-md-3 pb-sm-4">
                                        <h4><?php echo htmlspecialchars($shoe['name']); ?></h4>
                                    </div>
                                    <div class="col-6 pt-md-1 pt-3">
                                        <button data-id="<?php echo $shoe['id']; ?>" class="btn btn-primary w-75" data-toggle="modal" data-target="#productModal"><i class='bx bx-show-alt'></i></button>
                                    </div>
                                    <div class="col-6 pt-md-1 pt-3">
                                        <button id="<?php echo $shoe['id']; ?>" class="btn btn-danger w-75"><i class='bx bxs-trash'></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
            <script>
                document.getElementById('searchInput').addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const products = document.querySelectorAll('#productList .product-item');

                    products.forEach(product => {
                        const name = product.getAttribute('data-name').toLowerCase();
                        if (name.includes(query)) {
                            product.style.display = 'block';
                        } else {
                            product.style.display = 'none';
                        }
                    });
                });
            </script>

            <!-- Modal edit -->
            <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">Comments</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                    <h4 id="productName">Nike Air Zoom GT Cut 3 EP</h4>
                                    <div id="productImageContainer" class="position-relative">
                                        <div class="image-container">

                                            <img src="img/1.png" alt="Product Shoes 1" class="img-fluid mb-3" id="productImage">
                                            <div class="remove-button" style="visibility: hidden;">−</div>
                                        </div>
                                        <i class="fa-solid fa fa-image overlay-icon" style="display: none;"></i>
                                    </div>
                                    <div class="colorContain">
                                        <div class="color-check">
                                            <input class="color-radio" type="radio" name="color" id="purple" value="purple" checked>
                                            <label class="color-label" for="purple" style="background-color: purple;"></label>
                                        </div>
                                        <div class="color-check">
                                            <input class="color-radio" type="radio" name="color" id="aqua" value="aqua">
                                            <label class="color-label" for="aqua" style="background-color: aqua;"></label>
                                        </div>
                                        <div class="color-check addmoreColor" style="visibility: hidden;">
                                            <input class="color-radio" type="radio" name="color" id="addmore" value="addmore" data-toggle="modal" data-target="#cutterModal">
                                            <label class="color-label" for="addmore" style="background-color: gray;"><i class="fa fa-plus"></i></label>
                                        </div>
                                    </div>
                                    <p><strong>More Picture</strong>
                                    <div hidden id='viewSwitchSliderGallery' class="view-switch">
                                        <button class="switch-to-slider active">Slider View</button>
                                        <button class="switch-to-gallery">Gallery View</button>
                                    </div>
                                    <div id="ImageSliderId" class="slider">
                                        <div class="slider-wrapper">
                                            <!-- Images for slider -->
                                        </div>
                                        <button class="slider-btn prev">❮</button>
                                        <button class="slider-btn next">❯</button>
                                    </div>
                                    <div class="gallery" style="display: none;">
                                        <!-- Gallery images with delete option will be dynamically added -->
                                    </div>
                                    <div class="add-image">
                                        <button hidden class="add-image-btn">Add Image</button>
                                        <input type="file" accept="image/*" style="display: none;" class="image-input">
                                    </div>

                                    <div id="sliderDotsId" class="dots-wrapper"></div>
                                    </p>
                                    <p><strong>Price:</strong> <span id="productPrice">$120.00</span></p>
                                    <p><strong>Brand:</strong> <span id="productBrand">NIKE</span></p>
                                    <p><strong>Gender:</strong> <span id="productGender">Male</span></p>
                                    <p><strong>Description:</strong> <span id="productDescription">A lightweight shoe designed for speed and agility.</span></p>
                                    <div class="sizeOptions">
                                        <p><strong>Sizes Available:</strong></p>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="size" id="size1" value="7" checked>
                                                <label class="form-check-label" for="size1">7</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="size" id="size2" value="8">
                                                <label class="form-check-label" for="size2">8</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="size" id="size3" value="9">
                                                <label class="form-check-label" for="size3">9</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="size" id="size4" value="10">
                                                <label class="form-check-label" for="size4">10</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="size" id="size5" value="11">
                                                <label class="form-check-label" for="size5">11</label>
                                            </div>
                                            <!-- Additional sizes... -->
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                                    <h5>User Comments</h5>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item">Great shoes! Very comfortable.</li>
                                        <li class="list-group-item">I love the design!</li>
                                        <li class="list-group-item">Would recommend to my friends.</li>
                                    </ul>
                                    <textarea class="form-control" rows="3" placeholder="Add a comment..."></textarea>
                                    <button class="btn btn-primary mt-2">Submit Comment</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="closeButtonEdit" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="editButton" class="btn btn-primary">Edit</button>
                            <script>
                                const closeButtonEdit = document.getElementById('closeButtonEdit');
                                closeButtonEdit.addEventListener('click', function() {
                                    const productImage = document.getElementById('productImage');
                                    const removeColor = document.querySelector('.remove-button');
                                    if (productImage.classList.contains('hover-effect')) productImage.classList.remove('hover-effect');
                                    if (removeColor.style.visibility === 'visible') removeColor.style.visibility = 'hidden';

                                    /////////////////////////////////////reset từ slider và gallery
                                    const gallery = document.querySelector('.gallery');
                                    const switchToSlider = document.querySelector('.switch-to-slider');
                                    const switchToGallery = document.querySelector('.switch-to-gallery');
                                    const addImageBtn = document.querySelector('.add-image-btn');
                                    const imageInput = document.querySelector('.image-input');
                                    const viewSwitchSliderGallery = document.getElementById('viewSwitchSliderGallery');
                                    switchToSlider.classList.add("active");
                                    switchToGallery.classList.remove("active");
                                    document.querySelector('.slider').style.display = "block";
                                    gallery.style.display = "none";
                                    document.querySelector('.dots-wrapper').style.display = "flex";
                                    addImageBtn.hidden = true;
                                    viewSwitchSliderGallery.hidden = true;
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Define folder and load images

                let currentID = 0;
                let currentBrands = "";
                let currentImg = "";
                let imgtoDelete = "";
                let selectedBrand = "";
                document.addEventListener("DOMContentLoaded", function() {
                    const viewButtons = document.querySelectorAll('button[data-target="#productModal"]');

                    viewButtons.forEach(button => {
                        button.addEventListener("click", function() {
                            const productId = this.getAttribute("data-id");
                            currentID = productId;
                            // Fetch product data using the product ID
                            fetch(`getProduct.php?id=${productId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Log the data in the console
                                        console.log(data);

                                        // Populate modal fields
                                        document.getElementById("productName").textContent = data.name;
                                        document.getElementById("productPrice").textContent = `$${data.price}`;
                                        document.getElementById("productBrand").textContent = data.brands;
                                        document.getElementById("productGender").textContent = data.gender;
                                        document.getElementById("productDescription").textContent = data.desc;
                                        // Clear previous color options
                                        const colorContainer = document.querySelector(".colorContain");
                                        colorContainer.innerHTML = "";

                                        // Split the color and image pairs
                                        currentImg = data.img; //lấy img để toàn cục
                                        const colorImagePairs = data.img.split(',');
                                        colorImagePairs.forEach((pair, index) => {
                                            let is_it_first_Loop = false;
                                            const [color, imgFile] = pair.split('<>');

                                            // Create color-check elements
                                            const colorCheckDiv = document.createElement("div");
                                            colorCheckDiv.classList.add("color-check");

                                            const colorRadio = document.createElement("input");
                                            colorRadio.classList.add("color-radio");
                                            colorRadio.type = "radio";
                                            colorRadio.name = "color";
                                            colorRadio.id = color;
                                            colorRadio.value = color;
                                            colorRadio.checked = index === 0; // Check the first color by default

                                            // Event listener to change image based on selected color
                                            colorRadio.addEventListener("change", function() {
                                                document.getElementById("productImage").src = `img/${imgFile}`;
                                                morePicture(imgFile.split('.')[0]);
                                            });
                                            if (index === 0) is_it_first_Loop = true;
                                            if (is_it_first_Loop) {
                                                morePicture(imgFile.split('.')[0]);
                                            }
                                            const colorLabel = document.createElement("label");
                                            colorLabel.classList.add("color-label");
                                            colorLabel.htmlFor = color;
                                            colorLabel.style.backgroundColor = color;

                                            colorCheckDiv.appendChild(colorRadio);
                                            colorCheckDiv.appendChild(colorLabel);
                                            colorContainer.appendChild(colorCheckDiv);

                                            // Set initial image for the first color
                                            if (index === 0) {
                                                document.getElementById("productImage").src = `img/${imgFile}`;
                                            }
                                        });

                                        // Append the "Add more color" button at the end
                                        const addMoreColorDiv = document.createElement("div");
                                        addMoreColorDiv.classList.add("color-check", "addmoreColor");
                                        addMoreColorDiv.style.visibility = "hidden";

                                        const addMoreRadio = document.createElement("input");
                                        addMoreRadio.classList.add("color-radio");
                                        addMoreRadio.type = "radio";
                                        addMoreRadio.name = "color";
                                        addMoreRadio.id = "addmore";
                                        addMoreRadio.value = "addmore";
                                        addMoreRadio.setAttribute("data-toggle", "modal");
                                        addMoreRadio.setAttribute("data-target", "#cutterModal");


                                        const addMoreLabel = document.createElement("label");
                                        addMoreLabel.classList.add("color-label");
                                        addMoreLabel.htmlFor = "addmore";
                                        addMoreLabel.style.backgroundColor = "gray";
                                        addMoreLabel.innerHTML = "<i class='fa fa-plus'></i>";

                                        addMoreColorDiv.appendChild(addMoreRadio);
                                        addMoreColorDiv.appendChild(addMoreLabel);
                                        colorContainer.appendChild(addMoreColorDiv);

                                    } else {
                                        console.error("Product data not found");
                                    }
                                })
                                .catch(error => console.error("Error fetching product data:", error));
                        });
                    });
                });
            </script>
            <!--script slider -->
            <script>
                function morePicture(folderPath) {
                    const sliderWrapper = document.querySelector('.slider-wrapper');
                    const gallery = document.querySelector('.gallery');
                    const dotsWrapper = document.querySelector('.dots-wrapper');
                    const switchToSlider = document.querySelector('.switch-to-slider');
                    const switchToGallery = document.querySelector('.switch-to-gallery');
                    const addImageBtn = document.querySelector('.add-image-btn');
                    const imageInput = document.querySelector('.image-input');
                    let slideIndex = 0;
                    console.log(folderPath);
                    // Fetch images
                    let images = [];
                    fetch(`phpFile/listImages.php?folder=${folderPath}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error("Error:", data.error);
                                document.querySelector('#ImageSliderId').hidden = true;
                                document.querySelector('#sliderDotsId').hidden = true;
                                return;
                            }
                            images = data; // Populate the images array
                            console.log(images);
                            if(images.length > 0) {
                                console.log(images.length);
                                document.querySelector('#ImageSliderId').hidden = false;
                                document.querySelector('#sliderDotsId').hidden = false;
                            }
                            else {
                                console.log(images.length);
                                document.querySelector('#ImageSliderId').hidden = true;
                                document.querySelector('#sliderDotsId').hidden = true;
                            }
                            gallery.innerHTML = "";
                            populateSlider();
                            populateGallery();
                        })
                        .catch(error => console.error("Error loading images:", error));

                    // Populate slider
                    function populateSlider() {
                        sliderWrapper.innerHTML = "";
                        dotsWrapper.innerHTML = "";

                        images.forEach((image, index) => {
                            addImageToSlider(image, index);
                        });

                        document.querySelector(".prev").addEventListener("click", () => navigateSlide(-1));
                        document.querySelector(".next").addEventListener("click", () => navigateSlide(1));
                        updateSlider();
                    }

                    function addImageToSlider(image, index) {
                        const img = document.createElement("img");
                        img.src = `img/${folderPath}/${image}`;
                        img.alt = `Slide ${index + 1}`;
                        sliderWrapper.appendChild(img);

                        // Create dots
                        const dot = document.createElement("div");
                        dot.classList.add("dot");
                        if (index === 0) dot.classList.add("active");
                        dot.dataset.index = index;
                        dotsWrapper.appendChild(dot);

                        dot.addEventListener("click", () => goToSlide(index));
                    }

                    // Populate gallery
                    function populateGallery() {
                        gallery.innerHTML = "";

                        images.forEach(image => {
                            addImageToGallery(image);
                        });
                    }

                    function addImageToGallery(image) {
                        
                        const wrapper = document.createElement("div");
                        wrapper.classList.add("gallery-item");
                        wrapper.style.position = "relative";

                        const img = document.createElement("img");
                        img.src = `img/${folderPath}/${image}`;
                        img.alt = "Gallery Image";

                        const deleteBtn = document.createElement("button");
                        deleteBtn.classList.add("delete-btn");
                        deleteBtn.textContent = "−";
                        deleteBtn.addEventListener("click", () => {
                            removeImage(image);
                            wrapper.remove();
                        });

                        wrapper.appendChild(img);
                        wrapper.appendChild(deleteBtn);
                        gallery.appendChild(wrapper);
                    }

                    function removeImage(image) {
                        const index = images.indexOf(image);
                        if (index > -1) {
                            images.splice(index, 1);
                            fetch('deleteimgProduct.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    imagePath: 'img/' + folderPath + '/' + image
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data.message); // In thông báo từ PHP
                                alert(data.message); // Hiển thị thông báo cho người dùng
                            })
                            .catch(error => console.error('Lỗi:', error));
                            populateSlider();
                        }     
                    }
                    
                    // Add image from file input
                    addImageBtn.addEventListener("click", () => {
                        imageInput.click();
                    });

                    imageInput.addEventListener("change", (event) => {
                        const file = event.target.files[0];
                        if (file) {
                            const imageName = `new-${Date.now()}.jpg`; // Generate a unique name
                            const formData = new FormData();
                            const dynamicPath = folderPath; // Dynamic path for upload

                            // Append file, dynamic path, and custom image name to FormData
                            formData.append("image", file);
                            formData.append("dynamicPath", dynamicPath);
                            formData.append("imageName", imageName);

                            fetch("phpFile/addMoreIMG.php", {
                                    method: "POST",
                                    body: formData,
                                })
                                .then((response) => response.json())
                                .then((data) => {
                                    if (data.success) {
                                        console.log(data.message);
                                        images.push(imageName); // Add to images array
                                        addImageToSlider(imageName, images.length - 1);
                                        addImageToGallery(imageName);
                                    } else {
                                        console.error(data.message);
                                    }
                                })
                                .catch((error) => console.error("Error:", error));
                        }
                    });


                    // Slider navigation
                    function navigateSlide(direction) {
                        slideIndex = (slideIndex + direction + images.length) % images.length;
                        updateSlider();
                    }

                    function goToSlide(index) {
                        slideIndex = index;
                        updateSlider();
                    }

                    function updateSlider() {
                        const slideWidth = sliderWrapper.firstChild.offsetWidth;
                        sliderWrapper.style.transform = `translateX(-${slideWidth * slideIndex}px)`;

                        document.querySelectorAll('.dot').forEach(dot => dot.classList.remove('active'));
                        dotsWrapper.children[slideIndex].classList.add('active');
                    }

                    // Switch between views
                    switchToSlider.addEventListener("click", () => {
                        switchToSlider.classList.add("active");
                        switchToGallery.classList.remove("active");
                        document.querySelector('.slider').style.display = "block";
                        gallery.style.display = "none";
                        document.querySelector('.dots-wrapper').style.display = "flex";
                        addImageBtn.hidden = true;
                        
                    });

                    switchToGallery.addEventListener("click", () => {
                        switchToGallery.classList.add("active");
                        switchToSlider.classList.remove("active");
                        document.querySelector('.slider').style.display = "none";
                        gallery.style.display = "flex";
                        document.querySelector('.dots-wrapper').style.display = "none";
                        addImageBtn.hidden = false;
                    });

                }
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const colorContainer = document.querySelector(".colorContain");
                    const removeButton = document.querySelector(".remove-button");

                    // Remove checked color, auto-check the first color, and trigger change event
                    removeButton.addEventListener("click", function() {
                        const checkedRadio = colorContainer.querySelector(".color-radio:checked");
                        const colorRadios = colorContainer.querySelectorAll(".color-radio");
                        console.log(checkedRadio.value);
                        // Ví dụ sử dụng
                        imgtoDelete = 'img/' + getImageFile(currentImg, checkedRadio.value);

                        currentImg = removeColor(currentImg, checkedRadio.value);

                        console.log(imgtoDelete);
                        if (colorRadios.length > 2) {
                            if (checkedRadio) {
                                checkedRadio.closest(".color-check").remove();

                                // After removal, auto-check the first remaining radio button and trigger change event
                                const remainingRadios = colorContainer.querySelectorAll(".color-radio");
                                if (remainingRadios.length > 0) {
                                    remainingRadios[0].checked = true;
                                    remainingRadios[0].dispatchEvent(new Event('change', {
                                        bubbles: true
                                    }));
                                }

                                // Hide remove button if only one color is left
                                if (remainingRadios.length === 1) {
                                    removeButton.style.visibility = "hidden";
                                }
                            }
                        } else {
                            alert("Lưu ý là có ít nhất 1 color cho sản phẩm !!!");
                        }
                    });
                });

                function removeColor(s, color) {
                    // Chuyển chuỗi `s` thành mảng các mục dựa trên dấu phẩy
                    let items = s.split(',');

                    // Lọc bỏ các mục có chứa màu cần loại bỏ
                    items = items.filter(item => !item.startsWith(color + '<>'));

                    // Ghép lại các mục còn lại thành chuỗi
                    return items.join(',');
                }

                function getImageFile(s, color) {
                    // Tách chuỗi `s` thành mảng các mục dựa trên dấu phẩy
                    let items = s.split(',');

                    // Tìm mục có chứa màu cần lấy
                    let item = items.find(item => item.startsWith(color + '<>'));

                    // Nếu tìm thấy, tách và trả về tên file
                    return item ? item.split('<>')[1] : null;
                }
            </script>

            <!-- JavaScript to Handle Edit Functionality -->
            <script>
                function removeDuplicates(s) {
                    // Chuyển chuỗi thành mảng các mục dựa trên dấu phẩy
                    let items = s.split(',');

                    // Sử dụng Set để loại bỏ trùng lặp
                    let uniqueItems = [...new Set(items)];

                    // Ghép lại thành chuỗi
                    return uniqueItems.join(',');
                }

                function deleteImage() {
                    console.log(imgtoDelete);
                    if (imgtoDelete != "") {
                        fetch('deleteimgProduct.php', {
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
                document.getElementById('editButton').addEventListener('click', function() {
                    const editButton = this;
                    const viewSwitchSliderGallery = document.getElementById('viewSwitchSliderGallery');
                    const productImage = document.getElementById('productImage');
                    const overlayIcon = document.querySelector('.overlay-icon');
                    const colorContain = document.querySelector('.addmoreColor');
                    const productName = document.getElementById('productName');
                    const productPrice = document.getElementById('productPrice');
                    const productBrand = document.getElementById('productBrand'); //đổi thành button <button class="nav-item" onclick="bradsOpenModal()">BRANDS</button> và nhấn save thì đổi thành radio button đã chọn 
                    const productGender = document.getElementById('productGender');
                    const productDescription = document.getElementById('productDescription');
                    const sizeOptions = document.querySelector('.sizeOptions');
                    const removeColor = document.querySelector('.remove-button');

                    if (editButton.innerText === 'Edit') {
                        // Switch to Edit Mode
                        editButton.innerText = 'Save';

                        viewSwitchSliderGallery.hidden = false;

                        selectedBrand = productBrand.innerHTML;
                        // Enable hover effect on image
                        productImage.classList.add('hover-effect');
                        overlayIcon.style.display = 'block';

                        // Show add more color option
                        colorContain.style.visibility = 'visible';

                        removeColor.style.visibility = 'visible';
                        // Make product details editable
                        const nameField = `<input type="text" id="editProductName" class="form-control" value="${productName.innerText}">`;
                        productName.innerHTML = nameField;

                        const priceField = `<input type="text" id="editProductPrice" class="form-control" value="${productPrice.innerText.replace('$', '')}">`;
                        productPrice.innerHTML = priceField;

                        const descriptionField = `<textarea style="min-height: 12rem" id="editProductDescription" class="form-control">${productDescription.innerText}</textarea>`;
                        productDescription.innerHTML = descriptionField;

                        // Hide size options
                        sizeOptions.style.visibility = 'hidden';
                        currentBrands = productBrand.innerHTML;
                        console.log(currentBrands);
                        // Show brand button to open selection modal
                        productBrand.innerHTML = `<button id="selectedBrandButton" class="nav-item" style="background-color:black; color:white;border-radius: 5px;" onclick="bradsOpenModal()">BRANDS</button>`;

                        // Convert gender to a horizontal dropdown menu
                        const currentGender = productGender.innerText.trim();
                        productGender.innerHTML = `
    <div class="horizontal-dropdown">
        <select id="editProductGender" class="form-control">
            <option value="Male" ${currentGender === "Male" ? "selected" : ""}>Male</option>
            <option value="Female" ${currentGender === "Female" ? "selected" : ""}>Female</option>
            <option value="Unisex" ${currentGender === "Unisex" ? "selected" : ""}>Unisex</option>
        </select>
    </div>`;
                        document.querySelector('.horizontal-dropdown select').style.display = 'inline-block';
                        document.querySelector('.horizontal-dropdown').style.display = 'flex';
                        document.querySelector('.horizontal-dropdown').style.flexDirection = 'row';
                    } else {
                        // Switch to View Mode
                        editButton.innerText = 'Edit';

                        const gallery = document.querySelector('.gallery');
                        const switchToSlider = document.querySelector('.switch-to-slider');
                        const switchToGallery = document.querySelector('.switch-to-gallery');
                        const addImageBtn = document.querySelector('.add-image-btn');
                        const imageInput = document.querySelector('.image-input');
                        const viewSwitchSliderGallery = document.getElementById('viewSwitchSliderGallery');
                        switchToSlider.classList.add("active");
                        switchToGallery.classList.remove("active");
                        document.querySelector('.slider').style.display = "block";
                        gallery.style.display = "none";
                        document.querySelector('.dots-wrapper').style.display = "flex";
                        addImageBtn.hidden = true;
                        viewSwitchSliderGallery.hidden = true;

                        // Remove hover effect on image
                        productImage.classList.remove('hover-effect');
                        overlayIcon.style.display = 'none';

                        // Hide add more color option
                        colorContain.style.visibility = 'hidden';


                        // Save product details and revert to static view
                        const editedName = document.getElementById('editProductName').value;
                        const editedPrice = document.getElementById('editProductPrice').value;
                        const editedDescription = document.getElementById('editProductDescription').value;
                        const editedGender = document.getElementById('editProductGender').value;

                        productName.innerHTML = editedName;
                        productPrice.innerHTML = `$${editedPrice}`;
                        productDescription.innerHTML = editedDescription;
                        productGender.innerText = editedGender;

                        // Show size options
                        sizeOptions.style.visibility = 'visible';
                        removeColor.style.visibility = 'hidden';

                        // Update the brand section to show selected brand as a radio button
                        productBrand.innerHTML = ` ${selectedBrand}`;
                        console.log("new color add to " + new_color_add_to_current_Product);
                        currentImg = currentImg + ',' + new_color_add_to_current_Product;

                        currentImg = currentImg
                            .split(',')
                            .filter(item => item !== 'undefined' && item !== '')
                            .join(',');
                        currentImg = removeDuplicates(currentImg);
                        var updateShoe = {
                            id: currentID, // là hàm let đã được khai báo từ trước
                            name: editedName,
                            price: editedPrice,
                            brands: currentBrands,
                            desc: editedDescription,
                            gender: editedGender,
                            img: currentImg
                        };

                        // Use fetch to send data to PHP
                        fetch('productView.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(updateShoe)
                            })
                            .then(response => response.text())
                            .then(updateShoe => {
                                console.log('Response from PHP:', updateShoe);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        deleteImage();
                        // Add integer restriction for price input
                        document.getElementById('editProductPrice').addEventListener('input', function() {
                            this.value = this.value.replace(/[^0-9]/g, '');
                        });
                    }
                });
            </script>
            <style>
                .hover-effect {
                    opacity: 0.5;
                }

                .overlay-icon {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 2rem;
                    color: #333;
                }
            </style>

            <div class="image-cutter-app">
                <!-- Modal for Image Cutter -->
                <div class="modal fade" id="cutterModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="cutterModalLabel"
                    aria-hidden="true" style="z-index:1000000">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cutterModalLabel">Align and Crop Image</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="colorPicker">
                                    <h2>Select a Color</h2>
                                    <input type="color" id="colorPicker" value="#ff0000">
                                </div>
                                <input type="file" id="imageUpload" accept="image/*" class="mb-3">
                                <div id="canvasWrapper">
                                    <img src="img/1.png" id="originImage" alt="Origin Image">
                                    <img id="uploadedImage" alt="Uploaded Image" draggable="false" style="display: none;">
                                </div>
                                <div class="controls">
                                    <button id="cropButton" class="btn btn-primary mt-3">Upload new color</button>
                                    <div class="move-buttons mt-2">
                                        <button id="moveLeft" class="btn btn-secondary">Left</button>
                                        <button id="moveRight" class="btn btn-secondary">Right</button>
                                        <button id="moveUp" class="btn btn-secondary">Up</button>
                                        <button id="moveDown" class="btn btn-secondary">Down</button>

                                    </div>
                                    <div class="resize-buttons mt-2">
                                        <button id="increaseWidth" class="btn btn-secondary">Widen</button>
                                        <button id="decreaseWidth" class="btn btn-secondary">Narrow</button>
                                        <button id="increaseHeight" class="btn btn-secondary">Heighten</button>
                                        <button id="decreaseHeight" class="btn btn-secondary">Shorten</button>
                                    </div>
                                    <div class="flip-buttons mt-2">
                                        <button id="FlipX" class="btn btn-secondary">Flip X</button>
                                        <button id="FlipY" class="btn btn-secondary">Flip Y</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <canvas id="canvas" style="display: none;"></canvas>
            <!-- Modal Brands -->
            <div id="bradsModal" class="brads-modal">
                <div class="brads-modal-content">
                    <span class="brads-close" onclick="bradsCloseModal()">&times;</span>
                    <h2>Select Brands</h2>
                    <div class="brads-image-grid">
                        <div class="brads-image-box" style="background-image: url('https://gigamall.com.vn/data/2019/09/05/15023424_LOGO-NIKE-500x500.jpg');" onclick="bradsToggleSelection(this)">
                            <span>Nike</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://static.vecteezy.com/system/resources/previews/010/994/332/non_2x/puma-logo-black-symbol-clothes-design-icon-abstract-football-illustration-with-red-background-free-vector.jpg');" onclick="bradsToggleSelection(this)">
                            <span>Puma</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://i.pinimg.com/736x/70/fb/bb/70fbbbb1d15922445e981ddf29c4a1d7.jpg');" onclick="bradsToggleSelection(this)">
                            <span>Adidas</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://images2.minutemediacdn.com/image/upload/c_fill,w_1200,ar_16:9,f_auto,q_auto,g_auto/images/voltaxMediaLibrary/mmsport/mentalfloss/01gv19d1fnt9c0sftbc5.jpg');" onclick="bradsToggleSelection(this)">
                            <span>MLB</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdTqDdTumZAmB0bbS7igFxHaYwsZMWB7wFPA&s');" onclick="bradsToggleSelection(this)">
                            <span>MIRA</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrvDGf4eEnnQjb9-eYQbGBIo3gvnzUvj_1EHyyJ5jWXPs_iDZ24a32Df8RC7luHm7-lCs&usqp=CAU');" onclick="bradsToggleSelection(this)">
                            <span>New Balance</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://down-ph.img.susercontent.com/file/cn-11134216-7r98o-lv5qjw651sjxa6');" onclick="bradsToggleSelection(this)">
                            <span>Ceymme</span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFuoWR3p_nr6bgBnXgrqfKVVK8aYo743BSPw&s');" onclick="bradsToggleSelection(this)">
                            <span>Converse </span>
                        </div>
                        <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQx3OzRnHuVR4vzeVEiyszdt9BhHiAMwyKkZw&s');" onclick="bradsToggleSelection(this)">
                            <span>Vans</span>
                        </div>
                    </div>
                    <button class="brads-btn" onclick="bradsSubmitSelection()">OK</button>
                </div>
            </div>


            <script>
                function bradsOpenModal() {
                    document.getElementById('bradsModal').style.display = 'block';
                }

                function bradsCloseModal() {
                    document.getElementById('bradsModal').style.display = 'none';
                }

                function bradsToggleSelection(element) {
                    // Remove 'selected' class from all brand elements
                    document.querySelectorAll('.brads-image-box').forEach(box => {
                        box.classList.remove('selected');
                    });

                    // Add 'selected' class to the clicked element
                    element.classList.add('selected');
                }

                function bradsSubmitSelection() {
                    const selectedImages = document.querySelectorAll('.brads-image-box.selected');
                    if (selectedImages.length > 0) {
                        selectedBrand = selectedImages[0].querySelector('span').innerText;
                        console.log(selectedBrand);

                        // Update button text to the selected brand name
                        document.querySelector('#selectedBrandButton').innerText = selectedBrand;
                        currentBrands = selectedBrand;
                        alert('You selected: ' + selectedBrand);
                        bradsCloseModal();
                    } else {
                        alert('Please select at least one brand.');
                    }
                }
            </script>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="scriptimgCutter.js?v=<?php echo time(); ?>"></script>
            <!--  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
<script>
    const productImage = document.getElementById('productImage');

    document.querySelectorAll('input[name="color"]').forEach((input) => {
        input.addEventListener('change', () => {
            // Get the selected value
            const selectedColor = input.value;

            // Change the image source based on the selected color
            if (selectedColor === 'purple') {
                productImage.src = 'img/1.png'; // Path to the purple image
            } else if (selectedColor === 'aqua') {
                productImage.src = 'img/1_aqua.png'; // Path to the aqua image
            } else {
                productImage.src = 'img/emptyShoes.png'; // Path to the aqua image
            }
        });
    });
    //fix bug slidebar màn hình chính
    $('#cutterModal').on('hidden.bs.modal', function() {
        // Check if the productModal is still open
        if ($('#productModal').hasClass('show')) {
            // Add 'modal-open' class back to body to maintain scrollbar behavior
            $('body').addClass('modal-open');
        }
    });
    /////////////////////////////////////////
</script>
<script>
    const colorPicker = document.getElementById('colorPicker');
    const colorDisplay = document.getElementById('colorDisplay');

    // Set initial color display
    colorDisplay.style.backgroundColor = colorPicker.value;

    // Event listener for color change
    colorPicker.addEventListener('input', function() {
        colorDisplay.style.backgroundColor = colorPicker.value;
    });
</script>
<script>
    //chức năng delete product
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-danger").forEach(button => {
            button.addEventListener("click", function() {
                const productId = button.id; // Get the product ID
                console.log(productId);
                //thêm xóa ảnh để tối ưu bộ nhớ 
                if (confirm("Are you sure you want to delete this product?")) {
                    fetch("deleteProduct.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "id=" + encodeURIComponent(productId)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                alert("Product deleted successfully.");
                                this.closest(".col-md-3").remove(); // Remove the product from the DOM
                            } else {
                                alert("Error deleting product: " + data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    });
</script>
<script src="script.js"></script>

</html>