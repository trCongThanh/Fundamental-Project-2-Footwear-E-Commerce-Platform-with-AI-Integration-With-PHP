<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $action = $data['action'] ?? null;

    if ($action === 'insert_voucher') {
        $id = $data['id'] ?? null;
        $discount = $data['discount'] ?? null;

        if (!$id || !$discount) {
            echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
            exit;
        }

        // Kết nối tới cơ sở dữ liệu
        $host = 'localhost'; // Đổi thành thông tin của bạn
        $username = 'root'; // Đổi thành thông tin của bạn
        $password = ''; // Đổi thành thông tin của bạn
        $dbname = 'shopbangiay'; // Đổi thành thông tin của bạn

        $conn = new mysqli($host, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
            exit;
        }

        // Thực hiện truy vấn thêm mới
        $stmt = $conn->prepare("INSERT INTO voucher (id, discount) VALUES (?, ?)");
        $stmt->bind_param('ss', $id, $discount);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Voucher added successfully!']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add voucher.']);
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action.']);
        exit;
    }
}
