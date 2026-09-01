<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/ProductModel.php';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $seller_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;
    $title     = trim($_POST['title'] ?? '');
    $category  = trim($_POST['category'] ?? '');
    $price     = floatval($_POST['price'] ?? 0);
    $stock     = intval($_POST['stock'] ?? 1);

    if (empty($title) || empty($category) || $price <= 0) {
        header("Location: ../View/seller/add_product.php?error=Please fill all required fields correctly");
        exit();
    }

    $imageName = 'default_craft.jpg';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/images/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($ext, $allowed)) {
            $imageName = time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
        }
    }

    $isAdded = addProduct($seller_id, $title, $category, $price, $stock, $imageName);

    if ($isAdded) {
        echo "<script>window.location.href = '../index.php?success=Craft uploaded successfully!#store-products';</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=../index.php?success=Craft uploaded successfully!#store-products'></noscript>";
        exit();
    } else {
        die("<div style='font-family:sans-serif; padding:20px; color:red;'><h3>Database Error:</h3>" . mysqli_error($conn) . "</div>");
    }
}

// Handle Delete Product
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $product_id = intval($_GET['id']);
    $seller_id  = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    deleteProduct($product_id, $seller_id);
    header("Location: ../View/seller/dashboard.php?success=Product removed");
    exit();
}

// AJAX Inline Stock & Price Update for Seller
if (isset($_POST['action']) && $_POST['action'] === 'ajax_update_stock_price') {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit();
    }

    $productId = intval($_POST['product_id'] ?? 0);
    $stock     = intval($_POST['stock'] ?? 0);
    $price     = floatval($_POST['price'] ?? 0);
    $sellerId  = intval($_SESSION['user_id']);

    if ($productId > 0 && $price > 0) {
        $sql = "UPDATE products SET stock = $stock, price = $price WHERE id = $productId AND seller_id = $sellerId";
        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Stock & Price updated successfully!']);
            exit();
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'Update failed!']);
    exit();
}
?>