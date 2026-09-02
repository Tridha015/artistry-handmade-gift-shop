<?php
require_once __DIR__ . '/../config/db.php';

function getOrdersByCustomer(int $customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT o.*, p.title AS product_name, d.delivery_status, d.rider_id, u.name AS rider_name 
            FROM orders o 
            JOIN products p ON o.product_id = p.id 
            LEFT JOIN deliveries d ON o.id = d.order_id 
            LEFT JOIN users u ON d.rider_id = u.id 
            WHERE o.customer_id = $customerId 
            ORDER BY o.id DESC";
    return mysqli_query($conn, $sql);
}

function getCustomOrdersByCustomer(int $customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT * FROM custom_orders WHERE customer_id = $customerId ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function createCustomOrder(int $customerId, string $craftType, string $craftSize, string $layers, string $colorTheme, float $budget, string $sampleImage, string $instructions) {
    global $conn;
    $customerId   = intval($customerId);
    $craftType    = mysqli_real_escape_string($conn, $craftType);
    $craftSize    = mysqli_real_escape_string($conn, $craftSize);
    $layers       = mysqli_real_escape_string($conn, $layers);
    $colorTheme   = mysqli_real_escape_string($conn, $colorTheme);
    $budget       = floatval($budget);
    $sampleImage  = mysqli_real_escape_string($conn, $sampleImage);
    $instructions = mysqli_real_escape_string($conn, $instructions);

    $sql = "INSERT INTO custom_orders (customer_id, craft_type, craft_size, layers, color_theme, budget, sample_image, instructions, status) 
            VALUES ($customerId, '$craftType', '$craftSize', '$layers', '$colorTheme', $budget, '$sampleImage', '$instructions', 'Pending Review')";
    return mysqli_query($conn, $sql);
}
?>