<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = "/artistry";

$cart_count = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Artistry of Tridha'; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="<?php echo $base_url; ?>/index.php" class="brand">
        <img src="<?php echo (file_exists($_SERVER['DOCUMENT_ROOT'] . '/artistry/assets/images/Logo.png')) ? $base_url . '/assets/images/Logo.png' : $base_url . '/assets/images/logo.png'; ?>" alt="Artistry Logo" class="brand-logo">
    </a>
    
    <div class="nav-links">
        <a href="<?php echo $base_url; ?>/index.php">Home</a>
        <a href="<?php echo $base_url; ?>/View/customer/custom_order.php">Custom Order</a>
        
        <!-- Cart Link -->
        <a href="<?php echo $base_url; ?>/View/customer/cart.php" style="display: inline-flex; align-items: center; gap: 5px;">
            🛒 Cart
            <?php if ($cart_count > 0): ?>
                <span style="background-color: #f1c40f; color: #1a202c; font-size: 11px; font-weight: bold; border-radius: 50%; padding: 2px 6px;"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>

        <?php if (isset($_SESSION['user_role'])): ?>
            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                <a href="<?php echo $base_url; ?>/View/admin/dashboard.php">Admin Panel</a>
            <?php elseif ($_SESSION['user_role'] === 'Seller'): ?>
                <a href="<?php echo $base_url; ?>/View/seller/dashboard.php">Seller Dashboard</a>
            <?php elseif ($_SESSION['user_role'] === 'Customer'): ?>
                <a href="<?php echo $base_url; ?>/View/customer/profile.php">My Profile (<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>)</a>
            <?php elseif ($_SESSION['user_role'] === 'Delivery'): ?>
                <a href="<?php echo $base_url; ?>/View/delivery/dashboard.php">Delivery Panel</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>/View/auth/login.php">Login</a>
            <a href="<?php echo $base_url; ?>/View/auth/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
<div class="main-wrapper">