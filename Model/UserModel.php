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
?>