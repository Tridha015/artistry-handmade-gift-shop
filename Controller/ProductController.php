<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/ProductModel.php';

// ১. Live Search AJAX Handler
if (isset($_GET['action']) && $_GET['action'] === 'live_search') {
    header('Content-Type: application/json');
    $query = trim($_GET['q'] ?? '');

    if (strlen($query) > 0) {
        $result = searchProductsLive($query);
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = [
                    'id'       => $row['id'],
                    'title'    => $row['title'],
                    'price'    => number_format($row['price'], 2),
                    'image'    => $row['image'],
                    'category' => $row['category']
                ];
            }
        }
        echo json_encode(['status' => 'success', 'data' => $products]);
    } else {
        echo json_encode(['status' => 'success', 'data' => []]);
    }
    exit();
}

// ২. Seller Inline Stock & Price Update AJAX
if (isset($_POST['action']) && $_POST['action'] === 'ajax_update_stock_price') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'seller') {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized Access']);
        exit();
    }

    $productId = intval($_POST['product_id'] ?? 0);
    $stock     = intval($_POST['stock'] ?? 0);
    $price     = floatval($_POST['price'] ?? 0);
    $sellerId  = intval($_SESSION['user_id'] ?? 0);

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

// ৩. Delete Product
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $productId = intval($_GET['id'] ?? 0);
    $sellerId  = intval($_SESSION['user_id'] ?? 0);

    if ($productId > 0 && $sellerId > 0) {
        deleteProduct($productId, $sellerId);
        header("Location: ../View/seller/dashboard.php?success=Product deleted successfully");
        exit();
    }
}

// ৪. Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $sellerId    = intval($_SESSION['user_id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = floatval($_POST['price'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $size        = trim($_POST['size'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $imageName = 'sample1.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", basename($_FILES['image']['name']));
        $uploadFileDir = __DIR__ . '/../assets/images/uploads/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $imageName = $fileName;
        }
    }

    if (!empty($title) && $price > 0) {
        $res = addProduct($sellerId, $title, $category, $price, $stock, $size, $description, $imageName);
        if ($res) {
            header("Location: ../View/seller/dashboard.php?success=Product added successfully");
            exit();
        } else {
            header("Location: ../View/seller/add_product.php?error=Database insertion failed");
            exit();
        }
    } else {
        header("Location: ../View/seller/add_product.php?error=Invalid input fields");
        exit();
    }
}

// ৫. Edit Product 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_product') {
    $productId   = intval($_POST['product_id'] ?? 0);
    $sellerId    = intval($_SESSION['user_id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $price       = floatval($_POST['price'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $size        = trim($_POST['size'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $newImageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", basename($_FILES['image']['name']));
        $uploadFileDir = __DIR__ . '/../assets/images/uploads/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $newImageName = $fileName;
        }
    }

    if ($productId > 0 && !empty($title) && $price > 0) {
        updateProduct($productId, $sellerId, $title, $category, $price, $stock, $size, $description, $newImageName);
        header("Location: ../View/seller/dashboard.php?success=Product updated successfully");
        exit();
    } else {
        header("Location: ../View/seller/edit_product.php?id=$productId&error=Invalid input fields");
        exit();
    }
}

header("Location: ../View/seller/dashboard.php");
exit();