<?php
require_once __DIR__ . '/../config/db.php';
 
function createCustomOrder($customerId, $craftType, $craftSize, $layers, $colorTheme, $budget, $sampleImage, $instructions) {
    global $conn;
    $customerId   = intval($customerId);
    $craftType    = mysqli_real_escape_string($conn, $craftType);
    $craftSize    = mysqli_real_escape_string($conn, $craftSize);
    $layers       = intval($layers);
    $colorTheme   = mysqli_real_escape_string($conn, $colorTheme);
    $budget       = floatval($budget);
    $sampleImage  = mysqli_real_escape_string($conn, $sampleImage);
    $instructions = mysqli_real_escape_string($conn, $instructions);
    $status       = 'Pending Review';
 
    $sql = "INSERT INTO custom_orders (customer_id, craft_type, craft_size, layers, color_theme, budget, sample_image, instructions, status) 
            VALUES ($customerId, '$craftType', '$craftSize', $layers, '$colorTheme', $budget, '$sampleImage', '$instructions', '$status')";
    return mysqli_query($conn, $sql);
}

function getCustomOrdersByCustomer($customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT * FROM custom_orders WHERE customer_id = $customerId ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getStoreOrdersByCustomer($customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT o.*, p.title AS product_name, d.delivery_status 
            FROM orders o 
            JOIN products p ON o.product_id = p.id 
            LEFT JOIN deliveries d ON o.id = d.order_id 
            WHERE o.customer_id = $customerId 
            ORDER BY o.id DESC";
    return mysqli_query($conn, $sql);
}
?>