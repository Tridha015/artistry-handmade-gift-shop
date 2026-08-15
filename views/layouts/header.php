<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artistry of Tridha - Handmade Gift Shop</title>
    <link rel="stylesheet" href="/artistry/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">🎨 Artistry of Tridha</div>
    <ul class="nav-links">
        <li><a href="/artistry/index.php">Home</a></li>
        <li><a href="/artistry/views/custom_order/form.php">Custom Order</a></li>
        <li><a href="/artistry/index.php#categories">Categories</a></li>

        <?php if (isset($_SESSION['user_name'])): ?>
            <li><a href="#" style="color: #7c3aed; font-weight: bold;">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
            <li><a href="/artistry/controllers/AuthController.php?action=logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/artistry/views/auth/login.php">Login</a></li>
            <li><a href="/artistry/views/auth/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>