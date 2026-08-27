<?php
require_once __DIR__ . '/../config/db.php';

function getAssignedParcels($riderId) {
    global $conn;
    $riderId = intval($riderId);
    $sql = "SELECT d.id AS delivery_id, d.order_id, d.delivery_address, d.delivery_status,
                   u.name AS customer_name, u.phone AS customer_phone, p.title AS product_name, o.total_price
            FROM deliveries d
            LEFT JOIN orders o ON d.order_id = o.id
            LEFT JOIN users u ON o.customer_id = u.id
            LEFT JOIN products p ON o.product_id = p.id
            WHERE d.rider_id = $riderId AND d.delivery_status != 'Delivered'
            ORDER BY d.id DESC";
    $result = mysqli_query($conn, $sql);
    return $result ? $result : false;
}

function getCompletedDeliveries($riderId) {
    global $conn;
    $riderId = intval($riderId);
    $sql = "SELECT d.id AS delivery_id, d.order_id, d.delivery_address, d.delivery_status,
                   u.name AS customer_name, p.title AS product_name, o.total_price
            FROM deliveries d
            LEFT JOIN orders o ON d.order_id = o.id
            LEFT JOIN users u ON o.customer_id = u.id
            LEFT JOIN products p ON o.product_id = p.id
            WHERE d.rider_id = $riderId AND d.delivery_status = 'Delivered'
            ORDER BY d.id DESC";
    $result = mysqli_query($conn, $sql);
    return $result ? $result : false;
}

function updateDeliveryStatus($deliveryId, $riderId, $status) {
    global $conn;
    $deliveryId = intval($deliveryId);
    $riderId    = intval($riderId);
    $status     = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE deliveries SET delivery_status = '$status' WHERE id = $deliveryId AND rider_id = $riderId";
    return mysqli_query($conn, $sql);
}
?>