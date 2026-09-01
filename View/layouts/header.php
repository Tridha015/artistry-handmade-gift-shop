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
<style>
        .header-search-wrapper {
            position: relative;
            display: inline-block;
            margin: 0 15px;
        }
        .header-search-box {
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 5px 14px;
        }
        .header-search-box input {
            border: none;
            outline: none;
            font-size: 13px;
            width: 170px;
            background: transparent;
            color: #333;
        }
        .search-results-dropdown {
            display: none;
            position: absolute;
            top: 38px;
            left: 0;
            width: 300px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            z-index: 9999;
            max-height: 380px;
            overflow-y: auto;
        }
</style>
</head>
<body>
<nav class="navbar">
<a href="<?php echo $base_url; ?>/index.php" class="brand">
<img src="<?php echo (file_exists($_SERVER['DOCUMENT_ROOT'] . '/artistry/assets/images/Logo.png')) ? $base_url . '/assets/images/Logo.png' : $base_url . '/assets/images/logo.png'; ?>" alt="Artistry Logo" class="brand-logo">
</a>
 
    <!-- Live AJAX Search Bar (Aarong Style) -->
<div class="header-search-wrapper">
<div class="header-search-box">
<span style="color: #666; font-size: 13px; margin-right: 6px;">🔍</span>
<input type="text" id="live-search-input" placeholder="Search crafts, albums..." autocomplete="off">
</div>
<div id="search-results-dropdown" class="search-results-dropdown"></div>
</div>
<div class="nav-links">
<a href="<?php echo $base_url; ?>/index.php">Home</a>
<a href="<?php echo $base_url; ?>/View/customer/custom_order.php">Custom Order</a>
<!-- Cart Link -->
<a href="<?php echo $base_url; ?>/View/customer/cart.php" style="display: inline-flex; align-items: center; gap: 5px;">
            🛒 Cart
<?php if ($cart_count > 0): ?>
<span id="cart-counter" style="background-color: #f1c40f; color: #1a202c; font-size: 11px; font-weight: bold; border-radius: 50%; padding: 2px 6px;"><?php echo $cart_count; ?></span>
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
<a href="<?php echo $base_url; ?>/Controller/AuthController.php?action=logout" style="color: #fc8181;">Logout</a>
<?php else: ?>
<a href="<?php echo $base_url; ?>/View/auth/login.php">Login</a>
<a href="<?php echo $base_url; ?>/View/auth/register.php">Register</a>
<?php endif; ?>
</div>
</nav>
<div class="main-wrapper">
 
<!-- Live Search JavaScript Engine -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search-input');
    const dropdown = document.getElementById('search-results-dropdown');
    const baseUrl = "<?php echo $base_url; ?>";
 
    if (!searchInput || !dropdown) return;
 
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
 
        if (query.length < 1) {
            dropdown.style.display = 'none';
            dropdown.innerHTML = '';
            return;
        }
 
        const xhr = new XMLHttpRequest();
        xhr.open('GET', baseUrl + '/Controller/ProductController.php?action=live_search&q=' + encodeURIComponent(query), true);
 
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.status === 'success') {
                        if (res.data.length > 0) {
                            let html = '<ul style="list-style: none; margin: 0; padding: 0;">';
                            res.data.forEach(item => {
                                html += `
<li style="border-bottom: 1px solid #edf2f7; padding: 10px 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; transition: background 0.2s;" 
                                        onmouseover="this.style.background='#f7fafc'" 
                                        onmouseout="this.style.background='#fff'"
                                        onclick="window.location.href='${baseUrl}/index.php?category=${encodeURIComponent(item.category)}#store-products'">
<img src="${baseUrl}/assets/images/uploads/${item.image}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;" onerror="this.src='${baseUrl}/assets/images/sample1.jpg';">
<div style="flex-grow: 1; text-align: left;">
<div style="font-size: 13px; font-weight: bold; color: #2d3748;">${item.title}</div>
<div style="display: flex; justify-content: space-between; margin-top: 2px;">
<span style="font-size: 11px; background: #edf2f7; color: #4a5568; padding: 1px 5px; border-radius: 3px;">${item.category}</span>
<span style="font-size: 12px; font-weight: bold; color: #2b6cb0;">৳ ${item.price}</span>
</div>
</div>
</li>
                                `;
                            });
                            html += '</ul>';
                            dropdown.innerHTML = html;
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.innerHTML = '<div style="padding: 14px; font-size: 13px; color: #a0aec0; text-align: center;">No matching crafts found</div>';
                            dropdown.style.display = 'block';
                        }
                    }
                } catch(e) {
                    console.error('Search JSON parsing error', e);
                }
            }
        };
        xhr.send();
    });
 
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
});
</script>