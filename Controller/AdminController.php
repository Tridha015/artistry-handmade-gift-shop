<?php
session_start();
require_once __DIR__ . '/../Model/AdminModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../View/auth/login.php?error=Access Denied!");
    exit();
}

if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    $action = $_GET['action'];

    if ($action === 'accept') {
        updateCustomOrderStatus($orderId, 'Accepted');
    } elseif ($action === 'reject') {
        updateCustomOrderStatus($orderId, 'Rejected');
    }

    header("Location: ../View/admin/dashboard.php?success=Custom order status updated");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'confirm_order' && isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    updateStoreOrderStatus($orderId, 'Confirmed');
    header("Location: ../View/admin/dashboard.php?success=Payment verified and order confirmed!");
    exit();
}

if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $action = $_GET['action'];

    if ($action === 'approve_user') {
        updateUserAccountStatus($userId, 'Active');
    } elseif ($action === 'suspend_user') {
        updateUserAccountStatus($userId, 'Suspended');
    }

    header("Location: ../View/admin/dashboard.php?success=User status updated successfully");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'assign_rider') {
    $orderId = intval($_POST['order_id']);
    $riderId = intval($_POST['rider_id']);
    $address = trim($_POST['address']);

    assignRiderToOrder($orderId, $riderId, $address);
    header("Location: ../View/admin/dashboard.php?success=Rider assigned successfully");
    exit();
}
?>