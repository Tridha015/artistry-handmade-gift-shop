<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $pageTitle ?? 'Artistry of Tridha'; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<nav class="navbar">
<a href="../../index.php" class="brand">
        <img src="../../assets/images/Logo.png" alt="Artistry Logo" class="brand-logo">
        
    </a>
    
    <div class="nav-links">
        <?php if (isset($userRole)): ?>
            <span class="user-badge"><?php echo htmlspecialchars($userRole); ?></span>
            <a href="../../index.php">Home</a>
            <a href="../../index.php">Logout</a>
        <?php else: ?>
            <a href="../../index.php">Home</a>
            <a href="../../View/customer/custom_order.php">Custom Order</a>
            <a href="../../View/auth/login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>

<div class="main-wrapper">