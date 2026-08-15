<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password)) {
        header("Location: ../views/auth/login.php?error=Invalid email or password");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if (isset($_POST['remember'])) {
            setcookie('artistry_user', $user['email'], time() + (86400 * 30), "/");
        }

        // Role-based redirection
        if ($user['role'] === 'admin') {
            header("Location: ../views/dashboard/admin.php");
        } elseif ($user['role'] === 'seller') {
            header("Location: ../views/dashboard/seller.php");
        } elseif ($user['role'] === 'delivery') {
            header("Location: ../views/dashboard/delivery.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        header("Location: ../views/auth/login.php?error=Incorrect email or password");
        exit();
    }
}

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$phone || strlen($password) < 6) {
        header("Location: ../views/auth/register.php?error=All fields are required (password min 6 chars)");
        exit();
    }

    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        header("Location: ../views/auth/register.php?error=Email already registered");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
    $stmt->execute([$name, $email, $phone, $hashedPassword]);

    header("Location: ../views/auth/login.php?success=Registration successful. Please login.");
    exit();
}

if ($action === 'logout') {
    session_destroy();
    setcookie('artistry_user', '', time() - 3600, "/");
    header("Location: ../index.php");
    exit();
}