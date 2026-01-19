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

$host = "localhost";
$username = "root";
$password = "";
$database = "shopbangiay";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
}

$selectedBrands = isset($_GET['brands']) ? $_GET['brands'] : [];
$priceRange = isset($_GET['price']) ? $_GET['price'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Tạo query với điều kiện lọc
$sql = "
    SELECT products.*, sales.name AS salesName , sales.color,sales.pos,sales.bigsales
    FROM products
    LEFT JOIN sales ON products.idSale = sales.id
    WHERE products.name LIKE '%$keyword%'";

if (!empty($selectedBrands)) {
    $brandsFilter = implode("','", $selectedBrands);
    $sql .= " AND products.brands IN ('$brandsFilter')";
}

if ($priceRange) {
    switch ($priceRange) {
        case '≤ 500,000 VND':
            $sql .= " AND products.price <= 500000";
            break;
        case '≤ 1,000,000 VND':
            $sql .= " AND products.price <= 1000000";
            break;
        case '≤ 5,000,000 VND':
            $sql .= " AND products.price <= 5000000";
            break;
        case '> 5,000,000 VND':
            $sql .= " AND products.price > 5000000";
            break;
    }
}

$result = mysqli_query($conn, $sql);
$shoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_close($conn);
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


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Topic Listing Bootstrap 5 Template</title>

    <!-- CSS FILES -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/main.css" rel="stylesheet">

    <link href="css/products.css" rel="stylesheet">

    <link href="css/brandModal.css" rel="stylesheet">

    <link href="css/sales.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <main id="top">

        <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow">
            <div class="container">
                <!-- Hamburger Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Logo Centered -->
                <a class="navbar-brand mx-auto" onclick="window.location.href='../Shoes/homepage/homepage.php'">ShoeStore</a>

                <!-- Navbar Links and Buttons -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Left Links -->
                    <ul class="navbar-nav me-auto flex-column flex-lg-row align-items-lg-center gap-5">
                        <li class="nav-item active"><a class="nav-link active" onclick="window.location.href='../Shoes/homepage/homepage.php?gender=male'">Male</a></li>
                        <li class="nav-item active"><a class="nav-link active" onclick="window.location.href='../Shoes/homepage/homepage.php?gender=female'">Female</a></li>
                        <li class="nav-item active"><a class="nav-link active" onclick="window.location.href='../Shoes/homepage/homepage.php?gender=unisex'">Unisex</a></li>
                    </ul>

                    <!-- Right Buttons <i class="bi bi-search"></i> -->
                    <div class="d-flex mt-3 mt-lg-0 justify-content-lg-end gap-5">
                        <button class="btn btn-outline-secondary me-2 nav-btn" onclick="window.location.href='search.php?keyword=&price='">
                            <i class="bi bi-search"></i> Search
                        </button>

                        <button class="btn btn-outline-success me-2 nav-btn" id="cartButton">
                            <i class="bi bi-cart"></i> Cart
                        </button>
                        <script>
                            document.getElementById("cartButton").addEventListener("click", function() {
                                const currentUserId = <?php echo $idUser; ?>;
                                window.location.href = `../Shoes/cart/cart.php?user_id=${currentUserId}`;
                            });
                        </script>
                        <button class="btn btn-outline-primary nav-btn" onclick="window.location.href='../Shoes/profile/profile.php'">
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

        <section style="background-image: linear-gradient(15deg, #1a1a1a 0%, #444444 100%);" class="hero-section d-flex justify-content-center align-items-center" id="section_1">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-12 mx-auto">
                        <h1 class="text-white text-center">Find Your Styles</h1>
                        <h6 style="color: #ffffff;" class="text-center">Platform for finding the best shoes</h6>
                        <form method="get" class="custom-form mt-4 pt-2 mb-lg-0 mb-5" role="search">
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text bi-search" id="basic-addon1"></span>
                                <input name="keyword" type="search" class="form-control" id="keyword" placeholder="Search by product name..." aria-label="Search">
                                <button type="submit" class="form-control">Search</button>
                            </div>
                            <!-- Price Range Dropdown -->
                            <div hidden class="mb-3">
                                <label for="priceRange" class="form-label text-white">Price Range</label>
                                <select name="price" id="priceRange" class="form-select">
                                    <option value="">Select Price Range</option>
                                    <option value="All Price">All Price</option>
                                    <option value="≤ 500,000 VND">≤ 500,000 VND</option>
                                    <option value="≤ 1,000,000 VND">≤ 1,000,000 VND</option>
                                    <option value="≤ 5,000,000 VND">≤ 5,000,000 VND</option>
                                    <option value="> 5,000,000 VND">> 5,000,000 VND</option>
                                </select>
                            </div>

                            <!-- Brands Checkboxes -->
                            <div hidden class="mb-3">
                                <label class="form-label text-white">Brands</label><br>
                                <input type="checkbox" name="brands[]" value="Nike" id="nike" class="form-check-input">
                                <label for="nike" class="form-check-label">Nike</label><br>

                                <input type="checkbox" name="brands[]" value="Adidas" id="adidas" class="form-check-input">
                                <label for="adidas" class="form-check-label">Adidas</label><br>

                                <input type="checkbox" name="brands[]" value="Puma" id="puma" class="form-check-input">
                                <label for="puma" class="form-check-label">Puma</label><br>

                                <input type="checkbox" name="brands[]" value="MLB" id="mlb" class="form-check-input">
                                <label for="mlb" class="form-check-label">MLB</label><br>

                                <input type="checkbox" name="brands[]" value="MIRA" id="mira" class="form-check-input">
                                <label for="mira" class="form-check-label">MIRA</label><br>

                                <input type="checkbox" name="brands[]" value="New Balance" id="new-balance" class="form-check-input">
                                <label for="new-balance" class="form-check-label">New Balance</label><br>

                                <input type="checkbox" name="brands[]" value="Ceymme" id="ceymme" class="form-check-input">
                                <label for="ceymme" class="form-check-label">Ceymme</label><br>

                                <input type="checkbox" name="brands[]" value="Converse" id="converse" class="form-check-input">
                                <label for="converse" class="form-check-label">Converse</label><br>

                                <input type="checkbox" name="brands[]" value="Vans" id="vans" class="form-check-input">
                                <label for="vans" class="form-check-label">Vans</label><br>
                                <!-- Add more brands as needed -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>


        <!-- Featured Section -->
        <section class="featured-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                        <div class="custom-block bg-white shadow-lg" id="affordable-pricing" onclick="showPricingOptions()">
                            <div class="d-flex" id="pricing-content">
                                <div>
                                    <h5 class="mb-2">Affordable Pricing</h5>
                                    <p class="mb-0">Choose a pricing option that fits your budget. We offer a variety of choices to meet your needs, from free to premium. Explore our options to find the best fit for you!</p>
                                </div>
                                <span class="badge bg-design rounded-pill ms-auto">$</span>
                            </div>
                            <img src="images/topics/undraw_Remote_design_team_re_urdx.png" class="custom-block-image img-fluid" alt="">
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="custom-block custom-block-overlay">
                            <div class="d-flex flex-column h-100">
                                <img id="brandsImage" src="images/businesswoman-using-tablet-analysis.jpg" class="custom-block-image img-fluid" alt="">
                                <div class="custom-block-overlay-text d-flex">
                                    <div>
                                        <h5 class="text-white mb-2">Favorite Brands</h5>
                                        <p class="text-white">Explore our collection of favorite shoe brands, featuring the latest styles and trends.</p>
                                        <a href="javascript:void(0);" onclick="openBrandsModal()" class="btn custom-btn mt-2 mt-lg-3">Select Brands</a>
                                    </div>
                                    <span class="badge bg-finance rounded-pill ms-auto">✩</span>
                                </div>
                                <div class="section-overlay"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <section class="explore-section section-padding" id="section_2">
            <div class="container">
                <div class="col-12 text-center">
                    <h2 class="mb-4">Result of search</h1>
                </div>

            </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="design-tab" data-bs-toggle="tab"
                                data-bs-target="#design-tab-pane" type="button" role="tab"
                                aria-controls="design-tab-pane" aria-selected="true">All</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="males-tab" data-bs-toggle="tab"
                                data-bs-target="#males-tab-pane" type="button" role="tab"
                                aria-controls="males-tab-pane" aria-selected="false">Males</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="females-tab" data-bs-toggle="tab"
                                data-bs-target="#females-tab-pane" type="button" role="tab"
                                aria-controls="females-tab-pane" aria-selected="false">Females</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unisex-tab" data-bs-toggle="tab"
                                data-bs-target="#unisex-tab-pane" type="button" role="tab" aria-controls="unisex-tab-pane"
                                aria-selected="false">Unisex</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="salesoff-tab" data-bs-toggle="tab"
                                data-bs-target="#salesoff-tab-pane" type="button" role="tab"
                                aria-controls="salesoff-tab-pane" aria-selected="false" style="color: red;">Sales off
                                !!!</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tab-content" id="myTabContent">

                            <!-- All Products Tab -->
                            <div class="tab-pane fade show active" id="design-tab-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
                                <div id="product-container" class="row">
                                    <?php foreach ($shoes as $shoe): ?>
                                        <!-- Display each product as before -->
                                        <?php include 'product_template.php'; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Males Tab -->
                            <div class="tab-pane fade" id="males-tab-pane" role="tabpanel" aria-labelledby="males-tab" tabindex="0">
                                <div class="row">
                                    <?php foreach ($shoes as $shoe): ?>
                                        <?php if ($shoe['gender'] == 'Male'): ?>
                                            <?php include 'product_template.php'; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Females Tab -->
                            <div class="tab-pane fade" id="females-tab-pane" role="tabpanel" aria-labelledby="females-tab" tabindex="0">
                                <div class="row">
                                    <?php foreach ($shoes as $shoe): ?>
                                        <?php if ($shoe['gender'] == 'Female'): ?>
                                            <?php include 'product_template.php'; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Unisex Tab -->
                            <div class="tab-pane fade" id="unisex-tab-pane" role="tabpanel" aria-labelledby="unisex-tab" tabindex="0">
                                <div class="row">
                                    <?php foreach ($shoes as $shoe): ?>
                                        <?php if ($shoe['gender'] == 'Unisex'): ?>
                                            <?php include 'product_template.php'; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Sales Off Tab -->
                            <div class="tab-pane fade" id="salesoff-tab-pane" role="tabpanel" aria-labelledby="salesoff-tab" tabindex="0">
                                <div class="row">
                                    <?php foreach ($shoes as $shoe): ?>
                                        <?php if ($shoe['idSale'] != 0): ?>
                                            <?php include 'product_template.php'; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sizeModalLabel">Choose Size</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Input ẩn để lưu ID sản phẩm -->
                            <input type="hidden" id="selectedProductId">
                            <div class="form-group">
                                <label for="sizeSelect">Select a size:</label>
                                <select class="form-control" id="sizeSelect">
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmAddToCart">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function openSizeModal(productId) {
                    // Gán ID sản phẩm vào input ẩn trong modal
                    document.getElementById('selectedProductId').value = productId;
                    // Hiển thị modal
                    $('#sizeModal').modal('show');
                }
                document.getElementById('confirmAddToCart').addEventListener('click', function() {
                    // Lấy ID sản phẩm từ input ẩn
                    const productId = document.getElementById('selectedProductId').value;
                    // Lấy kích thước được chọn
                    const selectedSize = document.getElementById('sizeSelect').value;

                    const selectColor = document.getElementById('selectedProductColor').value;
                    // Dữ liệu gửi tới server
                    const data = {
                        idproduct: productId,
                        idcart: <?php echo $idCart; ?>, // ID giỏ hàng từ PHP
                        quantity: 1, // Mặc định số lượng là 1
                        size: selectedSize, // Kích thước sản phẩm
                        color: selectColor, // Mặc định màu sản phẩm
                        isPay: 'no' // Mặc định chưa thanh toán

                    };

                    // Gửi dữ liệu qua Fetch API
                    fetch('../Shoes/product/addToCart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'The product has been added to your cart.',
                                    showConfirmButton: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: result.message || 'Failed to add product to the cart.',
                                    showConfirmButton: true
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong. Please try again later.',
                                showConfirmButton: true
                            });
                        });

                    // Đóng modal
                    $('#sizeModal').modal('hide');
                });
            </script>
        </section>

        <section id="shoesearchIMG">
            <img style="display: absolute; width: 1250px; margin-top: -100px; width: 100%;" src="images/shoesearch.png" alt="">
        </section>

        <section class="timeline-section section-padding" id="section_3">
            <div class="section-overlay"></div>

            <div class="container">
                <div class="row">

                    <div class="col-12 text-center">
                        <h2 class="text-white mb-4">How does it work?</h1>
                    </div>

                    <div class="col-lg-10 col-12 mx-auto">
                        <div class="timeline-container">
                            <ul class="vertical-scrollable-timeline" id="vertical-scrollable-timeline">
                                <div class="list-progress">
                                    <div class="inner"></div>
                                </div>

                                <li>
                                    <h4 class="text-white mb-3">Search your favourite shoes</h4>

                                    <p class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                        Reiciendis, cumque magnam? Sequi, cupiditate quibusdam alias illum sed esse ad
                                        dignissimos libero sunt, quisquam numquam aliquam? Voluptas, accusamus omnis?
                                    </p>

                                    <div class="icon-holder">
                                        <i class="bi-search"></i>
                                    </div>
                                </li>

                                <li>
                                    <h4 class="text-white mb-3">Bookmark &amp; Keep it for yourself</h4>

                                    <p class="text-white">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sint
                                        animi necessitatibus aperiam repudiandae nam omnis est vel quo, nihil repellat
                                        quia velit error modi earum similique odit labore. Doloremque, repudiandae?</p>

                                    <div class="icon-holder">
                                        <i class="bi-bookmark"></i>
                                    </div>
                                </li>

                                <li>
                                    <h4 class="text-white mb-3">Read &amp; Enjoy</h4>

                                    <p class="text-white">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                        Animi vero quisquam, rem assumenda similique voluptas distinctio, iste est hic
                                        eveniet debitis ut ducimus beatae id? Quam culpa deleniti officiis autem?</p>

                                    <div class="icon-holder">
                                        <i class="bi-book"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-5">
                        <p class="text-white">
                            Want to learn more?
                            <a href="#" class="btn custom-btn custom-border-btn ms-3">Check out Youtube</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <section class="faq-section section-padding" id="section_4">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <h2 class="mb-4">Frequently Asked Questions</h2>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-lg-5 col-12">
                        <img src="images/faq_graphic.jpg" class="img-fluid" alt="FAQs">
                    </div>

                    <div class="col-lg-6 col-12 m-auto">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        What is Shoestore ?
                                    </button>
                                </h2>

                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>Shoestore</strong> is typically understood as a store or website that specializes in selling shoes. This can be a physical store or an online shop (e-commerce) where customers can find a variety of shoe types.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        How to find a voucher?
                                    </button>
                                </h2>

                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>Asking via Live Chat or Email</strong>: Use the live chat feature on a retailer's website or send them an email to inquire about any available vouchers or promotions. Customer service representatives can often provide you with current offers.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Do I need to pay to use the AI support feature?
                                    </button>
                                </h2>

                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <strong>No</strong>, the AI support feature, or <em style="color:purple">WizardShoes</em>,
                                        is a tremendous tool that makes it easier for you to search for and purchase shoes , information of sales or anything on this website.
                                        With its ability to provide accurate and quick information,
                                        this feature will help you save time and effort.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section style="background-color: #ffeee7;" class="contact-section section-padding section-bg" id="section_5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12 text-center">
                        <h2 class="mb-5">Get in touch</h2>
                    </div>

                    <div class="col-lg-5 col-12 mb-4 mb-lg-0">
                        <iframe class="google-map"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2595.065641062665!2d-122.4230416990949!3d37.80335401520422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858127459fabad%3A0x808ba520e5e9edb7!2sFrancisco%20Park!5e1!3m2!1sen!2sth!4v1684340239744!5m2!1sen!2sth"
                            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-3 mb-lg- mb-md-0 ms-auto">
                        <h4 class="mb-3">Head office</h4>

                        <p>Bay St &amp;, Larkin St, San Francisco, CA 94109, United States</p>

                        <hr>

                        <p class="d-flex align-items-center mb-1">
                            <span class="me-2">Phone</span>

                            <a href="tel: 305-240-9671" class="site-footer-link">
                                305-240-9671
                            </a>
                        </p>

                        <p class="d-flex align-items-center">
                            <span class="me-2">Email</span>

                            <a href="mailto:shoestore251211@gmail.com" class="site-footer-link">
                                info@company.com
                            </a>
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mx-auto">
                        <h4 class="mb-3">Dubai office</h4>

                        <p>Burj Park, Downtown Dubai, United Arab Emirates</p>

                        <hr>

                        <p class="d-flex align-items-center mb-1">
                            <span class="me-2">Phone</span>

                            <a href="tel: 110-220-3400" class="site-footer-link">
                                110-220-3400
                            </a>
                        </p>

                        <p class="d-flex align-items-center">
                            <span class="me-2">Email</span>

                            <a href="mailto:info@company.com" class="site-footer-link">
                                info@company.com
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </section>
    </main>
    <!-- End Main -->

    <!-- Brands Modal -->
    <div id="bradsModal" class="brads-modal">
        <div class="brads-modal-content">
            <span class="brads-close" onclick="closeBrandsModal()">&times;</span>
            <h5>Select Brands</h5>
            <div class="brads-image-grid">
                <div class="brads-image-box" style="background-image: url('https://gigamall.com.vn/data/2019/09/05/15023424_LOGO-NIKE-500x500.jpg');" onclick="toggleSelection(this, 'Nike')">
                    <span>Nike</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://static.vecteezy.com/system/resources/previews/010/994/332/non_2x/puma-logo-black-symbol-clothes-design-icon-abstract-football-illustration-with-red-background-free-vector.jpg');" onclick="toggleSelection(this, 'Puma')">
                    <span>Puma</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://i.pinimg.com/736x/70/fb/bb/70fbbbb1d15922445e981ddf29c4a1d7.jpg');" onclick="toggleSelection(this, 'Adidas')">
                    <span>Adidas</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://images2.minutemediacdn.com/image/upload/c_fill,w_1200,ar_16:9,f_auto,q_auto,g_auto/images/voltaxMediaLibrary/mmsport/mentalfloss/01gv19d1fnt9c0sftbc5.jpg');" onclick="toggleSelection(this, 'MLB')">
                    <span>MLB</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdTqDdTumZAmB0bbS7igFxHaYwsZMWB7wFPA&s');" onclick="toggleSelection(this, 'MIRA')">
                    <span>MIRA</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrvDGf4eEnnQjb9-eYQbGBIo3gvnzUvj_1EHyyJ5jWXPs_iDZ24a32Df8RC7luHm7-lCs&usqp=CAU');" onclick="toggleSelection(this, 'New Balance')">
                    <span>New Balance</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://down-ph.img.susercontent.com/file/cn-11134216-7r98o-lv5qjw651sjxa6');" onclick="toggleSelection(this, 'Ceymme')">
                    <span>Ceymme</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFuoWR3p_nr6bgBnXgrqfKVVK8aYo743BSPw&s');" onclick="toggleSelection(this, 'Converse')">
                    <span>Converse</span>
                </div>
                <div class="brads-image-box" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQx3OzRnHuVR4vzeVEiyszdt9BhHiAMwyKkZw&s');" onclick="toggleSelection(this, 'Vans')">
                    <span>Vans</span>
                </div>
            </div>
            <button class="brads-btn" onclick="submitBrandsSelection()">OK</button>
        </div>
    </div>
    <!-- Chat Popup -->
    <div class="chat-popup">
        <div class="main-circle" onclick="toggleChatOptions()">💬</div> <!-- Main chat icon -->
        <div class="chat-options" style="display: none;"> <!-- Hide initially -->
            <div class="chat-option" onclick="openLiveChatWindow('live')">
                🗣️ <!-- Live Chat icon -->
            </div>
            <div class="chat-option" onclick="openChatWindow('ai')">
                🤖 <!-- AI Chat icon -->
            </div>
        </div>
    </div>

    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window" style="display: none; background-color:aliceblue"> <!-- Hide initially -->
        <div class="chat-header">
            <span id="chatTitle">Chat</span>
            <button style="background-color: red; border-radius:50%; color:white" onclick="closeChatWindow()">✖</button>
        </div>
        <div class="chat-content chat-body">
            <!-- Chat messages will appear here -->
        </div>
        <div style="margin-bottom: 10px;" class="chat-input">
            <input type="text" placeholder="Type a message..." id="message-input" class="message-input" />
            <button id="send-message" onclick="handleOutgoingMessage(event)">Send</button>
        </div>
    </div>
    <!--Live chat-->
    <?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "shopbangiay";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Failed to connect: " . $conn->connect_error);
    }
    $sql = "SELECT chatbox FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    // Bind tham số (idUser) vào prepared statement
    $stmt->bind_param("i", $idUser);

    // Thực thi câu lệnh SQL
    $stmt->execute();

    // Lấy kết quả từ câu lệnh SQL
    $result = $stmt->get_result(); // Sử dụng get_result() thay vì mysqli_query
    $chatbox = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);
    //print_r($shoes);
    ?>
    <div id="liveChatWindow" class="chat-window" style="display: none; background-color:antiquewhite">
        <div class="chat-header">
            <span id="liveTitle">Live Chat</span>
            <button style="background-color: red; border-radius:50%; color:white" onclick="closeLiveChatWindow()">✖</button>
        </div>
        <div class="chat-content chat-body" id="liveContent">
            <!-- Chat messages will appear here -->
        </div>
        <div style="margin-bottom: 10px;" class="chat-input">
            <input type="text" placeholder="Type a message..." id="liveMessageInput" class="message-input" />
            <button id="liveSendMessage" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        // Sample chatbox data from PHP (initial load)
        let liveChatData = '<?php echo $chatbox[0]['chatbox']; ?>';

        function displayChatMessages(liveChatData) {
            if (liveChatData && liveChatData.trim() !== '') {
                const chatContent = document.getElementById("liveContent");

                // Split the chat data based on symbols and filter out empty results
                const chatParts = liveChatData.split(/(👤|🛠️)/).filter(Boolean);

                let currentUsername = '';
                let isUser = false;

                chatParts.forEach(part => {
                    if (part === '👤' || part === '🛠️') {
                        // Set the flag for user or admin
                        isUser = part === '👤';
                    } else {
                        if (!currentUsername) {
                            // If currentUsername is empty, set the part as the username
                            currentUsername = part.trim();
                        } else {
                            // Otherwise, treat this part as the message
                            const currentMessage = part.trim();

                            // Create a message bubble
                            const messageBubble = document.createElement("div");
                            messageBubble.className = isUser ? 'user-message' : 'admin-message';
                            messageBubble.innerHTML = `<strong>${currentUsername}</strong>: ${currentMessage}`;

                            // Apply different styling for user and admin messages
                            messageBubble.style = isUser ?
                                'background-color: lightgreen; align-self: flex-end; margin: 5px; padding: 10px; border-radius: 10px 10px 0 10px;' :
                                'background-color: lightblue; align-self: flex-start; margin: 5px; padding: 10px; border-radius: 10px 10px 10px 0;';

                            // Append the message to the chat content
                            chatContent.appendChild(messageBubble);

                            // Reset the username for the next message
                            currentUsername = '';
                        }
                    }
                });
            }
        }

        let isOpenLiveChat = false;
        // Function to fetch new chatbox data from the server
        function fetchNewMessages() {
            let idUser = <?php echo $idUser; ?>; // Example hardcoded user ID; this should be dynamic.

            if (isOpenLiveChat) {
                fetch(`getChatbox.php?idUser=${idUser}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            liveChatData = data.chatbox; // Update the liveChatData with new data
                            document.getElementById("liveContent").innerHTML = '';
                            console.log(liveChatData);
                            displayChatMessages(liveChatData); // Re-render the chatbox
                        } else {
                            console.error('Error fetching chatbox:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching chatbox:', error);
                    });
            }
        }


        // Initial display of chat messages
        displayChatMessages(liveChatData);

        // Automatically reload new chat every 5 seconds
        setInterval(fetchNewMessages, 2000);

        // Send message function
        function sendMessage() {
            const messageInput = document.getElementById("liveMessageInput");
            const messageText = messageInput.value.trim();

            if (messageText !== "") {
                const chatContent = document.getElementById("liveContent");

                let idUser = <?php echo $idUser ?>
                // chatContent.appendChild(userMessageBubble);
                addChatbox(idUser, 'customer', messageText); // You should implement this function to save message
                messageInput.value = ""; // Clear input after sending
                fetchNewMessages();
                // Here you can add code to send the message to the server or handle it as needed
            }
        }
    </script>



    <style>
        .admin-message {
            background-color: lightblue;
            align-self: flex-start;
            margin: 5px;
            padding: 10px;
            border-radius: 10px 10px 10px 0;
        }

        .user-message {
            background-color: lightgreen;
            align-self: flex-end;
            margin: 5px;
            padding: 10px;
            border-radius: 10px 10px 0 10px;
        }
    </style>


    <script>
        // Toggle the display of chat options
        function toggleChatOptions() {
            const chatOptions = document.querySelector('.chat-options');
            chatOptions.style.display = chatOptions.style.display === 'flex' ? 'none' : 'flex';
        }

        // Open chat window with specific title (Live Chat or AI Chat)
        function openChatWindow(type) {
            const chatWindow = document.getElementById('chatWindow');
            const chatTitle = document.getElementById('chatTitle');
            chatTitle.textContent = type === 'live' ? 'Live Chat' : 'AI Chat';
            chatWindow.style.display = 'block';
            document.querySelector('.chat-options').style.display = 'none';
        }

        function openLiveChatWindow() {
            isOpenLiveChat = true;
            const chatWindow = document.getElementById('liveChatWindow');
            chatWindow.style.display = 'block'
            document.querySelector('.chat-options').style.display = 'none';
        }
        // Close chat window
        function closeChatWindow() {
            isOpenLiveChat = false;
            document.getElementById('chatWindow').style.display = 'none';
        }
        // Close chat window
        function closeLiveChatWindow() {
            document.getElementById('liveChatWindow').style.display = 'none';
        }
        let productsData;
        let salesData;
        let chatData;
        let UserInputText = '';
        // Function to fetch data from the PHP file
        async function fetchData() {
            try {
                const response = await fetch('getData.php');
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                const data = await response.json();
                const productsData = JSON.stringify(data.products, null, 2);
                console.log("Products Data:", productsData);
                salesData = JSON.stringify(data.sales, null, 2);

                chatData = `
  Pretend you are WizardShoes (an A.I. helper in helping, searching, guiding when customer use ShopShoes website)
   and use Data Shoes available and Data Sales to solve my question (Before answer you should tell customer who are you and you can add emoji that relative your answer for friendly).
  When i ask you search you just need base on Database, no need to ask much thing or created more question or predict customer's question or yapping.
  This is my database:
  Data Shoes available: ${productsData}, Data Sales: ${salesData}
  when ask for shoes attach link: http://localhost/WebsiteDoAnCoSo2/Shoes/product/product.php?id={idShoes}
  when ask for sales (and sales have bigSales !=null or not empty) attach link: http://localhost/WebsiteDoAnCoSo2/Admin/phpSales/info.php?product={idSales}
  (Just only when someone ask you that who created wizardshoes: WizardShoes and ShopShoes was created by group Trương Công Thành and Trần Thanh Phong)
`
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        }

        // Select required elements
        const chatBody = document.querySelector(".chat-body");
        const messageInput = document.querySelector(".message-input");
        const sendMessageButton = document.querySelector("#send-message");

        const API_KEY = "AIzaSyCLUFioEhWhd3qtBZzN-6Y43J4pdnpha-A";
        const API_URL = `https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;

        const chatHistory = [];

        // Create message element
        const createMessageElement = (content, ...classes) => {
            const div = document.createElement("div");
            div.classList.add("message", ...classes);
            div.innerHTML = content;
            return div;
        };

        // Generate bot response using API
        const generateBotResponse = async (incomingMessageDiv) => {
            const messageElement = incomingMessageDiv.querySelector(".message-text");
            console.log(UserInputText);
            chatHistory.push({
                role: "user",
                parts: [{
                    text: UserInputText + ".RolePlay :{" + chatData + "}"
                }],
            });

            const requestOptions = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    contents: chatHistory
                }),
            };

            try {
                const response = await fetch(API_URL, requestOptions);
                const data = await response.json();
                if (!response.ok) throw new Error(data.error.message);

                const apiResponseText = data.candidates[0].content.parts[0].text.trim();
                messageElement.innerText = apiResponseText;

                chatHistory.push({
                    role: "model",
                    parts: [{
                        text: apiResponseText
                    }],
                });
            } catch (error) {
                messageElement.innerText = error.message;
                messageElement.style.color = "#ff0000";
            } finally {
                chatBody.scrollTo({
                    top: chatBody.scrollHeight,
                    behavior: "smooth"
                });
            }
        };

        // Handle outgoing message
        const handleOutgoingMessage = (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            UserInputText = message;
            messageInput.value = "";

            // Display the user's message
            const outgoingMessageDiv = createMessageElement(`<div class="message-text">${message}</div>`, "user-message");
            chatBody.appendChild(outgoingMessageDiv);
            chatBody.scrollTo({
                top: chatBody.scrollHeight,
                behavior: "smooth"
            });

            // Generate bot response after short delay
            setTimeout(() => {
                const incomingMessageDiv = createMessageElement(`<div class="message-text"></div>`, "bot-message");
                chatBody.appendChild(incomingMessageDiv);
                chatBody.scrollTo({
                    top: chatBody.scrollHeight,
                    behavior: "smooth"
                });
                generateBotResponse(incomingMessageDiv);
            }, 600);
        };

        // Fetch initial data
        fetchData();

        // Send message when the user presses Enter or clicks send
        messageInput.addEventListener("keydown", (e) => {
            const userMessage = e.target.value.trim();
            if (e.key === "Enter" && !e.shiftKey && userMessage) handleOutgoingMessage(e);
        });

        sendMessageButton.addEventListener("click", (e) => handleOutgoingMessage(e));

        function addChatbox(userId, role = 'customer', messageText) {

            const chatboxInput = messageText.trim();

            if (!chatboxInput) {
                console.error("Chatbox input is empty!");
                return;
            }

            const updateChatbox = {
                userId: userId,
                chatbox: chatboxInput,
                role: role,
            };

            // Debug thông tin gửi đi
            console.log("Data to send:", updateChatbox);

            // Gửi dữ liệu đến PHP
            fetch('updateChatbox.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updateChatbox),
                })
                .then((response) => response.text())
                .then((result) => {
                    console.log('Response from PHP:', result);
                    // Xóa input sau khi gửi thành công
                    document.getElementById("liveMessageInput").value = '';
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
        // Hàm kiểm tra và thay thế liên kết bằng nút
        const replaceLinksInMessages = () => {
            // Lấy tất cả phần tử có class "message-text" trong bot-message
            const botMessages = document.querySelectorAll(".bot-message .message-text");

            botMessages.forEach(message => {
                // Kiểm tra nếu đã xử lý
                if (message.dataset.processed === "true") return;
                console.log(message.innerHTML);
                let content = message.innerHTML;

                // Thay thế liên kết sản phẩm
                content = content.replace(
                    /http:\/\/localhost\/WebsiteDoAnCoSo2\/Shoes\/product\/product.php\?id=\d+/g,
                    match => {
                        return `<button class="btn btn-primary" onclick="window.location.href='${match}'">View this product</button>`;
                    }
                );

                // Thay thế liên kết sales info
                content = content.replace(
                    /http:\/\/localhost\/WebsiteDoAnCoSo2\/Admin\/phpSales\/info.php\?product=\d+/g,
                    match => {
                        return `<button class="btn btn-secondary" onclick="window.location.href='${match}'">View this sale</button>`;
                    }
                );

                // Cập nhật lại nội dung của tin nhắn
                message.innerHTML = content;

                // Đánh dấu đã xử lý
                if (message.innerHTML != 'bot message' && message.innerHTML != '') message.dataset.processed = "true";
            });
        };



        // Thiết lập kiểm tra mỗi 5 giây
        setInterval(replaceLinksInMessages, 1500);
    </script>

    <script>
        // Array to store selected brands
        let selectedBrands = [];

        // Function to toggle brand selection
        function toggleSelection(element, brand) {
            element.classList.toggle('selected');
            if (element.classList.contains('selected')) {
                selectedBrands.push(brand);
            } else {
                const index = selectedBrands.indexOf(brand);
                if (index > -1) {
                    selectedBrands.splice(index, 1);
                }
            }
        }

        // Function to submit selected brands
        function submitBrandsSelection() {
            const brandsImage = document.getElementById('brandsImage');
            if (selectedBrands.length > 0) {
                // Update the image and text with the first selected brand
                const firstBrand = selectedBrands[0];
                const brandImages = {
                    'Nike': 'https://gigamall.com.vn/data/2019/09/05/15023424_LOGO-NIKE-500x500.jpg',
                    'Puma': 'https://static.vecteezy.com/system/resources/previews/010/994/332/non_2x/puma-logo-black-symbol-clothes-design-icon-abstract-football-illustration-with-red-background-free-vector.jpg',
                    'Adidas': 'https://i.pinimg.com/736x/70/fb/bb/70fbbbb1d15922445e981ddf29c4a1d7.jpg',
                    'MLB': 'https://images2.minutemediacdn.com/image/upload/c_fill,w_1200,ar_16:9,f_auto,q_auto,g_auto/images/voltaxMediaLibrary/mmsport/mentalfloss/01gv19d1fnt9c0sftbc5.jpg',
                    'MIRA': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdTqDdTumZAmB0bbS7igFxHaYwsZMWB7wFPA&s',
                    'New Balance': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrvDGf4eEnnQjb9-eYQbGBIo3gvnzUvj_1EHyyJ5jWXPs_iDZ24a32Df8RC7luHm7-lCs&usqp=CAU',
                    'Ceymme': 'https://down-ph.img.susercontent.com/file/cn-11134216-7r98o-lv5qjw651sjxa6',
                    'Converse': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFuoWR3p_nr6bgBnXgrqfKVVK8aYo743BSPw&s',
                    'Vans': 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQx3OzRnHuVR4vzeVEiyszdt9BhHiAMwyKkZw&s'
                };

                brandsImage.src = brandImages[firstBrand] || 'images/topics/undraw_Remote_design_team_re_urdx.png';
                alert('You have selected: ' + selectedBrands.join(', '));
            } else {
                alert('No brands selected!');
            }
            autoSelectBrands(selectedBrands);
            closeBrandsModal(); // Close modal after selection

        }

        // Function to open the brands modal
        function openBrandsModal() {
            document.getElementById('bradsModal').style.display = 'block';
        }

        // Function to close the brands modal
        function closeBrandsModal() {
            document.getElementById('bradsModal').style.display = 'none';
        }
    </script>

    <script>
        let selectedPricing = "";

        function showPricingOptions() {
            const pricingContent = document.getElementById("pricing-content");
            console.log('showpriciiiiiiinnnnnngggg');
            // Thay đổi thành flex-column với các nút lựa chọn giá
            pricingContent.className = "d-flex flex-column";
            pricingContent.innerHTML = `
        <h5 class="mb-2">Choose Your Budget</h5>
        <div class="btn-group-vertical mt-3" role="group">
            <button class="btn btn-secondary mb-2" onclick="displaySelection('All prices')">All Prices</button>
            <button class="btn btn-secondary mb-2" onclick="displaySelection('≤ 500,000 VND')">≤ 500,000 VND</button>
            <button class="btn btn-secondary mb-2" onclick="displaySelection('≤ 1,000,000 VND')">≤ 1,000,000 VND</button>
            <button class="btn btn-secondary mb-2" onclick="displaySelection('≤ 5,000,000 VND')">≤ 5,000,000 VND</button>
            <button class="btn btn-secondary" onclick="displaySelection('> 5,000,000 VND')">> 5,000,000 VND</button>
        </div>
    `;
        }

        function displaySelection(priceRange) {
            event.stopPropagation();
            const pricingContent = document.getElementById("pricing-content");
            console.log(priceRange);
            // Khôi phục về d-flex với nội dung đã chọn
            pricingContent.className = "d-flex";
            pricingContent.innerHTML = `
        <div>
            <h5 class="mb-2">Affordable Pricing</h5>
            <p class="mb-0">${priceRange} is a great choice!!!</p>
        </div>
        <span class="badge bg-design rounded-pill ms-auto">$</span>
    `;
            selectedPricing = priceRange;
            autoSelectPrice(selectedPricing);
        }
    </script>
    <script>
        // Function to auto-select the price range in the dropdown
        function autoSelectPrice(price) {
            const priceDropdown = document.getElementById('priceRange');
            for (let i = 0; i < priceDropdown.options.length; i++) {
                if (priceDropdown.options[i].value === price) {
                    priceDropdown.selectedIndex = i;
                    break;
                }
            }
        }

        // Function to auto-select brand checkboxes
        function autoSelectBrands(brandArray) {
            brandArray.forEach(brand => {
                const checkbox = document.querySelector(`input[name="brands[]"][value="${brand}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // // Example usage:
        // // Select price range '≤ 500,000 VND'
        // autoSelectPrice('≤ 500,000 VND');

        // // Select brands 'Nike' and 'Puma'
        // autoSelectBrands(['Nike', 'Puma']);
    </script>
    <script>
        // Function to retrieve URL parameters
        function getUrlParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name) || '';
        }

        // Check if keyword and price are empty
        window.addEventListener('DOMContentLoaded', () => {
            const keyword = getUrlParameter('keyword');
            const price = getUrlParameter('price');

            // Get the section to be hidden
            const exploreSection = document.getElementById('section_2');
            const shoesearchIMG = document.getElementById('shoesearchIMG');
            // Hide section if both keyword and price are empty
            let hasProductYet = hasProduct();
            if ((!keyword && !price) || !hasProductYet) {
                console.log('okkkkkkkkkkk')
                exploreSection.hidden = true;
                shoesearchIMG.hidden = false;
            } else {
                exploreSection.hidden = false;
                shoesearchIMG.hidden = true;
            }
        });

        function hasProduct() {
            const productContainer = document.getElementById("product-container");

            if (productContainer && productContainer.children.length > 0) {
                console.log('okkkkkkkkkkk1111111111111111111111111111111')
                return true;
            } else {
                return false;
            }
        }
    </script>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <!-- <script src="js/click-scroll.js"></script> -->
    <script src="js/custom.js"></script>


</body>
<style>
    /* Chat Popup Styling */
    .chat-popup {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1000;
        /* Ensures it's always on top */
    }

    .main-circle {
        width: 60px;
        height: 60px;
        background-color: #13547a;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .main-circle:hover {
        transform: scale(1.1);
    }

    .chat-options {
        display: none;
        margin-top: 10px;
        gap: 10px;
        flex-direction: column;
        align-items: center;
    }

    .chat-option {
        width: 50px;
        height: 50px;
        background-color: #80d0c7;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .chat-option span {
        display: none;
        margin-top: 5px;
        font-size: 12px;
        color: #717275;
    }

    .chat-option:hover {
        transform: scale(1.1);
    }

    .chat-option:hover span {
        display: inline-block;
    }

    /* Chat Window Styling */
    .chat-window {
        margin-bottom: -70px;
        display: none;
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 300px;
        height: 400px;
        background-color: #ffffff;
        border: 1px solid #13547a;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        z-index: 1000;
    }

    .chat-header {
        background-color: #13547a;
        color: white;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-content {
        padding: 10px;
        height: 300px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        max-height: 400px;
    }

    .user-message {
        align-self: flex-end;
        background-color: #0084ff;
        color: white;
        border-radius: 10px;
        padding: 8px 12px;
        margin: 5px 0;
        max-width: 70%;
    }

    .bot-message {
        align-self: flex-start;
        background-color: #e5e5ea;
        color: black;
        border-radius: 10px;
        padding: 8px 12px;
        margin: 5px 0;
        max-width: 70%;
    }

    .thinking-indicator .dot {
        background-color: #0084ff;
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: blink 1.4s infinite both;
    }

    .thinking-indicator .dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .thinking-indicator .dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    .chat-input {
        display: flex;
        padding: 10px;
        margin-top: -10px;
    }

    .chat-input input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 5px;
    }

    .chat-input button {
        background-color: #80d0c7;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Responsive Media Queries */
    @media (max-width: 768px) {
        .chat-window {
            width: 90%;
            bottom: 20px;
            right: 5%;
            height: 60%;
        }

        .main-circle {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .chat-option {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .chat-content {
            height: calc(60% - 100px);
            /* Adjusted height for smaller screens */
        }

        .chat-input input,
        .chat-input button {
            padding: 6px;
        }
    }

    @media (max-width: 480px) {
        .chat-window {
            width: 100%;
            right: 0;
            height: 50%;
        }

        .main-circle {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }

        .chat-option {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .chat-content {
            height: calc(50% - 100px);
        }

        .chat-input input,
        .chat-input button {
            margin-top: 150px;
        }
    }
</style>

</html>