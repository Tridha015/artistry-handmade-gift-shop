<?php

session_start();

require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Model/DeliveryModel.php';


if (!isset($_SESSION['rider_id'])) {
    header('Location: ../View/auth/login.php');
    exit;
}

$riderId = (int) $_SESSION['rider_id'];
$model   = new DeliveryModel($pdo);

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'update_status':
        $deliveryId = (int) ($_POST['delivery_id'] ?? 0);
        $newStatus  = trim($_POST['new_status'] ?? '');

        $success = $model->updateDeliveryStatus($deliveryId, $newStatus, $riderId);

        $_SESSION['flash_message'] = $success
            ? "Delivery #{$deliveryId} status updated to \"{$newStatus}\"."
            : "Could not update that delivery's status. Please refresh and try again.";
        $_SESSION['flash_type'] = $success ? 'success' : 'error';

        header('Location: ../View/delivery/dashboard.php');
        exit;


    case 'update_duty':
        $dutyStatus = trim($_POST['duty_status'] ?? '');

        $success = $model->updateDutyStatus($riderId, $dutyStatus);

        $_SESSION['flash_message'] = $success
            ? "Duty status updated to \"{$dutyStatus}\"."
            : "Could not update duty status.";
        $_SESSION['flash_type'] = $success ? 'success' : 'error';

        header('Location: ../View/delivery/profile.php');
        exit;


    case 'update_zone':
        $zone = trim($_POST['zone'] ?? '');

        $success = $model->updateZone($riderId, $zone);

        $_SESSION['flash_message'] = $success
            ? "Delivery zone updated to \"{$zone}\"."
            : "Could not update delivery zone.";
        $_SESSION['flash_type'] = $success ? 'success' : 'error';

        header('Location: ../View/delivery/profile.php');
        exit;


    default:
        header('Location: ../View/delivery/dashboard.php');
        exit;
}
