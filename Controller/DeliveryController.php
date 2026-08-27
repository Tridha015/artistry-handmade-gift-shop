<?php
ob_start();
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/DeliveryModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Delivery') {
    header("Location: ../View/auth/login.php?error=Unauthorized Access");
    exit();
}

$riderId = intval($_SESSION['user_id'] ?? 0);
$action  = $_POST['action'] ?? '';

if ($action === 'update_status') {
    $deliveryId = intval($_POST['delivery_id'] ?? 0);
    $newStatus  = trim($_POST['new_status'] ?? '');

    if ($deliveryId > 0 && !empty($newStatus)) {
        updateDeliveryStatus($deliveryId, $riderId, $newStatus);
        header("Location: ../View/delivery/dashboard.php?success=Parcel status updated to " . urlencode($newStatus));
        exit();
    }
}

header("Location: ../View/delivery/dashboard.php");
exit();
?>