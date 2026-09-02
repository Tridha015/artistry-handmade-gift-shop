<?php
require_once __DIR__ . '/../config/db.php';

function getDeliveriesByRider(int $riderId) {
    global $conn;
    $riderId = intval($riderId);
    
    $sql = "SELECT d.id AS delivery_id, d.delivery_status, d.delivery_address, 
                   o.id AS order_id, o.trx_id, o.total_price, o.payment_gateway,
                   COALESCE(u.name, 'Customer') AS customer_name, 
                   COALESCE(u.phone, 'N/A') AS customer_phone,
                   co.id AS custom_order_id,
                   co.craft_type AS custom_craft_name,
                   co.craft_size AS custom_craft_size,
                   co.layers AS custom_craft_layers,
                   co.color_theme AS custom_craft_theme,
                   co.sample_image AS custom_craft_image,
                   p.title AS store_product_name, 
                   p.image AS store_product_image
            FROM deliveries d
            JOIN orders o ON d.order_id = o.id
            JOIN users u ON o.customer_id = u.id
            LEFT JOIN custom_orders co ON (o.trx_id LIKE CONCAT('CUSTOM-', co.id, '-%'))
            LEFT JOIN products p ON o.product_id = p.id
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
    $updated = mysqli_query($conn, $sql);

    if ($updated && $status === 'Delivered') {
        $getOrd = mysqli_query($conn, "SELECT order_id FROM deliveries WHERE id = $deliveryId LIMIT 1");
        if ($getOrd && mysqli_num_rows($getOrd) > 0) {
            $row = mysqli_fetch_assoc($getOrd);
            $ordId = intval($row['order_id']);
            mysqli_query($conn, "UPDATE orders SET status = 'Delivered' WHERE id = $ordId");
        }
    }

    return $updated;
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

    $completedCount = intval($delRow['total'] ?? 0);
    $riderEarnings  = $completedCount * 80; // প্রতি ডেলিভারিতে ৮০ টাকা

    return [
        'total_assigned' => intval($totalRow['total'] ?? 0),
        'completed'      => $completedCount,
        'in_progress'    => intval($pendingRow['total'] ?? 0),
        'earnings'       => $riderEarnings
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