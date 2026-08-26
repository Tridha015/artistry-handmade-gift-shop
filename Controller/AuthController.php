<?php
session_start();
require_once __DIR__ . '/../Model/UserModel.php';

if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if ($role === 'Admin') {
        header("Location: ../View/auth/register.php?error=Admin registration is not allowed");
        exit();
    }

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        header("Location: ../View/auth/register.php?error=All fields are required");
        exit();
    }

    $existingUser = getUserByEmail($email);
    if ($existingUser) {
        header("Location: ../View/auth/register.php?error=Email already exists");
        exit();
    }

    $isRegistered = registerUser($name, $email, $phone, $password, $role);

    if ($isRegistered) {
        if ($role === 'Customer') {
            header("Location: ../View/auth/login.php?success=Account created successfully! Please login.");
        } else {
            header("Location: ../View/auth/login.php?success=Application submitted! Please wait for Admin approval.");
        }
    } else {
        header("Location: ../View/auth/register.php?error=Registration failed. Try again.");
    }
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: ../View/auth/login.php?error=Email and password required");
        exit();
    }

    $user = getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'Pending') {
            header("Location: ../View/auth/login.php?error=Your account is pending admin approval.");
            exit();
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'Admin') {
            header("Location: ../View/admin/dashboard.php");
        } elseif ($user['role'] === 'Seller') {
            header("Location: ../View/seller/dashboard.php");
        } elseif ($user['role'] === 'Delivery') {
            header("Location: ../View/delivery/dashboard.php");
        } else {
            // Customer সরাসরি Homepage এ রিডাইরেক্ট হবে
            header("Location: ../index.php?success=Login successful! Welcome back.");
        }
        exit();
    } else {
        header("Location: ../View/auth/login.php?error=Invalid email or password");
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../index.php?success=Logged out successfully");
    exit();
}
?>