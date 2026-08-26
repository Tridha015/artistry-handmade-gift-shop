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
 
function getOrdersByCustomer($customerId) {
    global $conn;
    $customerId = intval($customerId);
    $sql = "SELECT 'Store Order' AS order_type, o.id, p.title AS item_name, o.total_price AS amount, o.status, d.delivery_status 
            FROM orders o 
            JOIN products p ON o.product_id = p.id 
            LEFT JOIN deliveries d ON o.id = d.order_id 
            WHERE o.customer_id = $customerId
            UNION ALL
            SELECT 'Custom Order' AS order_type, co.id, co.craft_type AS item_name, co.budget AS amount, co.status, 'N/A' AS delivery_status 
            FROM custom_orders co 
            WHERE co.customer_id = $customerId
            ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}
?>