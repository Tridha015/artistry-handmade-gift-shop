<?php
require_once __DIR__ . '/../config/db.php';

function registerUser($name, $email, $phone, $password, $role) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $status = ($role === 'Customer') ? 'Active' : 'Pending';

    $sql = "INSERT INTO users (name, email, phone, password, role, status) 
            VALUES ('$name', '$email', '$phone', '$hashedPassword', '$role', '$status')";
            
    return mysqli_query($conn, $sql);
}
function getUserByEmail($email) {
    global $conn;
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}

function getAllUsers() {
    global $conn;
    $sql = "SELECT * FROM users ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}
//change status
function updateUserStatus($userId, $status) {
    global $conn;
    $userId = intval($userId);
    $status = mysqli_real_escape_string($conn, $status);
    $sql = "UPDATE users SET status = '$status' WHERE id = $userId";
    return mysqli_query($conn, $sql);
}

function getDashboardStats() {
    global $conn;
    
    $userCountRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
    $totalUsers = mysqli_fetch_assoc($userCountRes)['total'];

    $pendingOrderRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'Payment pending'");
    $pendingOrders = mysqli_fetch_assoc($pendingOrderRes)['total'];

    $revenueRes = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders WHERE status = 'Confirmed'");
    $totalRevenue = mysqli_fetch_assoc($revenueRes)['total'] ?? 0;

    return [
        'total_users'    => $totalUsers,
        'pending_orders' => $pendingOrders,
        'total_revenue'  => $totalRevenue
    ];
}
?>