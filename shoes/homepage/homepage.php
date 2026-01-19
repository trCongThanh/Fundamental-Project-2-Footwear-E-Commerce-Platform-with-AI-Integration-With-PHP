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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
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
            <a class="navbar-brand mx-auto" href="homepage.php">ShoeStore</a>

            <!-- Navbar Links and Buttons -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Links -->
                <ul class="navbar-nav me-auto flex-column flex-lg-row align-items-lg-center gap-0 gap-lg-5">
                    <li class="nav-item"><a class="nav-link" href="?gender=male">Male</a></li>
                    <li class="nav-item"><a class="nav-link" href="?gender=female">Female</a></li>
                    <li class="nav-item"><a class="nav-link" href="?gender=unisex">Unisex</a></li>
                </ul>
                
                <!-- Right Buttons <i class="bi bi-search"></i> -->
                <div class="d-flex mt-3 mt-lg-0 justify-content-lg-end gap-0 gap-lg-5">
                    <button class="btn btn-outline-secondary me-2 nav-btn" onclick="window.location.href='../../Search/search.php?keyword=&price='">
                        <i class="bi bi-search"></i> Search
                    </button>

                    <button class="btn btn-outline-success me-2 nav-btn" id="cartButton">
                        <i class="bi bi-cart"></i> Cart
                    </button>
                    <button class="btn btn-outline-primary nav-btn" onclick="window.location.href='../profile/profile.php'">
                        <i class="bi bi-person"></i> Profile
                    </button>
                </div>
            </div>
        </div>
    </nav>




    <!-- Hero Section -->
    <section id="hero" class="hero d-flex align-items-center justify-content-center text-center">
        <div class="container animate-on-scroll">
            <h1 id="hero-title" class="display-4">Welcome to ShoeStore</h1>
            <p id="hero-subtitle" class="lead">Find your perfect pair of shoes today.</p>
            <a id="hero-button" href="#hot-products" class="btn btn-primary mt-3">Shop Now</a>
        </div>
    </section>

    <!-- Advertise Section -->
    <section id="advertise" class="advertise py-5 bg-light">
        <div class="container animate-on-scroll">
            <h2 class="text-center mb-4">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img style="width: 100px;height: 100px" src="../uploads/bestq.gif" alt="Quality" class="mb-3">
                    <h4>Top Quality</h4>
                    <p>We ensure the best materials and designs for our shoes.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img style="width: 100px;height: 100px" src="../uploads/aprice.gif" alt="Affordable" class="mb-3">
                    <h4>Affordable Prices</h4>
                    <p>Premium shoes without breaking the bank.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img style="width: 100px;height: 100px" src="../uploads/gsupport.gif" alt="Support" class="mb-3">
                    <h4>Great Support</h4>
                    <p>We're here to help, anytime, anywhere.</p>
                </div>
            </div>
        </div>
    </section>
    <section hidden class="men">
        <h1 class="heading-10">MEN</h1>
        <div class="div-block-17 animate-on-scroll">
            <div class="div-block-18"><img
                    src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf081d853ed7decd9ab21_men.jpg"
                    loading="lazy" alt="" class="image-13" /></div>
            <div class="div-block-20"><img
                    src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance.jpg"
                    loading="lazy" width="70" sizes="20vw" alt=""
                    srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance-p-500.jpg 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance.jpg 736w"
                    class="image-14" /></div>
            <div class="div-block-18">
                <div class="text-block-17">&quot;Men&#x27;s footwear defines style and confidence, blending fashion with
                    purpose in every step.&quot;</div><img
                    src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf1bf973806a6f0ad8cd4_men2.jpg"
                    loading="lazy" width="187.5" alt="" class="image-13" />
            </div>
        </div>
    </section>
    <section hidden class="women animate-on-scroll">
        <h1 class="heading-10">WOMEN</h1>
        <div class="div-block-17">
            <div class="div-block-18">
                <div class="text-block-17">&quot;Women&#x27;s footwear embodies elegance and confidence, merging style
                    with every step forward.&quot;</div><img
                    src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf68ba9794311f56d0d0e_women.jpg"
                    loading="lazy" width="187.5" alt="" class="image-13" />
            </div>
            <div class="div-block-20"><img
                    src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2.jpg"
                    loading="lazy" width="70" sizes="20vw" alt=""
                    srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2-p-500.jpg 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2.jpg 736w"
                    class="image-14" /></div>
        </div>
    </section>
    <section hidden class="unisex animate-on-scroll">
        <h1 class="heading-10">UNISEX</h1>
        <div class="div-block-17">
            <div class="div-block-18"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf9204a189d5332efa70f_uni.jpg" loading="lazy" alt="" class="image-13" /></div>
            <div class="div-block-20"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf920eb17b395132f5e26_u.jpg" loading="lazy" width="70" alt="" class="image-14" /></div>
            <div class="div-block-18"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf9209421153e7d0472e4_unisex.jpg" loading="lazy" width="187.5" alt="" class="image-13" /></div>
        </div>
        <div class="text-block-17">&quot;Unisex footwear seamlessly combines style and versatility, making every step a statement of individuality.&quot;</div>
    </section>
    <section hidden class="review-section "><img style="width: 50%;" id="bigimg1" src="" loading="lazy" width="666.5" sizes="(max-width: 1919px) 38vw, 666.5px" alt=""  class="image-10" />
        <div class="nav-content">
            <h1 id="bigtitle1" class="heading-10">Where Style Meets Versatility</h1>
            <div id="bigtext1" class="text-block-17">Explore footwear that effortlessly blends timeless design with modern versatility, perfect for every occasion and every individual</div>
        </div>
    </section>
    <section hidden class="review-section ">
        <div class="nav-content">
            <h1 id="bigtitle2" class="heading-10">Exceptional Services for a Seamless Shopping Experience</h1>
            <div id="bigtext2" class="text-block-17">Enjoy free shipping, fast delivery, easy returns, and secure payments. Our 24/7 customer support ensures you&#x27;re always taken care of</div>
        </div><img style="width: 50%;" id="bigimg2" src="" loading="lazy" width="666.5" sizes="(max-width: 1919px) 38vw, 666.5px" alt=""  class="image-10" />
    </section>
    <?php
