<?php
// Include database connection
include('database.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $productName = $_POST['productName'];
  $productDescription = $_POST['productDescription'];
  $productPrice = $_POST['productPrice'];
  $productBrand = $_POST['Brands'];
  $currentImg = $_POST['currentImg'];
  $productGender = $_POST['productGender']; // Retrieve gender from form

  // Prepare the SQL statement
  $stmt = $conn->prepare("INSERT INTO products (name, `desc`, price, brands, img, gender) VALUES (?, ?, ?, ?, ?, ?)");

  // Check if prepare was successful
  if (!$stmt) {
    die("Prepare failed: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ssisss", $productName, $productDescription, $productPrice, $productBrand, $currentImg, $productGender);

  // Execute the statement
  if ($stmt->execute()) {
    header("Location: success.php");
    exit();
  } else {
    echo "Error inserting product: " . $stmt->error;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Product</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="styleimgCutter.css">

</head>
<style>
  /* Modal Styles */
  .brads-modal {
    display: none;
    position: fixed;
    z-index: 999;
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
  }

  @media (min-width: 900px) {
    .brads-modal-content {
      width: 50%;
    }
  }

  /* Slideshow container styling */
  .slideshow-container {
    max-width: 100%;
    position: relative;
    width: 500px;
    height: 450px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ddd;
  }

  /* Individual slide styling */
  .slide {
    display: none;
    position: absolute;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    transition: opacity 0.6s ease;
  }

  /* Image styling */
  .slide img {
    max-width: 100%;
    max-height: 100%;
    position: relative;
    z-index: 2;
  }

  /* Circle behind image */
  .circle {
    position: absolute;
    width: 20vw;
    /* Adjusts width relative to viewport width */
    height: 20vw;
    /* Keeps the element a perfect circle */
    max-width: 300px;
    /* Limits the maximum size on larger screens */
    max-height: 300px;
    /* Limits the maximum size on larger screens */
    background: var(--dynamic-color);
    border-radius: 50%;
    z-index: 1;
    animation: circle-expand 0.6s ease;
  }

  /* Adjust circle size for smaller screens */
  @media (max-width: 768px) {
    .circle {
      width: 30vw;
      /* Larger size for small screens */
      height: 30vw;
      max-width: 200px;
      /* Smaller max width limit */
      max-height: 200px;
    }
  }

  @media (max-width: 480px) {
    .circle {
      width: 40vw;
      /* Larger size for extra small screens */
      height: 40vw;
      max-width: 150px;
      /* Smaller max width limit */
      max-height: 150px;
    }

    .slideshow-container {
      width: 500px;
      height: 250px;
    }
  }

  @keyframes circle-expand {
    0% {
      transform: scale(0.5);
      opacity: 0;
    }

    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  /* Radio button controls */
  .radio-controls {
    display: flex;
    justify-content: center;
    margin-top: 10px;
  }

  .radio-controls input[type="radio"] {
    display: none;
  }

  .radio-label {
    width: 12px;
    height: 12px;
    margin: 0 5px;
    background: #bbb;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .radio-controls input[type="radio"]:checked+.radio-label {
    background: #333;
  }

  /* Close button styling */
  .close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    background-color: red;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    z-index: 3;
  }
</style>

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
                    <li><a href="#">Send voucher to user</a></li>
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
                    <input type="text" placeholder="Search...">
                    <i class='bx bx-search icon'></i>
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

    <!-- MAIN -->
    <main>
      <h1 class="title">Product</h1>
      <ul class="breadcrumbs">
        <li><a href="#">Product</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Add Product</a></li>
      </ul>
      <div class="container my-5">
        <div class="row align-items-center">
          <!-- Left Column: Image -->
          <div class="col-md-6">
            <div class="slideshow-container">
              <div class="slide">
                <div style="--dynamic-color:lightgrey" class="circle CnoShoes"></div>
                <img src="img/noshoes.png" alt="Slide 1" class="noShoes">
              </div>
            </div>

            <!-- Radio Button Controls -->
            <div class="radio-controls">
              <input style="visibility: hidden;" type="radio" name="slider" id="slide1" checked>
              <label style="visibility: hidden;" for="slide1" class="radio-label"></label>
            </div>
          </div>


          <!-- Right Column: Form -->
          <div class="col-md-6 pt-5">
            <h2 class="mb-4">Add New Product</h2>
            <form action="addnewProduct.php" method="post" enctype="multipart/form-data" id="productForm">
              <div class="mb-3">
                <input type="hidden" name="currentImg" id="currentImgField">
                <input type="hidden" name="Brands" id="BrandsField">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
              </div>
              <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" name="productDescription" rows="2" required></textarea>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" required>
              </div>
               <!-- Gender Dropdown -->
               <div class="mb-3">
                <label for="productGender" class="form-label">Gender</label>
                <select class="form-control" id="productGender" name="productGender" required>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Unisex">Unisex</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="productBrands" class="form-label">Select Brands</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="selectedBrandButton" placeholder="No brand chosen" readonly>
                  <button style="z-index: 0;" type="button" class="btn btn-secondary" onclick="bradsOpenModal()">Choose Brand</button>
                </div>
              </div>
              <div class="mb-3">
                <label for="productImage" class="form-label">Upload Color</label>
                <div class="input-group">
                  <input data-toggle="modal" data-target="#cutterModal" type="text" class="form-control" id="filePlaceholder" placeholder="No file chosen" readonly>
                </div>
                <input type="file" id="productImage" name="productImage" style="display: none;" onchange="updatePlaceholder()">
              </div>
              <button onclick="setHiddenFields()" type="submit" class="btn btn-primary">Add Product</button>
            </form>
          </div>
        </div>
      </div>
    </main>
    <!-- MAIN -->
  </section>
  <!-- NAVBAR -->

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="script.js"></script>
  <div class="image-cutter-app">
    <!-- Modal for Image Cutter -->
    <div class="modal fade" id="cutterModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="cutterModalLabel"
      aria-hidden="true">
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
  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
<script>
  let currentImg = "";
  let selectedBrand = "";

  function setHiddenFields() {
    document.getElementById("currentImgField").value = currentImg;
    document.getElementById("BrandsField").value = selectedBrand;
  }
</script>
<script>
  // JavaScript for automatic slide change based on radio button
  let slides = Array.from(document.querySelectorAll(".slide"));
  let radios = Array.from(document.querySelectorAll(".radio-controls input[type='radio']"));

  radios.forEach((radio, index) => {
    radio.addEventListener("change", () => {
      slides.forEach((slide) => slide.style.display = "none");
      slides[index].style.display = "flex";
    });
  });

  // Initialize to display the first slide
  slides[0].style.display = "flex";
</script>
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

      // Update the input placeholder text to the selected brand name
      document.querySelector('#selectedBrandButton').value = selectedBrand;
      alert('You selected: ' + selectedBrand);
      bradsCloseModal();
    } else {
      alert('Please select at least one brand.');
    }
  }
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
  function removeColor(s, color) {
    // Chuyển chuỗi `s` thành mảng các mục dựa trên dấu phẩy
    let items = s.split(',');

    // Lọc bỏ các mục có chứa màu cần loại bỏ
    items = items.filter(item => !item.startsWith(color + '<>'));

    // Ghép lại các mục còn lại thành chuỗi
    return items.join(',');
  }

  function deleteImage(imgtoDelete) {
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
  // Function to ensure the last radio button is always checked
  function checkLastRadioButton() {
    const radioButtons = document.querySelectorAll('input[type="radio"][name="slider"]');
    if (radioButtons.length > 0) {
      // Set a 1-second delay before checking the last radio button
      setTimeout(() => {
        const lastRadioButton = radioButtons[radioButtons.length - 1];
        lastRadioButton.checked = true;

        // Trigger the change event
        const changeEvent = new Event('change', {
          bubbles: true
        });
        lastRadioButton.dispatchEvent(changeEvent);
      }, 50); // 1000 milliseconds = 1 second
    }
  }
  // script.js
  let new_color_add_to_current_Product = colorPicker.value;

  const imageUpload = document.getElementById('imageUpload');
  const uploadedImage = document.getElementById('uploadedImage');
  const originImage = document.getElementById('originImage');
  const cropButton = document.getElementById('cropButton');
  const canvas = document.getElementById('canvas');
  const moveLeft = document.getElementById('moveLeft');
  const moveRight = document.getElementById('moveRight');
  const moveUp = document.getElementById('moveUp');
  const moveDown = document.getElementById('moveDown');
  const increaseWidth = document.getElementById('increaseWidth');
  const decreaseWidth = document.getElementById('decreaseWidth');
  const increaseHeight = document.getElementById('increaseHeight');
  const decreaseHeight = document.getElementById('decreaseHeight');

  let offsetX, offsetY;

  // Handle image upload
  imageUpload.addEventListener('change', function(event) {
    console.log(colorPicker.value);
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        uploadedImage.src = e.target.result;
        uploadedImage.style.display = 'block';
        uploadedImage.style.left = '0px';
        uploadedImage.style.top = '0px';
        uploadedImage.style.width = '100%';
        uploadedImage.style.height = 'auto';
      };
      reader.readAsDataURL(file);
    }
  });

  // Enable dragging
  uploadedImage.addEventListener('mousedown', function(e) {
    offsetX = e.offsetX;
    offsetY = e.offsetY;
    document.addEventListener('mousemove', moveImage);
  });
  document.addEventListener('mouseup', function() {
    document.removeEventListener('mousemove', moveImage);
  });

  function moveImage(e) {
    uploadedImage.style.left = (e.pageX - offsetX - originImage.offsetLeft) + 'px';
    uploadedImage.style.top = (e.pageY - offsetY - originImage.offsetTop) + 'px';
  }

  // Crop and apply mask
  cropButton.addEventListener('click', function(event) {
    // Prevent default if within a form or default action might trigger
    document.querySelector('.noShoes').style.visibility = 'hidden';
    document.querySelector('.CnoShoes').style.visibility = 'hidden';
    event.preventDefault();

    canvas.width = originImage.width;
    canvas.height = originImage.height;
    const context = canvas.getContext('2d');

    // Draw the origin image to create a mask
    context.drawImage(originImage, 0, 0, originImage.width, originImage.height);
    context.globalCompositeOperation = 'source-in';

    // Calculate the position of the uploaded image relative to the origin
    const rect = uploadedImage.getBoundingClientRect();
    const originRect = originImage.getBoundingClientRect();
    const offsetX = rect.left - originRect.left;
    const offsetY = rect.top - originRect.top;

    // Draw the uploaded image using the mask
    context.drawImage(uploadedImage, offsetX, offsetY, rect.width, rect.height);

    // Generate the masked image and allow download
    canvas.toBlob(blob => {
      const randomId = Math.floor(1000 + Math.random() * 9000);
      const dynamicColor = colorPicker.value;
      const imageName = `cropped${randomId}.png`;
      const formData = new FormData();
      // Check if `new_color_add_to_current_Product` has a value, then append the new pair
      if (currentImg) {
        currentImg += `,${dynamicColor}<>${imageName}`;
      } else {
        currentImg = `${dynamicColor}<>${imageName}`;
      }
      console.log(currentImg);
      formData.append('image', blob, imageName);

      fetch('uploadimg.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Image uploaded successfully');

            // Add new slide element
            const slideshowContainer = document.querySelector('.slideshow-container');
            const newSlide = document.createElement('div');
            newSlide.classList.add('slide');
            newSlide.style.display = 'none'; // Initially hidden

            // Circle with dynamic color
            const circleDiv = document.createElement('div');
            circleDiv.classList.add('circle');
            circleDiv.style.setProperty('--dynamic-color', dynamicColor);

            // Image element
            console.log("imgname" + imageName); //imgnameundefined ???
            const imgElement = document.createElement('img');
            imgElement.src = 'img/' + imageName;
            imgElement.alt = 'New Slide';
            // Create close button
            const closeButton = document.createElement('button');
            closeButton.classList.add('close-button');
            closeButton.textContent = '-';
            //thêm chức năng xóa slide hiện tại cho close button
            // Append circle and image to slide
            newSlide.appendChild(circleDiv);
            newSlide.appendChild(imgElement);
            newSlide.appendChild(closeButton);
            slideshowContainer.appendChild(newSlide);

            // Add radio button control
            const radioControls = document.querySelector('.radio-controls');
            const newRadio = document.createElement('input');
            newRadio.type = 'radio';
            newRadio.name = 'slider';
            newRadio.id = 'slide' + (slides.length + 1); // Unique ID for each slide
            // Corresponding label for the new radio button
            const newLabel = document.createElement('label');
            newLabel.classList.add('radio-label');
            newLabel.setAttribute('for', newRadio.id);

            // Append radio button and label to controls
            radioControls.appendChild(newRadio);
            radioControls.appendChild(newLabel);

            // Update slides and radio buttons to activate new slide when clicked
            slides.push(newSlide);
            radios.push(newRadio);

            // Event listener to show the selected slide
            newRadio.addEventListener("change", () => {
              slides.forEach(slide => slide.style.display = "none");
              newSlide.style.display = "flex"; // Show the new slide
            });
            checkLastRadioButton();
            closeButton.addEventListener("click", () => {
              console.log("img element " + imgElement.src);
              newSlide.remove();
              newRadio.remove();
              newLabel.remove();

              // Update arrays
              slides = slides.filter(slide => slide !== newSlide);
              radios = radios.filter(radio => radio !== newRadio);
              console.log(slides.length);
              // Show previous slide if available
              if (slides.length > 1) {
                const newIndex = slides.length - 1;
                slides.forEach(slide => slide.style.display = "none");
                slides[newIndex].style.display = "flex";
                radios[newIndex].checked = true;
              } else {
                document.querySelector('.noShoes').style.visibility = 'visible';
                document.querySelector('.CnoShoes').style.visibility = 'visible';
              }
              let result = imgElement.src.substring(imgElement.src.indexOf("img/"));
              currentImg = removeColor(currentImg, dynamicColor);
              deleteImage(result);
              checkLastRadioButton();
            });
          } else {
            alert('Image upload failed: ' + data.message);
            console.error('Image upload failed:', data.message);
          }
        })
        .catch(error => {
          alert('Error occurred during upload: ' + error.message);
          console.error('Error:', error);
        });
    });
  });

  // Move controls
  moveLeft.addEventListener('click', () => moveUploaded(-5, 0));
  moveRight.addEventListener('click', () => moveUploaded(5, 0));
  moveUp.addEventListener('click', () => moveUploaded(0, -5));
  moveDown.addEventListener('click', () => moveUploaded(0, 5));

  function moveUploaded(x, y) {
    uploadedImage.style.left = (parseInt(uploadedImage.style.left || 0) + x) + 'px';
    uploadedImage.style.top = (parseInt(uploadedImage.style.top || 0) + y) + 'px';
  }

  // Resize controls with unlimited scaling
  increaseWidth.addEventListener('click', () => resizeUploaded(10, 0));
  decreaseWidth.addEventListener('click', () => resizeUploaded(-10, 0));
  increaseHeight.addEventListener('click', () => resizeUploaded(0, 10));
  decreaseHeight.addEventListener('click', () => resizeUploaded(0, -10));

  function resizeUploaded(widthChange, heightChange) {
    const newWidth = uploadedImage.offsetWidth + widthChange;
    const newHeight = uploadedImage.offsetHeight + heightChange;
    uploadedImage.style.width = newWidth + 'px';
    uploadedImage.style.height = newHeight + 'px';
  }
</script>
<script src="script.js"></script>

</html>