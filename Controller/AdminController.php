<?php
session_start();
require_once __DIR__ . '/../Model/AdminModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../View/auth/login.php?error=Access Denied!");
    exit();
}

if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $action = $_GET['action'];

    if ($action === 'accept') {
        updateCustomOrderStatus($orderId, 'Accepted');
    } elseif ($action === 'reject') {
        updateCustomOrderStatus($orderId, 'Rejected');
    }

    header("Location: ../View/admin/dashboard.php?success=Custom order status updated");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'assign_rider') {
    $orderId = $_POST['order_id'];
    $riderId = $_POST['rider_id'];
    $address = $_POST['address'];

    assignRiderToOrder($orderId, $riderId, $address);
    header("Location: ../View/admin/dashboard.php?success=Rider assigned successfully");
    exit();
}
?>