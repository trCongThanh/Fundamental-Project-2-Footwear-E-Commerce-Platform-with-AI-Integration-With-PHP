<?php
$idUser = "";
$currentUsername = "";
$idCart = "";
if (isset($_COOKIE["idUser"]) && isset($_COOKIE["username"]) && isset($_COOKIE["idCart"])) {
    $idUser = $_COOKIE["idUser"];
    $currentUsername = $_COOKIE["username"];
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
    <title>Nike Shoes Display</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-white text-black">
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
    <?php
    include('../homepage/database.php');

    // Get the product ID from URL
    $product_id = intval($_GET['id']); // Ensure the ID is an integer

    // Query to fetch product details
    $sql = "SELECT 
                p.id, 
                p.name AS product_name, 
                p.desc,
                p.brands,
                p.gender,
                p.img, 
                p.price, 
                p.idSale,
                p.comment,
                s.name AS sales_name, 
                s.color, 
                s.pos, 
                s.discount, 
                s.bigsales 
            FROM 
                products p
            LEFT JOIN 
                sales s ON p.idSale = s.id
            WHERE 
                p.id = $product_id";

    $result = mysqli_query($conn, $sql); // Execute the query
    if ($result) {
        $product = mysqli_fetch_assoc($result); // Fetch the product details
    } else {
        die("Product not found");
    }
    // Parse comments
    $comments = [];
    if (!empty($product['comment'])) {
        $raw_comments = explode('||', $product['comment']);
        foreach ($raw_comments as $raw_comment) {
            [$user, $comment] = explode(':', $raw_comment, 2);
            $comments[] = ['user' => trim($user), 'comment' => trim($comment)];
        }
    }

    // Parse images and colors
    $color_images = [];
    if (!empty($product['img'])) {
        $raw_color_images = explode(',', $product['img']);
        foreach ($raw_color_images as $entry) {
            [$color, $img] = explode('<>', $entry, 2);
            $color_images[] = ['color' => trim($color), 'img' => trim($img)];
        }
    }
    $current_buy_now_price;
    if (!empty($product['idSale']) && $product['idSale'] != 0) {
        $discount = floatval(str_replace('%', '', $product['discount']));

        // Tính giá sau khi giảm giá
        $discounted_price = $product['price'] * (1 - $discount / 100);
        $has_discount = true; // Flag để xác định có giảm giá
        $current_buy_now_price = $discounted_price;
    } else {
        $discounted_price = $product['price'];
        $current_buy_now_price = $product['price'];
        $has_discount = false;
    }

    ?>
    <div class="container py-5">
        <!-- Main Product Section -->
        <div class="row mb-5">
            <div class="col-md-6">
                <!-- Product Image -->
                <img style="width: 100%;" id="main-image" src="../../Admin/img/<?= htmlspecialchars($color_images[0]['img']) ?>" alt="Main Shoe"
                    class="img-fluid animate__animated animate__fadeIn">

                <!-- Gallery Section -->
                <h3 class="mb-3">Gallery</h3>
                <div class="gallery d-flex overflow-auto mb-5" id="gallery">
                    <?php foreach ($color_images as $entry): ?>
                        <div class="col-6 col-md-3 mb-3">
                            <img src="../../Admin/img/<?= htmlspecialchars($entry['img']) ?>"
                                alt="Gallery Image"
                                class="img-fluid rounded shadow-sm"
                                onclick="updateMainImage('../../Admin/img/<?= htmlspecialchars($entry['img']) ?>');">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <script>
                let images = []; // Stores fetched images
                let selectedColor = ''; // Stores selected color
                // Update the main image
                function updateMainImage(imagePath) {
                    document.getElementById('main-image').src = imagePath;
                }

                // Fetch and display images for a selected color
                function changeColorAndLoadImages(color, folderPath) {
                    selectedColor = color;
                    updateMainImage(`../../Admin/img/${folderPath}`); // Set base image

                    // Clear existing gallery
                    const gallery = document.getElementById('gallery');
                    gallery.innerHTML = '';
                    //
                    addFirstIMG(folderPath);
                    //
                    // Fetch new images
                    morePicture(folderPath.split('.')[0], gallery);
                }

                // Fetch additional images dynamically
                function morePicture(folderPath, gallery) {

                    fetch(`../../Admin/phpFile/listImages.php?folder=${folderPath}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                console.error("Error:", data.error);
                                return;
                            }
                            images = data; // Store fetched images

                            // Add new images to the gallery
                            images.forEach(img => {
                                const colDiv = document.createElement('div');
                                colDiv.className = "col-6 col-md-3 mb-3";
                                console.log('folderpath', folderPath);
                                const imgElement = document.createElement('img');
                                imgElement.src = `../../Admin/img/${folderPath}/${img}`;
                                imgElement.alt = "Gallery Image";
                                imgElement.className = "img-fluid rounded shadow-sm";
                                imgElement.onclick = () => updateMainImage(`../../Admin/img/${folderPath}/${img}`);

                                colDiv.appendChild(imgElement);
                                gallery.appendChild(colDiv);
                            });
                        })
                        .catch(error => console.error("Error loading images:", error));
                }

                function addFirstIMG(folderPath) {
                    const gallery = document.getElementById('gallery');
                    const colDiv = document.createElement('div');
                    colDiv.className = "col-6 col-md-3 mb-3";
                    console.log('folderpath', folderPath);
                    const imgElement = document.createElement('img');
                    imgElement.src = `../../Admin/img/${folderPath}`;
                    imgElement.alt = "Gallery Image";
                    imgElement.className = "img-fluid rounded shadow-sm";
                    imgElement.onclick = () => updateMainImage(`../../Admin/img/${folderPath}`);

                    colDiv.appendChild(imgElement);
                    gallery.appendChild(colDiv);
                }
            </script>
            <div class="col-md-6">
                <!-- Product Details -->
                <h1 class="display-5 fw-bold"><?= htmlspecialchars($product['product_name']) ?></h1>
                <?php if ($has_discount): ?>
                    <p class="text-muted">
                        Price:
                        <span class="text-decoration-line-through text-muted">$<?= number_format($product['price'], 0) ?></span>
                        <span class="text-danger fw-bold">$<?= number_format($discounted_price, 0) ?></span>
                        <span class="badge text-white" style="background-color: <?= htmlspecialchars($product['color']) ?>;">
                            <?= htmlspecialchars($product['sales_name']) ?>
                        </span>
                    </p>

                <?php else: ?>
                    <p class="text-muted">Price: <span class="text-black">$<?= number_format($product['price'], 0) ?></span></p>
                <?php endif; ?>
                <p class="text-muted">Brand: <span class="text-black"><?= htmlspecialchars($product['brands']) ?></span></p>
                <p class="text-muted">Gender: <span class="text-black"><?= htmlspecialchars($product['gender']) ?></span></p>
                <p class="text-muted">About this product: <span class="text-black"><?= htmlspecialchars($product['desc']) ?></span></p>
                <!-- Quantity Product -->
                <div class="quantity-container">
                    <p class="text-muted">Quantity:</p>
                    <div class="d-flex justify-content-start align-items-start">
                        <button id="decrease" class="btn btn-danger btn-quantity">-</button>
                        <p id="quantity" class="text-muted h4 mx-3">1</p>
                        <button id="increase" class="btn btn-success btn-quantity">+</button>
                    </div>
                </div>
                <!-- Available Sizes -->
                <p class="text-muted">Available Sizes:</p>
                <div class="d-flex gap-2 mb-3">
                    <input type="radio" name="size" id="size7" class="btn-check" autocomplete="off">
                    <label for="size7" class="btn btn-outline-dark">7</label>
                    <input type="radio" name="size" id="size8" class="btn-check" autocomplete="off">
                    <label for="size8" class="btn btn-outline-dark">8</label>
                    <input type="radio" name="size" id="size9" class="btn-check" autocomplete="off">
                    <label for="size9" class="btn btn-outline-dark">9</label>
                    <input type="radio" name="size" id="size10" class="btn-check" autocomplete="off">
                    <label for="size10" class="btn btn-outline-dark">10</label>
                </div>
                <!-- Available Colors -->
                <p class="text-muted">Available Colors:</p>
                <div class="d-flex gap-2 mb-4">
                    <?php foreach ($color_images as $entry): ?>
                        <input type="radio" name="color" id="color<?= htmlspecialchars($entry['color']) ?>"
                            class="btn-check"
                            autocomplete="off"
                            onclick="changeColorAndLoadImages('<?= htmlspecialchars($entry['color']) ?>', '<?= htmlspecialchars($entry['img']) ?>');">
                        <label for="color<?= htmlspecialchars($entry['color']) ?>"
                            class="color-circle"
                            style="background-color: <?= htmlspecialchars($entry['color']) ?>;"></label>
                    <?php endforeach; ?>
                </div>
                <!-- Call to Action -->
                <button onclick="buyNow(<?= htmlspecialchars($product['id']) ?>)"
                    class="btn btn-dark">Buy Now</button>
                <button
                    class="btn btn-primary  gap-2"
                    onclick="addToCart(<?= htmlspecialchars($product['id']) ?>)">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>

        <!-- Button to Trigger Modal -->
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#commentModal">Comments</button>
        </div>

        <!-- Modal for Comments -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Customer Comments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Display Comments -->
                        <h6 class="mb-3">Comments:</h6>
                        <ul class="list-group mb-3">
                            <?php foreach ($comments as $comment): ?>
                                <?php
                                // Tách phần rating và tên người dùng
                                preg_match('/(\d+☆)?(.*)/', $comment['user'], $matches);
                                $rating = isset($matches[1]) ? $matches[1] : '';
                                $username = isset($matches[2]) ? trim($matches[2]) : $comment['user'];
                                ?>
                                <li class="list-group-item">
                                    <?php if ($rating): ?>
                                        <div class="rating">
                                            <?php
                                            // Hiển thị sao vàng
                                            $stars = intval($rating);
                                            echo str_repeat('⭐', $stars);
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <strong><?= htmlspecialchars($username) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <style>
                            .star-rating {
                                display: flex;
                                justify-content: flex-start;
                                cursor: pointer;
                                font-size: 2rem;
                                /* Kích thước biểu tượng */
                                gap: 0.2rem;
                            }

                            .star-rating .bi {
                                color: #ccc;
                                /* Màu sao rỗng */
                                transition: color 0.2s;
                            }

                            .star-rating .bi.filled {
                                color: gold;
                                /* Màu sao đã chọn */
                            }
                        </style>
                        <!-- Form to Add Comment -->
                        <!-- Form to Add Comment method="POST" action="submit_comment.php" -->

                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>
                            <div class="star-rating" id="starRating">
                                <!-- Bootstrap Icons -->
                                <i class="bi bi-star" data-value="1"></i>
                                <i class="bi bi-star" data-value="2"></i>
                                <i class="bi bi-star" data-value="3"></i>
                                <i class="bi bi-star" data-value="4"></i>
                                <i class="bi bi-star" data-value="5"></i>
                            </div>
                            <!-- Input ẩn để lưu giá trị rating -->
                            <input type="hidden" name="rating" id="ratingInput" value="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Comment</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button id="submitComment" class="btn btn-primary">Submit Comment</button>
                    </div>
                    <div class="modal-footer">
                        <button id="closeCommentModal" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const stars = document.querySelectorAll('#starRating .bi');
                            const ratingInput = document.getElementById('ratingInput');

                            stars.forEach(star => {
                                // Khi di chuột qua ngôi sao
                                star.addEventListener('mouseover', function() {
                                    const value = parseInt(this.getAttribute('data-value'));
                                    highlightStars(value);
                                });

                                // Khi nhấn vào ngôi sao
                                star.addEventListener('click', function() {
                                    const value = parseInt(this.getAttribute('data-value'));
                                    ratingInput.value = value; // Gán giá trị rating vào input hidden
                                });

                                // Khi di chuột ra ngoài
                                star.addEventListener('mouseout', function() {
                                    resetStars(ratingInput.value);
                                });
                            });

                            function highlightStars(value) {
                                stars.forEach(star => {
                                    const starValue = parseInt(star.getAttribute('data-value'));
                                    if (starValue <= value) {
                                        star.classList.add('filled'); // Đổi màu sao
                                    } else {
                                        star.classList.remove('filled');
                                    }
                                });
                            }

                            function resetStars(value) {
                                highlightStars(value); // Khôi phục trạng thái đã chọn
                            }
                        });
                        // Xử lý khi nhấn nút Submit
                        document.getElementById('submitComment').addEventListener('click', function() {
                            const username = <?php echo json_encode($currentUsername); ?>;
                            const comment = document.getElementById('comment').value;
                            const rating = ratingInput.value;
                            const product_id = <?php echo json_encode($product_id); ?>;

                            // Kiểm tra các trường dữ liệu
                            if (!username || !comment || rating === "0") {
                                alert('Please fill in all fields and provide a rating.');
                                return;
                            }

                            // Tạo bình luận mới
                            const comment_for_update = rating + "☆" + username + ": " + comment;

                            // Ghi ra console
                            console.log("product_id:", product_id);
                            console.log('Comment:', comment_for_update);

                            // Gửi yêu cầu cập nhật bình luận
                            fetch('update_comment.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        product_id: product_id,
                                        new_comment: comment_for_update,
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Comment added successfully!');
                                        // Đóng modal bằng ID
                                        document.getElementById('closeCommentModal').click();

                                        // Reload the page to refresh the comments
                                        location.reload();

                                    } else {
                                        alert('Failed to add comment: ' + data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while adding the comment.');
                                });

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <?php
    include('../homepage/database.php');

    // Get the product ID from URL
    $product_id = intval($_GET['id']); // Ensure the ID is an integer

    // Fetch the current product details
    $current_product_query = "SELECT brands, gender, idSale FROM products WHERE id = $product_id";
    $current_product_result = mysqli_query($conn, $current_product_query);

    if ($current_product_result && $current_product_row = mysqli_fetch_assoc($current_product_result)) {
        $currentBrands = $current_product_row['brands'];
        $currentGender = $current_product_row['gender'];
        $currentIdSale = $current_product_row['idSale'];
    } else {
        die("Product not found");
    }

    // Query to fetch related products
    $sql = "
(SELECT 
    p.id, 
    p.name AS product_name, 
    p.img, 
    p.price, 
    s.discount,
    s.color AS sale_color
FROM 
    products p
LEFT JOIN 
    sales s ON p.idSale = s.id
WHERE 
    p.id != $product_id AND p.brands = '$currentBrands'
LIMIT 5)

UNION ALL

(SELECT 
    p.id, 
    p.name AS product_name, 
    p.img, 
    p.price, 
    s.discount,
    s.color AS sale_color
FROM 
    products p
LEFT JOIN 
    sales s ON p.idSale = s.id
WHERE 
    p.id != $product_id AND p.gender = '$currentGender'
      AND p.id NOT IN (
          SELECT id 
          FROM products 
          WHERE brands = '$currentBrands'
      )
LIMIT 5)

UNION ALL

(SELECT 
    p.id, 
    p.name AS product_name, 
    p.img, 
    p.price, 
    s.discount,
    s.color AS sale_color
FROM 
    products p
LEFT JOIN 
    sales s ON p.idSale = s.id
WHERE 
    p.id != $product_id AND p.idSale = $currentIdSale
      AND p.id NOT IN (
          SELECT id 
          FROM products 
          WHERE brands = '$currentBrands'
          OR gender = '$currentGender'
      )
LIMIT 5)

UNION ALL

(SELECT 
    p.id, 
    p.name AS product_name, 
    p.img, 
    p.price, 
    s.discount,
    s.color AS sale_color
FROM 
    products p
LEFT JOIN 
    sales s ON p.idSale = s.id
WHERE 
    p.id != $product_id
      AND p.id NOT IN (
          SELECT id 
          FROM products 
          WHERE brands = '$currentBrands'
          OR gender = '$currentGender'
          OR idSale = $currentIdSale
      )
ORDER BY RAND()
LIMIT 5)
";

    // Execute the query
    $result = mysqli_query($conn, $sql);
    $related_products = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Kiểm tra giá trị discount, mặc định là 0 nếu không tồn tại
            $row['discount'] = isset($row['discount']) ? floatval($row['discount']) : 0;

            // Tính toán giá sau giảm
            $row['sale_price'] = ($row['discount'] > 0)
                ? $row['price'] * (1 - $row['discount'] / 100)
                : $row['price'];

            // Thêm sản phẩm vào mảng liên quan
            $related_products[] = $row;
        }
    }


    ?>
    <div style="font-size: 30px; font-family: 'Nunito', sans-serif" class="text-center mb-3 animate__animated animate__fadeIn ">Similar Products</div>
    <div class="similar-products-container position-relative">
        <!-- Left Arrow -->
        <button class="btn btn-outline-dark position-absolute top-50 start-0 translate-middle-y arrow-btn" id="prevProduct">&#60;</button>

        <div class="similar-products d-flex overflow-auto">
            <?php if (!empty($related_products)) : ?>
                <?php foreach ($related_products as $related) : ?>
                    <?php
                    // Extract the first color<>img from the related['img']
                    $raw_images = explode(',', $related['img']);
                    if (!empty($raw_images[0])) {
                        [$color, $image] = explode('<>', $raw_images[0], 2);
                    } else {
                        $color = '#ccc';
                        $image = 'default.png';
                    }
                    ?>
                    <div class="product-card p-3 text-center position-relative"
                        style="cursor: pointer;"
                        onclick="window.location.href='product.php?id=<?php echo $related['id']; ?>'">

                        <!-- Sale Tag -->
                        <?php if ($related['discount'] > 0) : ?>
                            <span style="background-color: <?= htmlspecialchars($related['sale_color']) ?>;" class="badge position-absolute top-0 end-0 m-2" style="font-size: 0.9rem;">-<?= intval($related['discount']) ?>%</span>
                        <?php endif; ?>

                        <img src="../../Admin/img/<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($related['product_name']) ?>"
                            class="img-fluid rounded mt-2">

                        <p style="padding-left: 1rem; max-width: 15rem; white-space: normal; word-wrap: break-word; overflow-wrap: break-word; text-align: center;" class="mt-2">
                            <?= htmlspecialchars($related['product_name']) ?>
                        </p>

                        <!-- Price Display -->
                        <div class="price-container">
                            <?php if ($related['discount'] > 0) : ?>
                                <span class="text-muted text-decoration-line-through">$<?= number_format($related['price'], 2) ?></span>
                                <span class="text-danger ms-2"><?= number_format($related['sale_price'], 2) ?> VND</span>
                            <?php else : ?>
                                <span class="text-dark"><?= number_format($related['price'], 2) ?> VND</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-center text-muted">No similar products found.</p>
            <?php endif; ?>
        </div>

        <!-- Right Arrow -->
        <button class="btn btn-outline-dark position-absolute top-50 end-0 translate-middle-y arrow-btn" id="nextProduct">&#62;</button>
    </div>




    </div>
    <script>
        // JavaScript for sliding the similar products
        document.getElementById('prevProduct').addEventListener('click', function() {
            const container = document.querySelector('.similar-products');
            const firstCard = container.querySelector('.product-card');
            const cardWidth = firstCard.offsetWidth + parseInt(window.getComputedStyle(firstCard).marginRight);

            // Slide to the left
            container.scrollBy({
                left: -cardWidth, // Scroll by the width of one card
                behavior: 'smooth' // Smooth scrolling
            });
        });

        document.getElementById('nextProduct').addEventListener('click', function() {
            const container = document.querySelector('.similar-products');
            const firstCard = container.querySelector('.product-card');
            const cardWidth = firstCard.offsetWidth + parseInt(window.getComputedStyle(firstCard).marginRight);

            // Slide to the right
            container.scrollBy({
                left: cardWidth, // Scroll by the width of one card
                behavior: 'smooth' // Smooth scrolling
            });
        });
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>

<style>
    /* Styling for the radio buttons */
    input[type="radio"].btn-check:checked+label {
        background-color: #007bff;
        color: white;
    }

    input[type="radio"].btn-check:checked+label .color-circle {
        border: 2px solid #007bff;
    }

    /* Ensure the radio buttons are hidden and use labels for styling */
    input[type="radio"].btn-check {
        display: none;
    }

    input[type="radio"].btn-check+label {
        cursor: pointer;
    }

    input[type="radio"].btn-check+label:hover {
        background-color: #f8f9fa;
    }

    /* Custom color circle for radio buttons */
    .color-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #ccc;
        display: inline-block;
    }


    .similar-products-container {
        position: relative;
    }

    .similar-products {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        white-space: nowrap;
        max-width: calc(300px * 5);
        /* Limit to show 5 products */
    }

    .product-card {
        width: 300px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }

    .product-radio {
        display: block;
        margin-top: 10px;
    }

    .arrow-btn {
        font-size: 24px;
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 10px;
    }

    .arrow-btn:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    /* Styling for the arrows */
    .arrow-btn:focus {
        outline: none;
    }



    body {
        font-family: Arial, sans-serif;
    }

    .gallery {
        display: flex;
        overflow-x: auto;
        gap: 1rem;
        white-space: nowrap;
        max-width: calc(200px * 4);
        /* Limit the visible images to 4 */
    }

    .gallery .col-6 {
        flex-shrink: 0;
        /* Prevent the images from shrinking */
    }

    .gallery img {
        width: 100%;
        /* Make sure images are responsive */
    }

    /* Responsive styles */
    @media (min-width: 600px) {

        #main-image {
            min-height: 467px;
            min-width: 240px;
        }

    }

    @media (min-width: 900px) {

        #main-image {
            min-height: 240px;
            min-width: 467px;
        }

    }

    .quantity-container {
        padding-bottom: 10px;
        text-align: left;
    }

    .btn-quantity {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
</style>

<script>
    // Optional: To dynamically update modal content if needed
    document.querySelector('[data-bs-toggle="modal"]').addEventListener('click', () => {
        const commentModalBody = document.querySelector('.modal-body p');
        commentModalBody.textContent = 'Customer Comments: Amazing product! Very comfortable.';
    });
    // Listen for changes in the size selection
    document.querySelectorAll('input[name="size"]').forEach(input => {
        input.addEventListener('change', function() {
            console.log(`Selected size: ${this.id.replace('size', '')}`);
        });
    });

    // Listen for changes in the color selection
    document.querySelectorAll('input[name="color"]').forEach(input => {
        input.addEventListener('change', function() {
            console.log(`Selected color: ${this.id.replace('color', '')}`);
        });
    });
</script>
<script>
    let quantity = 1;

    function updateQuantityDisplay() {
        document.getElementById('quantity').innerText = quantity;
    }

    document.getElementById('increase').addEventListener('click', function() {
        quantity++;
        updateQuantityDisplay();
    });

    document.getElementById('decrease').addEventListener('click', function() {
        if (quantity > 1) {
            quantity--;
            updateQuantityDisplay();
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Wait for 1 second
        setTimeout(() => {
            // Find the first color radio button
            const firstColorInput = document.querySelector('input[name="color"]');
            if (firstColorInput) {
                firstColorInput.checked = true; // Select the first color
                firstColorInput.click(); // Trigger the click event to execute the associated function
                console.log(`Selected color: ${firstColorInput.id.replace('color', '')}`);
            }
        }, 100); // 1000ms = 1 second
    });

    let sizeOfProduct = null;
    document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.addEventListener('change', () => {
            sizeOfProduct = radio.id.replace('size', '');
        });
    });

    function addToCart(productId) {
        let currentIdCart = <?php echo $idCart ?>;
        let color = selectedColor;
        // Dữ liệu gửi tới server
        const data = {
            idproduct: productId,
            idcart: currentIdCart, // Mặc định id cart là 7
            quantity: quantity,
            color: color,
            size: sizeOfProduct,
            isPay: 'no'
        };
        console.log(data);
        // Gửi dữ liệu qua fetch API
        fetch('addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Hiển thị hiệu ứng SweetAlert khi thành công
                    // animateCartIcon();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'The product has been added to your cart.',
                        showConfirmButton: true
                    });
                } else {
                    console.error(result.message);
                    // Thông báo lỗi
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to add product to the cart. Please try again.',
                        showConfirmButton: true
                    });
                }
            })
            .catch(error => {
                //<br />
                //<b>"... is not valid JSON
                //tôi muốn xem toàn bộ trong ...
                console.error('Error:', error);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'The product has been added to your cart.',
                    showConfirmButton: true
                });
            });
    }

    function buyNow(productId) {
        let currentIdCart = <?php echo $idCart ?>;
        let color = selectedColor;
        // Dữ liệu gửi tới server
        const data = {
            idproduct: productId,
            idcart: currentIdCart, // Mặc định id cart là 7
            quantity: quantity,
            color: color,
            size: sizeOfProduct,
            isPay: 'yes'
        };
        console.log(data);
        // Gửi dữ liệu qua fetch API
        fetch('addToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Hiển thị hiệu ứng SweetAlert khi thành công
                    window.location.href = `../pay/checkpage.php?cart_id=<?php echo $idCart; ?>&total=${<?= $current_buy_now_price ?>}`;
                } else {
                    console.error(result.message);
                    // Thông báo lỗi
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to buy product. Please try again.',
                        showConfirmButton: true
                    });
                }
            })
            .catch(error => {
                //<br />
                //<b>"... is not valid JSON
                //tôi muốn xem toàn bộ trong ...
                console.error('Error:', error);
            });
    }

    function animateCartIcon() {
        const cartIcon = document.querySelector('.bi-cart');
        cartIcon.classList.add('animate__animated', 'animate__tada');
        setTimeout(() => {
            cartIcon.classList.remove('animate__animated', 'animate__tada');
        }, 1000);
    }
</script>