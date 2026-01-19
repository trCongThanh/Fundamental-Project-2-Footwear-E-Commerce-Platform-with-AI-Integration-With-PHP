<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "shopbangiay";
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$userid = $_GET['user_id'] ?? '';
if (!empty($userid)) {
    $userid = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
} else {
    die("No ID provided.");
}
?>
<!DOCTYPE html>
<html data-wf-domain="homepageshoes.webflow.io" data-wf-page="66f8e87fbb0f45ece8b0d856" data-wf-site="66f8e87fbb0f45ece8b0d847" data-wf-status="1">

<head>
    <meta charset="utf-8" />
    <title>Shoes Store</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Webflow" name="generator" />
    <link href="homepage.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Great Vibes:400"]
            }
        });
    </script>
    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);
    </script>
    <style>
        .w-webflow-badge {
            display: none !important;
        }

        .original-price {
            color: #a59f9f;
            font-size: 14px;
            text-decoration: line-through;
            background-color: #f1f1f1;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
        }

        .heading-9 {
            text-align: center;
        }

        .container-5 {
            max-width: 30vw;
        }

        .container-4 {

            max-width: 25vw;

        }

        .name {
            margin-right: 5vw;
        }

        .card {
            max-width: 35%;
        }

        .container-4 {
            grid-column-gap: 1vw;
            grid-row-gap: 1vw;
            ;
        }

        @media screen and (max-width: 1280px) {
            .product {
                margin-top: 22vw;
            }
        }

        @media screen and (max-width: 900px) {
            .product {
                margin-top: 25vw;
            }
        }

        @media screen and (max-width: 768px) {
            .container-5 {
                max-width: 20vw;
            }

            .product {
                margin-top: 35vw;
            }
        }

        @media screen and (max-width: 479px) {
            .name {
                margin-left: 15vw;
                margin-right: 3vw;
            }

            .container-5 {
                max-width: 10vw;
            }

            .original-price {

                font-size: 8px !important;

            }

            .product {
                margin-top: 60vw;
                grid-column-gap: 0.5vw;
                grid-row-gap: 0.5vw;
            }
        }

        @media screen and (max-width: 320px) {
            .name {
                margin-left: 10vw;
                margin-right: 1vw;
            }

            .product {
                margin-top: 100vw;
            }
        }

        .hidden {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .visible {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .w-icon-slider-right:before {
            content: "❯";
        }

        .w-icon-slider-left:before {
            content: "❮";
        }

        .w-icon-nav-menu:before {
            content: "☰";
        }

        .w-icon-arrow-down:before,
        .w-icon-dropdown-toggle:before {
            content: "☰";
        }

        .w-icon-file-upload-remove:before {
            content: "☰";
        }

        .w-icon-file-upload-icon:before {
            content: "☰";
        }

        .w-nav-overlay {
            width: 100%;
            display: none;
            position: absolute;
            z-index: 999;
            top: 100%;
            left: 0;
            right: 0;
            overflow: hidden;
        }

        .nav-menu-block {
            flex-direction: row !important;
        }
    </style>
</head>

<body class="body">
    <section class="intro hidden" style="background-image: url('../uploads/homebg.png');">
        <div class="navbar-logo-center">
            <div data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease" role="banner" class="navbar-logo-center-container shadow-three w-nav">
                <div class="container">
                    <div class="navbar-wrapper-three"><a href="#" class="navbar-brand-three w-nav-brand"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/671a666345ef5cecc0aa5f5f_shoe-logo_673221-128.jpg" loading="lazy" width="80" sizes="(max-width: 479px) 17vw, (max-width: 1279px) 80px, (max-width: 1439px) 6vw, 80px" alt="" srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/671a666345ef5cecc0aa5f5f_shoe-logo_673221-128-p-500.jpg 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/671a666345ef5cecc0aa5f5f_shoe-logo_673221-128.jpg 740w" class="image-12" /></a>
                        <nav role="navigation" class="nav-menu-wrapper-three w-nav-menu">
                            <div class="nav-menu-three">
                                <ul role="list" class="nav-menu-block w-list-unstyled">
                                    <li><a href="homepage.php?user_id=<?php echo $userid ?>" class="nav-link">Home</a></li>
                                    <li><a href="type.php?user_id=<?php echo $userid ?>&type=men" class="nav-link">Men</a></li>
                                    <li><a href="type.php?user_id=<?php echo $userid ?>&type=women" class="nav-link">Women</a></li>
                                    <li><a href="type.php?user_id=<?php echo $userid ?>&type=unisex" class="nav-link">Unisex</a></li>
                                </ul>
                                <ul role="list" class="nav-menu-block w-list-unstyled">
                                    <li><a href="cart.php?cart_id=<?php echo $cart_id ?>" class="nav-link-accent">Cart</a></li>
                                    <li class="mobile-margin-top-10"><a href="login.php" class="button-primary w-button">SIGN UP/IN</a><a href="search.php" class="button-primary w-button">SEARCH</a></li>
                                </ul>
                            </div>
                        </nav>
                        <div class="menu-button-2 w-nav-button">
                            <div class="w-icon-nav-menu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="div-block-16">
            <h1 class="heading-9">WELCOME</h1>
            <h1 class="heading-9">WE ARE SHOESSTORE</h1>
            <div class="text-block-17">a website design by TPN Team VKU 23JIT</div>
        </div>
    </section>
    <section class="men hidden">
        <h1 class="heading-10">MEN</h1>
        <div class="div-block-17">
            <div class="div-block-18"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf081d853ed7decd9ab21_men.jpg" loading="lazy" alt="" class="image-13" /></div>
            <div class="div-block-20"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance.jpg" loading="lazy" width="70" sizes="20vw" alt="" srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance-p-500.jpg 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf07f824b66f1f087e4ef_newbalance.jpg 736w" class="image-14" /></div>
            <div class="div-block-18">
                <div class="text-block-17">&quot;Men&#x27;s footwear defines style and confidence, blending fashion with purpose in every step.&quot;</div><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf1bf973806a6f0ad8cd4_men2.jpg" loading="lazy" width="187.5" alt="" class="image-13" />
            </div>
        </div>
    </section>
    <section class="women hidden">
        <h1 class="heading-10">WOMEN</h1>
        <div class="div-block-17">
            <div class="div-block-18">
                <div class="text-block-17">&quot;Women&#x27;s footwear embodies elegance and confidence, merging style with every step forward.&quot;</div><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf68ba9794311f56d0d0e_women.jpg" loading="lazy" width="187.5" alt="" class="image-13" />
            </div>
            <div class="div-block-20"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2.jpg" loading="lazy" width="70" sizes="20vw" alt="" srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2-p-500.jpg 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf7c4d9007b4fb4510515_women2.jpg 736w" class="image-14" /></div>
        </div>
    </section>
    <section class="unisex hidden">
        <h1 class="heading-10">UNISEX</h1>
        <div class="div-block-17">
            <div class="div-block-18"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf9204a189d5332efa70f_uni.jpg" loading="lazy" alt="" class="image-13" /></div>
            <div class="div-block-20"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf920eb17b395132f5e26_u.jpg" loading="lazy" width="70" alt="" class="image-14" /></div>
            <div class="div-block-18"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/673cf9209421153e7d0472e4_unisex.jpg" loading="lazy" width="187.5" alt="" class="image-13" /></div>
        </div>
        <div class="text-block-17">&quot;Unisex footwear seamlessly combines style and versatility, making every step a statement of individuality.&quot;</div>
    </section>
    <section class="review-section hidden"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f90751e8f6ba076ffa6b80_close-up-futuristic-sneakers.png" loading="lazy" width="666.5" sizes="(max-width: 1919px) 38vw, 666.5px" alt="" srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f90751e8f6ba076ffa6b80_close-up-futuristic-sneakers-p-500.png 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f90751e8f6ba076ffa6b80_close-up-futuristic-sneakers-p-800.png 800w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f90751e8f6ba076ffa6b80_close-up-futuristic-sneakers-p-1080.png 1080w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f90751e8f6ba076ffa6b80_close-up-futuristic-sneakers.png 1333w" class="image-10" />
        <div class="nav-content">
            <h1 class="heading-10">Where Style Meets Versatility</h1>
            <div class="text-block-17">Explore footwear that effortlessly blends timeless design with modern versatility, perfect for every occasion and every individual</div>
        </div>
    </section>
    <section class="review-section hidden">
        <div class="nav-content">
            <h1 class="heading-10">Exceptional Services for a Seamless Shopping Experience</h1>
            <div class="text-block-17">Enjoy free shipping, fast delivery, easy returns, and secure payments. Our 24/7 customer support ensures you&#x27;re always taken care of</div>
        </div><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f907beb64fda3d77b4adda_close-up-futuristic-sneakers-presentation.png" loading="lazy" width="666.5" sizes="(max-width: 1919px) 38vw, 666.5px" alt="" srcset="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f907beb64fda3d77b4adda_close-up-futuristic-sneakers-presentation-p-500.png 500w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f907beb64fda3d77b4adda_close-up-futuristic-sneakers-presentation-p-800.png 800w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f907beb64fda3d77b4adda_close-up-futuristic-sneakers-presentation-p-1080.png 1080w, https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f907beb64fda3d77b4adda_close-up-futuristic-sneakers-presentation.png 1121w" class="image-10" />
    </section>
    <section class="pd hidden">
        <?php
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "shopbangiay";

        try {
            $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "
      SELECT p.id AS product_id, p.name, p.type, p.price, p.img, p.bg, p.idSale, s.discount_percentage
      FROM products p
      LEFT JOIN sales s ON p.idSale = s.id
      ";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
        ?>
                <div data-delay="4000" data-animation="slide" class="slider-2 w-slider" data-autoplay="true" data-easing="ease-in" data-hide-arrows="true" data-disable-swipe="false" data-autoplay-limit="0" data-nav-spacing="3" data-duration="500" data-infinite="true">
                    <div class="w-slider-mask">
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $product_id = $row['product_id'];
                            $name = htmlspecialchars($row['name']);
                            $type = htmlspecialchars($row['type']);
                            $price = $row['price'];
                            $image = $row['img'];
                            $bg = $row['bg'];
                            $discount_percentage = $row['discount_percentage'] ? round($row['discount_percentage']) : null;
                            $discounted_price = $discount_percentage
                                ? $price - ($price * ($discount_percentage / 100))
                                : $price;
                        ?>
                            <div class="w-slide" data-product-id="<?php echo $product_id; ?>">
                                <div class="background-video-2 w-background-video w-background-video-atom">
                                    <video autoplay loop muted playsinline data-object-fit="cover">
                                        <source src="<?php echo $bg ?>" type="video/mp4">
                                    </video>
                                    <div class="w-layout-blockcontainer name w-container">
                                        <h1 class="heading-9"><?php echo $name; ?></h1>
                                    </div>
                                    <img src="<?php echo $image ?: 'https://via.placeholder.com/666'; ?>" loading="lazy" class="image-8" />
                                    <div class="w-layout-blockcontainer product w-container">
                                        <img src="<?php echo $image ?: 'https://via.placeholder.com/666'; ?>" loading="lazy" class="image-9" />
                                        <h4 class="heading-10"><?php echo $name; ?></h4>
                                        <div class="w-layout-blockcontainer container-5 w-container">
                                            <div class="text-block-18">₫<?php echo number_format($discounted_price, 0, ',', '.'); ?></div>
                                            <?php if ($discount_percentage): ?>
                                                <span class="original-price">₫<?php echo number_format($price, 0, ',', '.'); ?></span>
                                                <a class="button-8 w-button" style="width: 12vw;color:#d12b2b; font: size 1rem; background-color:#ff4d4d; padding:5px 10px; border-radius:50px; font-weight:bold; display:inline-block; text-transform:uppercase; letter-spacing:1px; box-shadow: 0 0 8px rgba(209, 43, 43, 0.7);">
                                                    -<?php echo $discount_percentage; ?>%
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="w-layout-blockcontainer container-4 w-container">
                                            <?php
                                            if (!function_exists('extractColorsFromUrl')) {
                                                function extractColorsFromUrl($imageUrl)
                                                {
                                                    $fileName = basename($imageUrl, ".png");
                                                    $parts = explode("_", $fileName);

                                                    array_shift($parts);

                                                    return $parts;
                                                }
                                            }

                                            $colors = extractColorsFromUrl($image);
                                            if (!empty($colors)) {
                                                foreach ($colors as $color) {
                                                    echo "<a href='#' class='button-6 w-button' style='background-color: $color;'></a>";
                                                }
                                            } else {
                                                echo "<a href='#' class='button-6 w-button'></a>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="w-slider-arrow-left">
                        <div class="icon-3 w-icon-slider-left"></div>
                    </div>
                    <div class="w-slider-arrow-right">
                        <div class="icon-4 w-icon-slider-right"></div>
                    </div>
                    <div class="w-slider-nav w-shadow w-round"></div>
                </div>
        <?php
            } else {
                echo "<p>No products found.</p>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Could not connect: " . $e->getMessage();
        }
        ?>
    </section>



    <section class="product-section hidden">
        <?php
        $db_server = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "shopbangiay";

        try {
            $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "
      SELECT p.id AS product_id, p.name, p.price, p.img, p.idSale, s.discount_percentage
      FROM products p
      LEFT JOIN sales s ON p.idSale = s.id
      ";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = $row['product_id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $image = $row['img'];
                    $discount_percentage = $row['discount_percentage'];
                    $discount_percentage = $discount_percentage ? round($discount_percentage) : null;
                    $discounted_price = $discount_percentage
                        ? $price - ($price * ($discount_percentage / 100))
                        : $price;
        ?>
                    <div class="w-layout-blockcontainer card w-container" data-product-id="<?php echo $product_id; ?>">
                        <img src="<?php echo $image ?: 'https://via.placeholder.com/666'; ?>" loading="lazy" width="666.5" sizes="17vw" alt="Product Image" class="card-image" />

                        <div class="div-block-21">
                            <h4 class="heading-10"><?php echo htmlspecialchars($name); ?></h4>
                            <div class="w-layout-blockcontainer container-5 w-container">
                                <div class="text-block-18">
                                    ₫<?php echo number_format($discounted_price, 0, ',', '.'); ?>
                                </div>
                                <?php if ($discount_percentage): ?>
                                    <span class="original-price" style="color:#a59f9f; font-size:16px; text-decoration: line-through;">
                                        ₫<?php echo number_format($price, 0, ',', '.'); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($discount_percentage): ?>
                                    <a href="#" class="button-8 w-button" style="width: 12vw;color:#d12b2b; font: size 1rem; background-color:#ff4d4d; padding:5px 10px; border-radius:50px; font-weight:bold; display:inline-block; text-transform:uppercase; letter-spacing:1px; box-shadow: 0 0 8px rgba(209, 43, 43, 0.7);">
                                        -<?php echo $discount_percentage; ?>%
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="w-layout-blockcontainer container-4 w-container">
                            <?php
                            if (!function_exists('extractColorsFromUrl')) {
                                function extractColorsFromUrl($imageUrl)
                                {
                                    $fileName = basename($imageUrl, ".png");
                                    $parts = explode("_", $fileName);


                                    array_shift($parts);

                                    return $parts;
                                }
                            }

                            $colors = extractColorsFromUrl($image);
                            if (!empty($colors)) {
                                foreach ($colors as $color) {
                                    echo "<a href='#' class='button-6 w-button' style='background-color: $color;'></a>";
                                }
                            } else {
                                echo "<a href='#' class='button-6 w-button'></a>";
                            }
                            ?>
                        </div>

                    </div>
        <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Could not connect: " . $e->getMessage();
        }
        ?>
    </section>


    <div class="sales-info-section hidden" style="padding: 24px; max-width: 900px; margin: 40px auto; color: #4a4a4a; text-align: center;">
        <h2 class="heading-10" style="margin :4vw;">Available Sales</h2>
        <div class="sales-list" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php
            $db_server = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_name = "shopbangiay";

            try {
                $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT name, description, discount_percentage, start_date, end_date, img FROM sales";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['name'];
                        $description = $row['description'];
                        $discount_percentage = $row['discount_percentage'];
                        $start_date = $row['start_date'];
                        $end_date = $row['end_date'];
                        $img = $row['img'];
            ?>
                        <div class="sale-item" style="background-color: #f2dddc; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15); transition: transform 0.3s ease, box-shadow 0.3s ease; text-align: left; border: 1px solid #e0e0e0;">
                            <img src="<?php echo $img; ?>" alt="Sale Image" class="sale-image" style="width: 100%; height: auto; object-fit: cover; border-bottom: 3px solid #ffd700;" />
                            <div class="sale-details" style="padding: 16px 20px;">
                                <h3 class="heading-10"><?php echo $name; ?></h3>
                                <p class="text-block-18"><?php echo $description; ?></p>
                                <div class="sale-info" style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                                    <span class="sale-discount" style="font-weight: bold; color: #ff5722;">-<?php echo $discount_percentage; ?>%</span>
                                    <p class="sale-dates" style="color: #888;">Valid: <?php echo $start_date; ?> - <?php echo $end_date; ?></p>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo "<p style='color: #555;'>No sales available at the moment.</p>";
                }
            } catch (mysqli_sql_exception $e) {
                echo "Could not connect: " . $e->getMessage();
            }
            ?>
        </div>
    </div>
    <section class="inbox-section hidden">
        <div class="nav-inbox">
            <h1 class="heading-9">More</h1>
            <div class="text-block-16">Get the lashopbangiay update, news and exclusive drop-sent straight to your inbox</div><a href="login.php" class="more w-button">Sign up for email</a>
        </div>
    </section>
    <section class="footer-section hidden"><a href="#" class="nav-footer-link">CONTACT US</a>
        <div class="nav-apps"><a href="#" class="card-product w-inline-block"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f9fe4bda53b22d4c0f8356_fb.png" loading="lazy" width="55.5" alt="" class="image-2" /></a><a href="#" class="card-product w-inline-block"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f9fd96840cc13a9a5543c8_insta.png" loading="lazy" width="68" alt="" class="image-3" /></a><a href="#" class="card-product w-inline-block"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f9fe4bc50a28b58f1235b1_twi.png" loading="lazy" width="66" alt="" class="image-4" /></a><a href="#" class="card-product w-inline-block"><img src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/66f9fe4b19d477e7b4763832_phone.png" loading="lazy" width="61" alt="" class="image-5" /></a></div>
        <div class="about"><a href="homepage.php?user_id=<?php echo $userid ?>" class="menu-link">home</a><a href="#" class="menu-link">news</a><a href="#" class="menu-link">help</a><a href="#" class="menu-link">about us</a></div>
        <div class="team">
            <div class="text-block-3">Design by TPN Team VKU 23JIT</div>
            <div class="text-block-3">No Copyright VKU@024</div>
        </div>
    </section>
    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=66f8e87fbb0f45ece8b0d847" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.prod.website-files.com/66f8e87fbb0f45ece8b0d847/js/webflow.80bb5a981.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elementsToObserve = document.querySelectorAll(".intro,.men,.women,.unisex,.review-section,.pd,.product-section, .sales-info-section,.inbox-section,.footer-section");

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("visible");
                            entry.target.classList.remove("hidden");
                        } else {
                            entry.target.classList.remove("visible");
                            entry.target.classList.add("hidden");
                        }
                    });
                }, {
                    threshold: 0.2,
                }
            );

            elementsToObserve.forEach((element) => {
                observer.observe(element);
            });
        });
        document.querySelectorAll('.w-slide').forEach(slide => {
            slide.addEventListener('click', () => {
                const productId = slide.getAttribute('data-product-id');

                const viewUrl = `view.php?user_id=<?php echo $userid ?>&p_id=${encodeURIComponent(productId)}`;

                window.location.href = viewUrl;
            });
        });
        document.querySelectorAll('.w-layout-blockcontainer.card').forEach(card => {
            card.addEventListener('click', () => {
                const productId = card.getAttribute('data-product-id');

                const viewUrl = `view.php?user_id=<?php echo $userid ?>&p_id=${encodeURIComponent(productId)}`;

                window.location.href = viewUrl;
            });
        });
    </script>
</body>

</html>