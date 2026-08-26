<?php
require_once __DIR__ . '/../config/db.php';

function getAdminStats() {
    global $conn;

    $revRes = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders WHERE status = 'Confirmed'");
    $totalRev = mysqli_fetch_assoc($revRes)['total'] ?? 0;

    $reqRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM custom_orders WHERE status = 'Pending Review'");
    $pendingReq = mysqli_fetch_assoc($reqRes)['total'] ?? 0;

    $sellerRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'Seller' AND status = 'Active'");
    $activeSellers = mysqli_fetch_assoc($sellerRes)['total'] ?? 0;

    $riderRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'Delivery' AND status = 'Active'");
    $activeRiders = mysqli_fetch_assoc($riderRes)['total'] ?? 0;

    return array(
        'revenue' => $totalRev,
        'custom_pending' => $pendingReq,
        'sellers' => $activeSellers,
        'riders' => $activeRiders
    );
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
    $sql = "SELECT o.*, p.title AS product_name, u.name AS customer_name, u.phone AS customer_phone,
                   d.id AS delivery_id, d.rider_id, d.delivery_status 
            FROM orders o
            JOIN products p ON o.product_id = p.id
            JOIN users u ON o.customer_id = u.id
            LEFT JOIN deliveries d ON o.id = d.order_id
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
    $sql = "SELECT id, name FROM users WHERE role = 'Delivery' AND status = 'Active'";
    return mysqli_query($conn, $sql);
}

function updateCustomOrderStatus($orderId, $status) {
    global $conn;
    $orderId = intval($orderId);
    $status = mysqli_real_escape_string($conn, $status);
    $sql = "UPDATE custom_orders SET status = '$status' WHERE id = $orderId";
    return mysqli_query($conn, $sql);
}

function updateStoreOrderStatus($orderId, $status) {
    global $conn;
    $orderId = intval($orderId);
    $status = mysqli_real_escape_string($conn, $status);
    $sql = "UPDATE orders SET status = '$status' WHERE id = $orderId";
    return mysqli_query($conn, $sql);
}

function updateUserAccountStatus($userId, $status) {
    global $conn;
    $userId = intval($userId);
    $status = mysqli_real_escape_string($conn, $status);
    $sql = "UPDATE users SET status = '$status' WHERE id = $userId";
    return mysqli_query($conn, $sql);
}

function assignRiderToOrder($orderId, $riderId, $address) {
    global $conn;
    $orderId = intval($orderId);
    $riderId = intval($riderId);
    $address = mysqli_real_escape_string($conn, $address);

    $check = mysqli_query($conn, "SELECT id FROM deliveries WHERE order_id = $orderId");
    if (mysqli_num_rows($check) > 0) {
        $sql = "UPDATE deliveries SET rider_id = $riderId, delivery_status = 'Picked Up' WHERE order_id = $orderId";
    } else {
        $sql = "INSERT INTO deliveries (order_id, rider_id, delivery_address, delivery_status, assigned_at) 
                VALUES ($orderId, $riderId, '$address', 'Picked Up', NOW())";
    }
    return mysqli_query($conn, $sql);
}
?>