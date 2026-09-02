<?php
require_once __DIR__ . '/../config/db.php';
 
function getDeliveriesByRider(int $riderId) {
    global $conn;
    $riderId = intval($riderId);
    $sql = "SELECT d.id AS delivery_id, d.delivery_status, d.delivery_address, 
                   o.id AS order_id, o.total_price, o.payment_gateway,
                   u.name AS customer_name, u.phone AS customer_phone,
                   p.title AS product_name, p.image AS product_image
            FROM deliveries d
            JOIN orders o ON d.order_id = o.id
            JOIN users u ON o.customer_id = u.id
            JOIN products p ON o.product_id = p.id
            WHERE d.rider_id = $riderId
            ORDER BY d.id DESC";
    return mysqli_query($conn, $sql);
}
 
function updateDeliveryStatusByRider(int $deliveryId, int $riderId, string $status) {
    global $conn;
    $deliveryId = intval($deliveryId);
    $riderId    = intval($riderId);
    $status     = mysqli_real_escape_string($conn, $status);
 
    $sql = "UPDATE deliveries SET delivery_status = '$status' 
            WHERE id = $deliveryId AND rider_id = $riderId";
    return mysqli_query($conn, $sql);
}
 
function rejectDeliveryTask(int $deliveryId, int $riderId) {
    global $conn;
    $deliveryId = intval($deliveryId);
    $riderId    = intval($riderId);
 
    $sql = "UPDATE deliveries SET rider_id = NULL, delivery_status = 'Pending Assignment' 
            WHERE id = $deliveryId AND rider_id = $riderId";
    return mysqli_query($conn, $sql);
}
 
function getRiderDeliveryStats(int $riderId): array {
    global $conn;
    $riderId = intval($riderId);
 
    $totalRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM deliveries WHERE rider_id = $riderId");
    $totalRow = mysqli_fetch_assoc($totalRes);
 
    $delRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM deliveries WHERE rider_id = $riderId AND delivery_status = 'Delivered'");
    $delRow = mysqli_fetch_assoc($delRes);
 
    $pendingRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM deliveries WHERE rider_id = $riderId AND delivery_status != 'Delivered'");
    $pendingRow = mysqli_fetch_assoc($pendingRes);
 
    return [
        'total_assigned' => intval($totalRow['total'] ?? 0),
        'completed'      => intval($delRow['total'] ?? 0),
        'in_progress'    => intval($pendingRow['total'] ?? 0)
    ];
}
 
function toggleRiderOnlineStatus(int $riderId, int $isOnline) {
    global $conn;
    $riderId  = intval($riderId);
    $isOnline = intval($isOnline);
    $sql = "UPDATE users SET is_online = $isOnline WHERE id = $riderId AND role = 'Delivery'";
    return mysqli_query($conn, $sql);
}
 
function getRiderAssignedDeliveriesCount(int $riderId): int {
    global $conn;
    $riderId = intval($riderId);
    $res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM deliveries WHERE rider_id = $riderId AND delivery_status != 'Delivered'");
    $row = mysqli_fetch_assoc($res);
    return intval($row['total'] ?? 0);
}
?>