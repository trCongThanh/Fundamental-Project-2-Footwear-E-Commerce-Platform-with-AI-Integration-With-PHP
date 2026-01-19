<?php

include('../homepage/database.php');

$user_id = $_POST['user_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$country = $_POST['country'];
$city = $_POST['city'];
$addressLine = $_POST['addressLine'];
$isDefault = $_POST['isDefault'];

$sql_check = "SELECT user_id FROM address WHERE user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    $sql = "UPDATE address 
            SET name = ?, phone = ?, country = ?, city = ?, address_line = ?, is_default = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $phone, $country, $city, $addressLine, $isDefault, $user_id);
    
    if ($stmt->execute()) {
        echo "Address updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $sql = "INSERT INTO address (user_id, name, phone, country, city, address_line, is_default)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $user_id, $name, $phone, $country, $city, $addressLine, $isDefault);
    
    if ($stmt->execute()) {
        echo "Address added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt_check->close();
$stmt->close();
$conn->close();
?>
