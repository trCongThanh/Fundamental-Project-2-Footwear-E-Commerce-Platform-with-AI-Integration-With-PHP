<?php
include('../database.php');

// Truy vấn thông tin đơn hàng
$sql = "SELECT id, status, total_payment, order_date, delivery_date, address, idUser FROM orders";
$result = mysqli_query($conn, $sql);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Đóng kết nối
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        td,
        thead {
            text-align: center;
            /* Căn giữa nội dung */
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
            <li><a href="#"><i class='bx bx-cart-alt icon'></i> Orders</a></li>
            <li>
                <a href="#"><i class='bx bxs-purchase-tag-alt icon'></i> Sales <i class='bx bx-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="../phpSales/salesView.php">View Sales</a></li>
                    <li><a href="../phpSales/addSalesToProduct.php">Add sales to product</a></li>
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
                    <input id="searchInputSuggest" type="text" placeholder="Search...">
                    <div id="suggestionsContainer" class="suggestions-container"></div>
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
        <style>
            .suggestions-container {
                position: absolute;
                top: 50px;
                /* Adjust depending on your layout */
                left: 0;
                width: 100%;
                background-color: #fff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                z-index: 1000;
            }

            .suggestion-item {
                padding: 10px;
                cursor: pointer;
                font-size: 16px;
            }

            .suggestion-item:hover {
                background-color: #f0f0f0;
            }
        </style>
        <script>
            document.getElementById('searchInputSuggest').addEventListener('input', function() {
                let query = this.value.toLowerCase();
                let suggestions = [{
                        name: "Dashboard",
                        url: "index.php"
                    },
                    {
                        name: "Add New User",
                        url: "phpUser/addnewUser.php"
                    },
                    {
                        name: "User View",
                        url: "phpUser/userView.php"
                    },
                    {
                        name: "View Product",
                        url: "productView.php"
                    },
                    {
                        name: "Add Product",
                        url: "addnewProduct.php"
                    },
                    {
                        name: "View Sales",
                        url: "phpSales/salesView.php"
                    },
                    {
                        name: "Add sales to product",
                        url: "phpSales/addSalesToProduct.php"
                    }
                ];
                let filteredSuggestions = suggestions.filter(suggestion => suggestion.name.toLowerCase().includes(query));

                // Clear previous suggestions
                let suggestionsContainer = document.getElementById('suggestionsContainer');
                suggestionsContainer.innerHTML = '';

                // Show matching suggestions
                filteredSuggestions.forEach(suggestion => {
                    let suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('suggestion-item');
                    suggestionItem.textContent = suggestion.name;
                    suggestionItem.dataset.url = suggestion.url;
                    suggestionItem.addEventListener('click', function() {
                        window.location.href = suggestionItem.dataset.url;
                    });
                    suggestionsContainer.appendChild(suggestionItem);
                });
            });

            // Handle 'Enter' key press to go to the first suggestion
            document.getElementById('searchInputSuggest').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    let firstSuggestion = document.querySelector('.suggestion-item');
                    if (firstSuggestion) {
                        window.location.href = firstSuggestion.dataset.url;
                    }
                }
            });
        </script>
        <!-- NAVBAR -->
        <div class="container mt-5">
            <h1 class="text-end animate__animated animate__fadeInDown">Manage Orders</h1>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ID User</th>
                            <th>Status</th>
                            <th>Total Payment</th>
                            <th>Order Date</th>
                            <th>Delivery Date</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['idUser']; ?></td>
                                <td><?php echo $order['status']; ?></td>
                                <td><?php echo number_format($order['total_payment'], 0, '.', ','); ?> VND</td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo $order['delivery_date']; ?></td>
                                <td><?php echo $order['address']; ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm change-status-btn mb-2" data-id="<?php echo $order['id']; ?>" data-status="<?php echo $order['status']; ?>">Change Status</button>
                                    <button class="btn btn-info btn-sm view-details-btn" data-id="<?php echo $order['id']; ?>"> &nbsp;View Details&nbsp;</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Change Status functionality
            document.querySelectorAll('.change-status-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const orderId = button.getAttribute('data-id');
                    const currentStatus = button.getAttribute('data-status');

                    Swal.fire({
                        title: 'Change Order Status',
                        html: `
        <select id="status-select" class="form-select">
            <option value="On Delivery" ${currentStatus === 'OnDelivery' ? 'selected' : ''}>On Delivery</option>
            <option value="Processing" ${currentStatus === 'Processing' ? 'selected' : ''}>Processing</option>
            <option value="Aborted" ${currentStatus === 'Aborted' ? 'selected' : ''}>Aborted</option>
            <option value="Delivered" ${currentStatus === 'Delivered' ? 'selected' : ''}>Delivered</option>
        </select>
        <textarea id="status-message" class="form-control mt-3" rows="3" placeholder="Message to customer..."></textarea>
        <button class="btn btn-success mt-3" id="auto-fill">Auto-fill Delivered Message</button>
    `,
                        showCancelButton: true,
                        confirmButtonText: 'Save',
                        preConfirm: () => {
                            const statusSelect = document.getElementById('status-select');
                            const statusMessage = document.getElementById('status-message');
                            if (!statusSelect || !statusMessage) {
                                Swal.showValidationMessage('Missing input elements!');
                                return false;
                            }
                            const status = statusSelect.value;
                            const message = statusMessage.value;
                            return {
                                status,
                                message,
                            };
                        }
                    }).then(result => {
                        if (result.isConfirmed) {
                            const {
                                status,
                                message
                            } = result.value;

                            // Thêm logic cập nhật trạng thái vào đây
                            fetch('update_order_status.php', {
                                    method: 'POST',
                                    body: JSON.stringify({
                                        orderId,
                                        status,
                                        message
                                    }),
                                    headers: {
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Saved!',
                                            text: `Order #${orderId} status updated to ${status}.`,
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            preConfirm: () => {
                                                if (message) {
                                                    
                                                    sendOrderStatus(orderId, message, status); // Hàm gửi trạng thái đơn hàng
                                                }
                                                //window.location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire('Error!', 'There was an issue updating the order status.', 'error');
                                    }
                                })
                                .catch(error => Swal.fire('Error!', 'There was an issue with the request.', 'error'));
                        }
                    });

                    document.getElementById('auto-fill').addEventListener('click', () => {
                        document.getElementById('status-message').value = 'Your product has been delivered. Thank you for shopping with us!';
                    });
                });
            });

            // View Order Details functionality
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const orderId = button.getAttribute('data-id');

                    // Fetch order details from database using AJAX
                    fetch(`get_order_details.php?id=${orderId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(data.items.map(item => `${item.total_payment}`));
                                const itemsHtml = data.items.map(item => `
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4 p-3">
                <img src="../img/${item.img}" alt="${item.name}" class="img-fluid rounded-start">
            </div>
            <div class="col-md-8">
                <div style="text-align: left; font-size: 16px;" class="card-body">
                    <h5 class="card-title">${item.name}</h5>
                    <p class="card-text">
                        <strong>Color:</strong> ${item.color} <br>
                        <strong>Size:</strong> ${item.size} <br>
                        <strong>Quantity:</strong> ${item.quantity} <br>
                        <strong>Price:</strong> ${Number(item.price).toLocaleString()} VND
                    </p>
                </div>
            </div>
        </div>
    </div>
`).join('');

                                Swal.fire({
                                    title: `Order #${orderId} Details`,
                                    html: `
                                    <p><strong>Items:</strong><br>${itemsHtml}</p>
                                    <div class="row">
        <!-- Left Column: Customer Info -->
        <div class="col-md-6">
            <p><strong>Customer Info:</strong></p>
            <p style="text-align: left; font-size: 16px;">
                Name: ${data.items[0].customerName} <br>
                Email: ${data.items[0].customerEmail} <br>
                Phone: ${data.items[0].customerPhone}
            </p>
        </div>
        <!-- Right Column: Total Payment -->
        <div class="col-md-6 text-end">
            <p><strong>Total Payment:</strong></p>
            <p>${Number(data.items[0].total_payment).toLocaleString()} VND</p>
        </div>
    </div>
                                `,

                                    showCloseButton: true
                                });
                            } else {
                                Swal.fire('Error!', 'Could not fetch order details.', 'error');
                            }
                        })
                        .catch(error => Swal.fire('Error!', 'There was an issue fetching the order details.', 'error'));
                });
            });
            //         // View Order Details functionality
            // document.querySelectorAll('.view-details-btn').forEach(button => {
            //     button.addEventListener('click', () => {
            //         const orderId = button.getAttribute('data-id');
            //         // Redirect to order details page
            //         window.location.href = `get_order_details.php?id=${orderId}`;
            //     });
            // });
            function sendOrderStatus(orderId, message, status) {
                let username;
                let email;


                // Fetch order details from database using AJAX
                fetch(`get_order_details.php?id=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('items', data.items[0].customerName);
                            username = data.items[0].customerName;
                            email = data.items[0].customerEmail;
                            const sendmailData = {
                                action: 'order',
                                username: username,
                                email: email,
                                message: message,
                                status: status,
                                orderId: orderId
                            };
                            console.log(sendmailData);
                            fetch('../../Login/sendOrderDetail.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(sendmailData)

                                })
                                .then(response => response.json())
                                .then(data => {
                                    try {
                                        const jsonData = JSON.parse(data);
                                        alert(jsonData.message);
                                        // Chuyển hướng sau khi xử lý thành công
                                    } catch (error) {
                                        console.error('Error parsing JSON:', error);
                                        alert('Message sent successfully!');
                                        window.location.reload();
                                        // Chuyển hướng ngay cả khi JSON không phân tích được
                                    }
                                })
                                .catch(error => {
                                    console.error('Fetch error:', error);
                                });
                        } else {
                            Swal.fire('Error!', 'Could not fetch order details.', 'error');
                        }
                    })
                    .catch(error => Swal.fire('Error!', 'There was an issue fetching the order details.', 'error'));




            }
        </script>
</body>
<script src="../script.js"></script>

</html>