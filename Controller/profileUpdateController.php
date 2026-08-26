<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/auth/login.php?error=Please login first");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_SESSION['user_id']);
    $name   = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email  = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone  = mysqli_real_escape_string($conn, trim($_POST['phone']));

    if (empty($name) || empty($email) || empty($phone)) {
        header("Location: ../View/customer/profile.php?error=All fields are required");
        exit();
    }

    $sql = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE id = $userId";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_name'] = $name;
        header("Location: ../View/customer/profile.php?success=Profile updated successfully!");
    } else {
        header("Location: ../View/customer/profile.php?error=Failed to update profile.");
    }
    exit();
}
?>