<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/AdminModel.php';

global $conn;

if (isset($_POST['action']) && $_POST['action'] === 'ajax_toggle_user_status') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized Access']);
        exit();
    }

    $userId = intval($_POST['user_id'] ?? 0);
    $status = trim($_POST['new_status'] ?? '');

    if ($userId > 0 && in_array($status, ['Active', 'Suspended'])) {
        $updated = updateUserAccountStatus($userId, $status);
        if ($updated) {
            $latestStats = getAdminStats();
            echo json_encode([
                'status'        => 'success',
                'new_status'    => $status,
                'message'       => "User status updated to $status",
                'sellers_count' => $latestStats['sellers'],
                'riders_count'  => $latestStats['riders']
            ]);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            exit();
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit();
}

if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
    header("Location: ../View/auth/login.php?error=Access Denied!");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'accept_and_quote') {
    $orderId    = intval($_POST['order_id'] ?? 0);
    $finalPrice = floatval($_POST['final_price'] ?? 0);

    if ($orderId > 0 && $finalPrice > 0) {
        setCustomOrderAdminPrice($orderId, $finalPrice);
        header("Location: ../View/admin/dashboard.php?success=Quotation updated and sent to customer!");
    } else {
        header("Location: ../View/admin/dashboard.php?error=Please enter a valid price quotation");
    }
    exit();
}

if (isset($_GET['action']) && isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    $action  = $_GET['action'];

    if ($action === 'reject') {
        updateCustomOrderStatus($orderId, 'Rejected');
        header("Location: ../View/admin/dashboard.php?success=Custom request rejected");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'confirm_order' && isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    updateStoreOrderStatus($orderId, 'Confirmed');
    header("Location: ../View/admin/dashboard.php?success=Payment verified & Order confirmed!");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_rider') {
    $orderId = intval($_POST['order_id'] ?? 0);
    $riderId = intval($_POST['rider_id'] ?? 0);
    $address = trim($_POST['address'] ?? '');

    if ($orderId > 0 && $riderId > 0) {
        assignRiderToOrder($orderId, $riderId, $address);
        header("Location: ../View/admin/dashboard.php?success=Rider assigned to order #ORD-" . $orderId);
    } else {
        header("Location: ../View/admin/dashboard.php?error=Please select a valid rider");
    }
    exit();
}

header("Location: ../View/admin/dashboard.php");
exit();
?>