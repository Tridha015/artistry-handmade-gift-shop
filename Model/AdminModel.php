<?php
require_once __DIR__ . '/../config/db.php';

function getAdminStats(): array {
    global $conn;
    
    $revRes = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders WHERE status = 'Confirmed'");
    $revRow = mysqli_fetch_assoc($revRes);
    $revenue = $revRow['total'] ?? 0;

    $cpRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM custom_orders WHERE status = 'Pending Review'");
    $cpRow = mysqli_fetch_assoc($cpRes);
    $customPending = $cpRow['total'] ?? 0;

    $selRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'Seller' AND status = 'Active'");
    $selRow = mysqli_fetch_assoc($selRes);
    $sellers = $selRow['total'] ?? 0;

    $ridRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'Delivery' AND status = 'Active'");
    $ridRow = mysqli_fetch_assoc($ridRes);
    $riders = $ridRow['total'] ?? 0;

    return [
        'revenue'        => $revenue,
        'custom_pending' => $customPending,
        'sellers'        => $sellers,
        'riders'         => $riders
    ];
}

function getAllCustomOrders() {
    global $conn;
    $sql = "SELECT co.*, u.name AS customer_name, u.phone AS customer_phone 
            FROM custom_orders co 
            JOIN users u ON co.customer_id = u.id 
            ORDER BY co.id DESC";
    return mysqli_query($conn, $sql);
}

function getAllStoreOrders() {
    global $conn;
    $sql = "SELECT o.*, u.name AS customer_name, u.phone AS customer_phone, p.title AS product_name, d.rider_id, d.delivery_status, r.name AS rider_name 
            FROM orders o 
            JOIN users u ON o.customer_id = u.id 
            JOIN products p ON o.product_id = p.id 
            LEFT JOIN deliveries d ON o.id = d.order_id 
            LEFT JOIN users r ON d.rider_id = r.id 
            ORDER BY o.id DESC";
    return mysqli_query($conn, $sql);
}

function getAllRegistrations() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role != 'Admin' ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getActiveRiders() {
    global $conn;
    $sql = "SELECT id, name, phone FROM users WHERE role = 'Delivery' AND status = 'Active' ORDER BY name ASC";
    return mysqli_query($conn, $sql);
}

function setCustomOrderAdminPrice(int $orderId, float $finalPrice) {
    global $conn;
    $orderId    = intval($orderId);
    $finalPrice = floatval($finalPrice);
    $sql = "UPDATE custom_orders SET budget = $finalPrice, status = 'Accepted' WHERE id = $orderId";
    return mysqli_query($conn, $sql);
}

function updateCustomOrderStatus(int $orderId, string $status) {
    global $conn;
    $orderId = intval($orderId);
    $status  = mysqli_real_escape_string($conn, $status);
    $sql     = "UPDATE custom_orders SET status = '$status' WHERE id = $orderId";
    return mysqli_query($conn, $sql);
}

function updateStoreOrderStatus(int $orderId, string $status) {
    global $conn;
    $orderId = intval($orderId);
    $status  = mysqli_real_escape_string($conn, $status);
    $sql     = "UPDATE orders SET status = '$status' WHERE id = $orderId";
    return mysqli_query($conn, $sql);
}

function updateUserAccountStatus(int $userId, string $status) {
    global $conn;
    $userId = intval($userId);
    $status = mysqli_real_escape_string($conn, $status);
    $sql    = "UPDATE users SET status = '$status' WHERE id = $userId";
    return mysqli_query($conn, $sql);
}

function assignRiderToOrder(int $orderId, int $riderId, string $address) {
    global $conn;
    $orderId = intval($orderId);
    $riderId = intval($riderId);
    $address = mysqli_real_escape_string($conn, $address);

    $check = mysqli_query($conn, "SELECT id FROM deliveries WHERE order_id = $orderId LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        $sql = "UPDATE deliveries SET rider_id = $riderId, delivery_address = '$address', delivery_status = 'Assigned' WHERE order_id = $orderId";
    } else {
        $sql = "INSERT INTO deliveries (order_id, rider_id, delivery_address, delivery_status) VALUES ($orderId, $riderId, '$address', 'Assigned')";
    }
    return mysqli_query($conn, $sql);
}
?>