include('database.php');

$gender = $_GET['gender'] ?? '';
$filterCondition = '';

if (!empty($gender)) {
    // Chỉ áp dụng điều kiện lọc nếu `gender` không rỗng
    $filterCondition = " WHERE p.gender = '" . mysqli_real_escape_string($conn, $gender) . "'";
}

// Kết hợp điều kiện lọc với truy vấn chính
$sql = "SELECT 
            p.id, 
            p.name AS product_name, 
            p.img, 
            p.price, 
            p.idSale,
            s.name AS sales_name, 
            s.color, 
            s.pos, 
            s.discount, 
            s.bigsales 
        FROM 
            products p
        LEFT JOIN 
            sales s ON p.idSale = s.id
        $filterCondition";

$result = mysqli_query($conn, $sql);
$shoes = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
// mysqli_close($conn);
?>


    <section id="hot-products" class="hot-products py-5">
        <div class="container animate-on-scroll">
            <h2 id="products-title" class="text-center mb-4">Best Sellers</h2>
            <div id="products-container" class="row g-4">
                <?php foreach ($shoes as $shoe):
                    // Extract the first "color<>img" pair
                    $first_entry = explode(',', $shoe['img'])[0];
                    list($color, $img) = explode('<>', $first_entry);

                    // Calculate discounted price if idSale is valid
                    $has_sale = $shoe['idSale'] !== null && $shoe['idSale'] != 0;

                    $price = isset($shoe['price']) ? (float)$shoe['price'] : 0; // Ensure numeric
                    $discount = isset($shoe['discount']) ? (float)$shoe['discount'] : 0;

                    $discounted_price = $has_sale && $discount > 0
                        ? round($price * (1 - $discount / 100), 2)
                        : $price;
                ?>
                    <div class="col-md-4 product-item" data-sale="<?= htmlspecialchars($shoe['sales_name']) ?>">
                        <a style="text-decoration: none;" href="../product/product.php?id=<?= urlencode($shoe['id']) ?>" class="card product-card" style="--hover-color: <?= htmlspecialchars($color) ?>; z-index: 5">
                            <div class="card-img-container position-relative">
                                <img src="../../Admin/img/<?= htmlspecialchars($img) ?>"
                                    class="card-img-top"
                                    alt="<?= htmlspecialchars($shoe['product_name']) ?>">
                                <?php if ($has_sale): ?>
                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                        <?= htmlspecialchars($shoe['sales_name']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body" style="border: 1px solid #ccc;">
                                <h5 style="min-height: 48px" class="card-title"><?= htmlspecialchars($shoe['product_name']) ?></h5>
                                <p class="card-text">
                                    <?php if ($has_sale): ?>
                                        <span style="text-decoration: line-through; color: #888;">
                                            $<?= htmlspecialchars($shoe['price']) ?>
                                        </span>
                                        <span style="color: red; font-style: italic;">
                                            $<?= htmlspecialchars($discounted_price) ?>
                                        </span>
                                    <?php else: ?>
                                        $<?= htmlspecialchars($shoe['price']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>




    <style>
        /* Ensure consistent image size */
        .card-img-container {
            position: relative;
            width: 300px;
            height: 200px;
            overflow: hidden;
            margin: auto;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: fill;
            /* Ensure images fill the container proportionally */
            transition: transform 0.3s ease;
        }

        /* Default card styles */
        .product-card {
            position: relative;
            /* background-color: var(--hover-color); */
            overflow: hidden;
            border: none;
            transition: box-shadow 0.3s ease;
            border: 1px solid #ccc;
        }

        /* Hover effect: spreading water-like color */
        .product-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 0;
            background: var(--hover-color);
            z-index: 1;
            opacity: 0.5;
            transition: width 0.4s ease, height 0.4s ease;
            border-radius: 50%;
        }

        .product-card:hover::before {
            width: 300%;
            /* Increase size to cover the card */
            height: 300%;
        }

        .product-card:hover .card-img-top {
            transform: rotate(-20deg);
            /* Tilt image on hover */
        }

        .product-card {
            position: relative;
            z-index: 2;
            /* Ensure text remains on top */
        }

        .card-body {
            position: relative;
            z-index: 1;
            /* Ensure text remains on top */
        }

        .badge {
            font-size: 0.9em;
            padding: 0.3em 0.6em;
            border-radius: 0.5em;
        }
    </style>



    <!-- Sales Section -->
    <?php
    include('database.php');

    // Fetch data from the sales table
    $sql = "SELECT id,name, color, pos, discount, bigsales FROM sales";
    $result = mysqli_query($conn, $sql);
    $sales = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($conn);

    // Select 3 random sales
    if (count($sales) > 3) {
        $random_keys = array_rand($sales, 3);
        $sales = array_intersect_key($sales, array_flip($random_keys));
    }
    ?>

    <section id="sales" class="sales py-5 bg-light">
        <div class="container animate-on-scroll">
            <h2 class="text-center mb-4">Sales</h2>
            <p class="text-center">Enjoy exclusive discounts on our top-selling items!</p>
            <div class="row g-4">
                <?php foreach ($sales as $sale): ?>
                    <div class="col-md-4">
                        <div class="sale-card text-center position-relative"
                            onclick="filterProducts('<?= htmlspecialchars($sale['name']) ?>')">
                            <?php
                            // Handle bigsales logic
                            if (!empty($sale['bigsales'])):
                                list($img, $info) = explode('<>', $sale['bigsales']);
                            ?>
                                <div class="sale-banner"
                                    style="background-image: url('../../Admin/phpSales/imgSales/<?= htmlspecialchars($img) ?>');">
                                </div>
                                <div class="sale-info">
                                    <p>
                                        <a href="../../Admin/phpSales/info.php?product=<?= htmlspecialchars($sale['id']) ?>" target="_blank" class="btn btn-primary">
                                            Learn More
                                        </a>
                                    </p>
                                    <p><strong><?= htmlspecialchars($sale['discount']) ?>% OFF</strong></p>
                                </div>
                            <?php else: ?>
                                <div class="sale-banner"
                                    style="background: linear-gradient(135deg, <?= htmlspecialchars($sale['color']) ?>, #ffffff);">
                                    <h4 class="sale-title text-white"><?= htmlspecialchars($sale['name']) ?></h4>
                                </div>
                                <div class="sale-info">
                                    <p><strong><?= htmlspecialchars($sale['discount']) ?>% OFF</strong></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <style>
        /* Card container styles */
        .sale-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .sale-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Sale banner styles */
        .sale-banner {
            position: relative;
            height: 150px;
            background-size: cover;
            background-position: center;
            border-bottom: 2px solid #ddd;
        }

        /* Overlay title for non-big-sales */
        .sale-title {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            font-size: 2.25rem;
            font-weight: bold;
            margin: 0;
            padding: 0 10px;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
        }

        /* Sale info styles */
        .sale-info {
            padding: 16px;
        }

        .sale-info p {
            margin-bottom: 4px;
            color: #555;
        }

        /* Discount text highlight */
        .sale-info strong {
            color: #e63946;
            font-size: 1.2rem;
        }

        /* Button styles */
        .btn-primary {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function filterProducts(saleName) {
            // Update the products title
            document.getElementById('products-title').innerText = saleName;

            // Get all product items
            const products = document.querySelectorAll('.product-item');

            // Loop through products and toggle visibility
            products.forEach(product => {
                if (product.dataset.sale === saleName) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        }
    </script>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
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

                <script>
                    let currentGender = '<?php echo $gender; ?>';
                    
                        // Reset tất cả các trạng thái ẩn/hiện trước
                        document.querySelector('.men').hidden = true;
                        document.querySelector('.women').hidden = true;
                        document.querySelector('.unisex').hidden = true;
                        document.querySelectorAll('.review-section').forEach(section => section.hidden = true);
                        console.log(currentGender); //console.log ra male
                        if (currentGender === 'male') {
                            document.querySelector('.hero').style.backgroundImage = "url('../uploads/men.png')";
                            document.querySelector('.men').hidden = false;

                            // Nội dung review-section cho nam
                            const section1 = document.querySelectorAll('.review-section')[0];
                            const section2 = document.querySelectorAll('.review-section')[1];
                            section1.hidden = false;
                            section1.querySelector('#bigimg1').src = '../uploads/bigmen1.png';
                            section1.querySelector('#bigtitle1').innerText = "Experience the Strength of Masculine Design";
                            section1.querySelector('#bigtext1').innerText = "Discover shoes tailored for men with bold designs and supreme comfort.";

                            section2.hidden = false;
                            section2.querySelector('#bigimg2').src = '../uploads/bigmen2.png';
                            section2.querySelector('#bigtitle2').innerText = "Built for Durability and Style";
                            section2.querySelector('#bigtext2').innerText = "Perfect shoes for every occasion, crafted for men who lead the way.";
                        } else if (currentGender === 'female') {
                            document.querySelector('.hero').style.backgroundImage = "url('../uploads/women.png')";
                            document.querySelector('.women').hidden = false;

                            // Nội dung review-section cho nữ
                            const section1 = document.querySelectorAll('.review-section')[0];
                            const section2 = document.querySelectorAll('.review-section')[1];
                            section1.hidden = false;
                            section1.querySelector('#bigimg1').src = '../uploads/bigwomen1.png';
                            section1.querySelector('#bigtitle1').innerText = "Where Elegance Meets Comfort";
                            section1.querySelector('#bigtext1').innerText = "Explore chic designs tailored for women, ensuring elegance and ease.";

                            section2.hidden = false;
                            section2.querySelector('#bigimg2').src = '../uploads/bigwomen2.png';
                            section2.querySelector('#bigtitle2').innerText = "Step Into Confidence";
                            section2.querySelector('#bigtext2').innerText = "Shoes crafted for modern women who embrace both style and functionality.";
                        } else if (currentGender === 'unisex') {
                            document.querySelector('.hero').style.backgroundImage = "url('../uploads/unisex.png')";
                            document.querySelector('.unisex').hidden = false;

                            // Nội dung review-section cho unisex
                            const section1 = document.querySelectorAll('.review-section')[0];
                            const section2 = document.querySelectorAll('.review-section')[1];
                            section1.hidden = false;
                            section1.querySelector('#bigimg1').src = '../uploads/bigunisex1.png';
                            section1.querySelector('#bigtitle1').innerText = "Timeless Style for Everyone";
                            section1.querySelector('#bigtext1').innerText = "Unisex designs that fit every personality and occasion.";

                            section2.hidden = false;
                            section2.querySelector('#bigimg2').src = '../uploads/bigunisex2.png';
                            section2.querySelector('#bigtitle2').innerText = "Versatility at Its Best";
                            section2.querySelector('#bigtext2').innerText = "Shoes designed for inclusivity, offering comfort and style for all.";
                        } else {
                            // Mặc định khi không xác định giới tính
                            document.querySelector('.hero').style.backgroundImage = "url('../uploads/homebg.png')";
                            document.querySelector('.men').hidden = false;
                            document.querySelector('.women').hidden = false;
                            document.querySelector('.unisex').hidden = false;

                            document.querySelectorAll('.review-section').forEach(section => section.hidden = true);
                        }

                </script>   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>
<style>
    .gen,
    .men,
    .women,
    .unisex {
        margin-top: 4vw;
        margin-bottom: 4vw;
    }

    .review-section {
        grid-column-gap: 4vw;
        grid-row-gap: 4vw;
        border-radius: 10px;
        flex-flow: row;
        justify-content: center;
        align-items: center;
        height: auto;
        margin: 0 1vw 2vw;
        padding: 0 2vw 0;
        display: flex;
    }

    .heading-10 {
        text-align: center;
        overflow-wrap: anywhere;
        flex-flow: wrap;
        margin-top: 0;
        margin-bottom: 0;
        padding-left: 0;
        font-family: Georgia, Times, Times New Roman, serif;
        font-size: 1.5rem;
        font-weight: 400;
    }

    .div-block-18 {
        grid-column-gap: 5vw;
        grid-row-gap: 5vw;
        flex-flow: column;
        justify-content: center;
        align-items: center;
        width: 30%;
        margin-top: 6vw;
        padding-left: 0;
        padding-right: 0;
        display: flex;
    }

    .div-block-17 {
        grid-column-gap: 2vw;
        grid-row-gap: 2vw;
        justify-content: center;
        align-items: center;
        margin-bottom: 4vw;
        display: flex;
    }

    .div-block-20 {
        margin-bottom: 3vw;
    }

    .text-block-17 {
        text-align: center;
        font-family: Georgia, Times, Times New Roman, serif;
        font-size: 1rem;
    }

    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .image-13 {
        width: 20vw;
    }

    .image-14 {
        width: 20vw;
        margin-bottom: 0;
    }
</style>
<style>
    body {
        font-family: Arial, sans-serif;
        scroll-behavior: smooth;
    }

    .hero {
        background-image: url('../uploads/homebg.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        height: 150vh;
        color: white;
    }

    section {
        min-height: 50vh;
        padding: 2rem 0;
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

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
<script>
    let currentUserId = <?php echo $idUser ?>;
    console.log(currentUserId);
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelector('#cartButton').addEventListener('click', () => {
            window.location.href = `../cart/cart.php?user_id=${currentUserId}`;
        });
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                    }
                });
            }, {
                threshold: 0.1
            }
        );

        document.querySelectorAll(".animate-on-scroll").forEach((section) => {
            observer.observe(section);
        });
    });
    document.querySelectorAll(".nav-btn").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            console.log(`${btn.textContent} clicked`);
            // Thêm hành động ở đây
        });
    });
</script>
<!-- <button class="btn btn-outline-secondary me-2 nav-btn" onclick="alert('Cart')">
                        <i class="bi bi-cart"></i> Cart
                    </button> -->