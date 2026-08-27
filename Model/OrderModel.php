<?php
require_once __DIR__ . '/../config/db.php';

function getOrdersByCustomer($customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT o.*, p.title AS product_name, p.image AS product_image 
            FROM orders o 
            JOIN products p ON o.product_id = p.id 
            WHERE o.customer_id = $customerId 
            ORDER BY o.id DESC";
    return mysqli_query($conn, $sql);
}

function getCustomOrdersByCustomer($customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT * FROM custom_orders 
            WHERE customer_id = $customerId 
            ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function placeStoreOrder($customerId, $productId, $quantity, $totalPrice, $gateway, $senderNum, $trxId, $address) {
    global $conn;
    $customerId = intval($customerId);
    $productId  = intval($productId);
    $quantity   = intval($quantity);
    $totalPrice = floatval($totalPrice);
    $gateway    = mysqli_real_escape_string($conn, $gateway);
    $senderNum  = mysqli_real_escape_string($conn, $senderNum);
    $trxId      = mysqli_real_escape_string($conn, $trxId);
    $address    = mysqli_real_escape_string($conn, $address);

    $sql = "INSERT INTO orders (customer_id, product_id, quantity, total_price, payment_gateway, sender_number, trx_id, delivery_address, status) 
            VALUES ($customerId, $productId, $quantity, $totalPrice, '$gateway', '$senderNum', '$trxId', '$address', 'Payment pending')";
            
    return mysqli_query($conn, $sql);
}

function createCustomOrder($customerId, $craftType, $craftSize, $layers, $colorTheme, $instructions, $sampleImage, $budget) {
    global $conn;
    $customerId   = intval($customerId);
    $craftType    = mysqli_real_escape_string($conn, $craftType);
    $craftSize    = mysqli_real_escape_string($conn, $craftSize);
    $layers       = intval($layers);
    $colorTheme   = mysqli_real_escape_string($conn, $colorTheme);
    $instructions = mysqli_real_escape_string($conn, $instructions);
    $sampleImage  = mysqli_real_escape_string($conn, $sampleImage);
    $budget       = floatval($budget);

    $sql = "INSERT INTO custom_orders (customer_id, craft_type, craft_size, layers, color_theme, instructions, sample_image, budget, status) 
            VALUES ($customerId, '$craftType', '$craftSize', $layers, '$colorTheme', '$instructions', '$sampleImage', $budget, 'Pending Review')";
            
    return mysqli_query($conn, $sql);
}
?>