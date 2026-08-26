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

    $sql = "SELECT id, craft_type AS item_name, budget AS amount, status, 'Custom Craft' AS order_type, 'N/A' AS delivery_status 
            FROM custom_orders 
            WHERE customer_id = $customerId
            ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Order Query Error: " . mysqli_error($conn));
    }
    return $result;
}
?>