<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/DeliveryModel.php';

global $conn;

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Delivery') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

$riderId = intval($_SESSION['user_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajax_update_status') {
    header('Content-Type: application/json');

    $deliveryId = intval($_POST['delivery_id'] ?? 0);
    $newStatus  = trim($_POST['status'] ?? '');

    $allowed = ['Assigned', 'Out for Delivery', 'Delivered'];
    if ($deliveryId > 0 && in_array($newStatus, $allowed)) {
        $updated = updateDeliveryStatusByRider($deliveryId, $riderId, $newStatus);
        if ($updated) {
            $stats = getRiderDeliveryStats($riderId);
            echo json_encode([
                'status'     => 'success',
                'new_status' => $newStatus,
                'message'    => 'Status updated successfully',
                'stats'      => $stats
            ]);
            exit();
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajax_reject_delivery') {
    header('Content-Type: application/json');

    $deliveryId = intval($_POST['delivery_id'] ?? 0);

    if ($deliveryId > 0) {
        $rejected = rejectDeliveryTask($deliveryId, $riderId);
        if ($rejected) {
            $stats = getRiderDeliveryStats($riderId);
            echo json_encode([
                'status'  => 'success',
                'message' => 'Delivery task rejected and sent back to Admin pool',
                'stats'   => $stats
            ]);
            exit();
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Failed to reject delivery task']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajax_toggle_duty') {
    header('Content-Type: application/json');
    $isOnline = intval($_POST['is_online'] ?? 0);

    $updated = toggleRiderOnlineStatus($riderId, $isOnline);
    if ($updated) {
        echo json_encode([
            'status'    => 'success',
            'is_online' => $isOnline,
            'message'   => $isOnline ? 'You are now Online & ready to receive orders' : 'You are now Offline'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to switch duty status']);
    }
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'ajax_poll_tasks') {
    header('Content-Type: application/json');
    $activeCount = getRiderAssignedDeliveriesCount($riderId);
    echo json_encode([
        'status'       => 'success',
        'active_tasks' => $activeCount
    ]);
    exit();
}

header("Location: ../View/delivery/dashboard.php");
exit();
?>