<?php
$idsender = "";
$username = "";
$idCart = "";
if (isset($_COOKIE["idUser"]) && isset($_COOKIE["username"]) && isset($_COOKIE["idCart"])) {
    $idsender = $_COOKIE["idUser"];
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
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>AdminSite</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand"><i class='bx bxs-smile icon'></i> AdminSite</a>
        <ul class="side-menu">
            <li><a href="#" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
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
                    <li><a href="../Login/login.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
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
        <!-- MAIN -->
        <main>
            <h1 class="title">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Dashboard</a></li>
            </ul>
            <div class="info-data">
                <div class="card">
                    <div class="head">
                        <div>
                            <h2>1500</h2>
                            <p>Traffic</p>
                        </div>
                        <i class='bx bx-trending-up icon'></i>
                    </div>
                    <span class="progress" data-value="40%"></span>
                    <span class="label">40%</span>
                </div>
                <div class="card">
                    <div class="head">
                        <div>
                            <h2>234</h2>
                            <p>Sales</p>
                        </div>
                        <i class='bx bx-trending-down icon down'></i>
                    </div>
                    <span class="progress" data-value="60%"></span>
                    <span class="label">60%</span>
                </div>
                <div class="card">
                    <div class="head">
                        <div>
                            <h2>465</h2>
                            <p>Pageviews</p>
                        </div>
                        <i class='bx bx-trending-up icon'></i>
                    </div>
                    <span class="progress" data-value="30%"></span>
                    <span class="label">30%</span>
                </div>
                <div id="originCard" class="card">
                    <div class="head">
                        <div>
                            <h2>235</h2>
                            <p>Visitors</p>
                        </div>
                        <i class='bx bx-trending-up icon'></i>
                    </div>
                    <span class="progress" data-value="80%"></span>
                    <span class="label">80%</span>
                </div>
            </div>
            <div class="data">
                <div class="content-data" id="chatContainer">
                    <div class="head">
                        <h3>Sales Report</h3>
                        <div class="menu">
                            <i class='bx bx-dots-horizontal-rounded icon'></i>
                            <ul class="menu-link">
                                <li><a href="#">Edit</a></li>
                                <li><a href="#">Save</a></li>
                                <li><a href="#">Remove</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart">
                        <div id="chart"></div>
                    </div>
                </div>
                <script>
                    function adjustStyles() {
                        const chatContainer = document.querySelector('#chatContainer');
                        const originCard = document.querySelector('#originCard');
                        var originCardWidth = originCard.offsetWidth;
                        if (window.innerWidth <= 500) {
                            chatContainer.style.maxWidth = originCardWidth + 'px';
                        } else {
                            chatContainer.style.maxWidth = ''; // Xóa thuộc tính maxWidth
                        }
                    }

                    // Gọi hàm khi trang được tải
                    window.onload = adjustStyles;
                    // Gọi hàm khi kích thước cửa sổ thay đổi
                    window.onresize = adjustStyles;
                </script>
                <style>
                    /* Container styling */
                    .container {
                        width: 100%;
                        height: 100vh;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        overflow-y: auto;
                        border: 1px solid #ddd;
                        background-color: white;

                    }

                    /* User list styles */
                    .user-list,
                    .chat-view {
                        width: 100%;
                        max-width: 400px;
                        display: none;
                        flex-direction: column;
                        margin-left: 0%;
                    }

                    .user-item {
                        padding: 10px;
                        display: flex;
                        align-items: center;
                        cursor: pointer;
                        border-bottom: 1px solid #ddd;
                        transition: background 0.3s;
                    }

                    .user-item:hover {
                        background-color: #f0f0f0;
                    }

                    .user-item img {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        margin-right: 10px;
                    }

                    /* Chat view styles */
                    .chat-header {
                        padding: 10px;
                        border-bottom: 1px solid #ddd;
                        text-align: center;
                        position: relative;
                    }

                    .back-button {
                        position: absolute;
                        left: 10px;
                        top: 10px;
                        cursor: pointer;
                    }

                    .chat-box {
                        flex-grow: 1;
                        padding: 10px;
                        overflow-y: auto;
                    }

                    .user-message {
                        background-color: lightgreen;
                        align-self: flex-start;
                        margin: 5px;
                        padding: 10px;
                        border-radius: 10px 10px 10px 0px;
                        max-width: 80%;

                    }

                    .admin-message {
                        background-color: lightblue;
                        align-self: flex-end;
                        margin: 5px;
                        padding: 10px;
                        border-radius: 10px 10px 0px 10px;
                        max-width: 80%;
                        margin-left: 20%;
                    }

                    form {
                        display: flex;
                        padding: 10px;
                        border-top: 1px solid #ddd;
                    }

                    form input {
                        flex-grow: 1;
                        padding: 8px;
                        margin-right: 5px;
                    }

                    form button {
                        padding: 8px 16px;
                    }
                </style>
                </head>

                <body>

                    <?php
                    // Include database connection
                    include('database.php');

                    // Fetch all users with role 'customer'
                    $sql = "SELECT * FROM user WHERE role='customer'";
                    $result = mysqli_query($conn, $sql);
                    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    mysqli_free_result($result);
                    ?>

                    <div class="content-data">
                        <!-- User List View -->
                        <div class="user-list" id="userListView">
                            <h3>Select a User</h3>
                            <?php foreach ($users as $user): ?>
                                <div class="user-item" onclick="loadChat(<?php echo $user['id']; ?>, '<?php echo $user['username']; ?>')">
                                    <img src="<?php echo htmlspecialchars($user['avatar'] ? 'phpUser/imgUser/' . $user['avatar'] : 'https://t3.ftcdn.net/jpg/05/16/27/58/360_F_516275801_f3Fsp17x6HQK0xQgDQEELoTuERO4SsWV.jpg'); ?>"
                                        style="height: 50px; width: 50px; border-radius: 50%;" alt="User Avatar">
                                    <div class="user-info">
                                        <strong><?php echo $user['username']; ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Chat View -->
                        <div class="chat-view" id="chatView">
                            <div class="chat-header">
                                <span class="back-button" onclick="showUserList()">← Back</span>
                                <h3 id="chatUserName">Chat</h3>
                            </div>
                            <div class="chat-box" id="chatContent">
                                <!-- Chat messages will be loaded here -->
                            </div>
                            <form onsubmit="sendMessage(); return false;">
                                <input type="text" id="messageInput" placeholder="Type a message...">
                                <button type="submit">Send</button>
                            </form>
                        </div>
                    </div>

                    <?php
                    // Close the database connection
                    mysqli_close($conn);
                    ?>

                    <script>
                        let selectedUserId = 0;

                        function addChatbox(messageText) {
                            const chatboxInput = messageText.trim();

                            if (!chatboxInput) {
                                console.error("Chatbox input is empty!");
                                return;
                            }
                            
                            const updateChatbox = {
                                userId: selectedUserId,
                                senderId: <?php echo $idsender ?>,
                                chatbox: chatboxInput,
                                role: "admin",
                            };

                            // Debug thông tin gửi đi
                            console.log("Data to send:", updateChatbox);

                            // Gửi dữ liệu đến PHP
                            fetch('phpFile/updateChatbox.php', {
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
                                    //document.getElementById("liveMessageInput").value = '';
                                })
                                .catch((error) => {
                                    console.error('Error:', error);
                                });
                        }
                        // Show the user list initially
                        document.getElementById("userListView").style.display = "flex";

                        let autoFetchInterval = null;

                        // Hàm tự động fetch dữ liệu chat
                        function autoFetchChat() {
                            if (selectedUserId === 0) {
                                console.log("No user selected, stopping auto-fetch.");
                                return;
                            }

                            console.log(`Fetching chat data for userId: ${selectedUserId}`);
                            fetch(`getChat.php?userId=${selectedUserId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        const chatboxContent = data.chatbox;
                                        displayChatMessages(chatboxContent);
                                    } else {
                                        console.error('Error fetching chat data:', data.message);
                                    }
                                })
                                .catch(error => console.error("Error fetching chat content:", error));
                        }

                        // Bắt đầu auto-fetch khi một user được chọn
                        function startAutoFetch() {
                            if (autoFetchInterval) {
                                clearInterval(autoFetchInterval);
                            }
                            autoFetchInterval = setInterval(autoFetchChat, 2000); // Fetch mỗi 5 giây
                        }

                        // Dừng auto-fetch khi cần
                        function stopAutoFetch() {
                            if (autoFetchInterval) {
                                clearInterval(autoFetchInterval);
                                autoFetchInterval = null;
                            }
                        }

                        // Function để chọn user và bắt đầu chat
                        function loadChat(userId, userName) {
                            selectedUserId = userId; // Gán selectedUserId
                            document.getElementById("userListView").style.display = "none";
                            document.getElementById("chatView").style.display = "flex";
                            document.getElementById("chatUserName").textContent = userName;

                            fetch(`getChat.php?userId=${userId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        const chatboxContent = data.chatbox;
                                        displayChatMessages(chatboxContent);
                                        startAutoFetch(); // Bắt đầu auto-fetch
                                    } else {
                                        console.error('Error fetching chat data:', data.message);
                                    }
                                })
                                .catch(error => console.error("Lỗi khi tải nội dung chat:", error));
                        }

                        // Quay lại danh sách user
                        function showUserList() {
                            selectedUserId = 0; // Reset selectedUserId
                            stopAutoFetch(); // Dừng auto-fetch
                            document.getElementById("userListView").style.display = "flex";
                            document.getElementById("chatView").style.display = "none";
                        }


                        // Hàm hiển thị tin nhắn trong giao diện chat
                        function displayChatMessages(chatboxContent) {
                            //console.log(chatboxContent);
                            const chatContainer = document.getElementById("chatContent"); // Phần tử chứa các tin nhắn
                            chatContainer.innerHTML = ""; // Xóa nội dung cũ

                            // Tách từng dòng tin nhắn
                            const messages = chatboxContent.split("\n\n");

                            messages.forEach(message => {
                                const messageDiv = document.createElement("div");
                                //console.log(message);
                                if (message.includes("role: customer")) {
                                    messageDiv.classList.add("user-message");
                                    messageDiv.textContent = message.replace("role: customer\n", "");
                                } else if (message.includes("role: admin")) {
                                    messageDiv.classList.add("admin-message");
                                    messageDiv.textContent = message.replace("role: admin\n", "");
                                }

                                chatContainer.appendChild(messageDiv);
                            });
                        }

                        // Function to send a message
                        function sendMessage() {
                            const messageInput = document.getElementById("messageInput");
                            const messageText = messageInput.value.trim();

                            if (messageText) {
                                // const chatContent = document.getElementById("chatContent");

                                // const userMessageBubble = document.createElement("div");
                                // userMessageBubble.className = 'admin-message';
                                // userMessageBubble.innerHTML = `<strong>AdminThanh</strong>: ${messageText}`;
                                // chatContent.appendChild(userMessageBubble);
                                //////////////////////////
                                addChatbox(messageText);

                                messageInput.value = ""; // Clear input after sending

                                // Optionally, add code to send the message to the server
                            }
                        }
                    </script>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- NAVBAR -->

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>
</body>

</html>