<?php
require_once __DIR__ . '/config/db.php';

$password = '123456';
$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = '$hashed', status = 'Active', role = 'Admin' WHERE email = 'admin@artistry.com'";

if (mysqli_query($conn, $sql)) {
    echo "<h2 style='color:green;'>Admin Password Updated Successfully to: 123456</h2>";
    echo "<a href='View/auth/login.php'>Go to Login</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>