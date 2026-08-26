<?php
session_start();
require_once __DIR__ . '/../Model/ProductModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../View/auth/login.php?error=Access Denied! Seller only.");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $seller_id = $_SESSION['user_id'];
    $title     = trim($_POST['title']);
    $category  = trim($_POST['category']);
    $price     = trim($_POST['price']);
    $stock     = trim($_POST['stock']);

    if (empty($title) || empty($category) || empty($price) || empty($stock)) {
        header("Location: ../View/seller/add_product.php?error=All fields are required");
        exit();
    }

    $imageName = 'default_craft.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['image']['tmp_name'];
        $fileName      = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
            $uploadPath  = __DIR__ . '/../assets/images/uploads/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $imageName = $newFileName;
            }
        } else {
            header("Location: ../View/seller/add_product.php?error=Invalid file format. Upload JPG, PNG, or WEBP.");
            exit();
        }
    }

    $isAdded = addProduct($seller_id, $title, $category, $price, $stock, $imageName);

    if ($isAdded) {
        header("Location: ../View/seller/dashboard.php?success=Product added successfully!");
    } else {
        header("Location: ../View/seller/add_product.php?error=Failed to add product.");
    }
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'edit_product') {
    $seller_id  = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $title      = trim($_POST['title']);
    $category   = trim($_POST['category']);
    $price      = trim($_POST['price']);
    $stock      = trim($_POST['stock']);

    if (empty($title) || empty($category) || empty($price) || empty($stock)) {
        header("Location: ../View/seller/edit_product.php?id=$product_id&error=All fields are required");
        exit();
    }

    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['image']['tmp_name'];
        $fileName      = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
            $uploadPath  = __DIR__ . '/../assets/images/uploads/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $imageName = $newFileName;
            }
        }
    }

    $isUpdated = updateProduct($product_id, $seller_id, $title, $category, $price, $stock, $imageName);

    if ($isUpdated) {
        header("Location: ../View/seller/dashboard.php?success=Product updated successfully!");
    } else {
        header("Location: ../View/seller/edit_product.php?id=$product_id&error=Failed to update product.");
    }
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $seller_id  = $_SESSION['user_id'];
    $product_id = intval($_GET['id']);

    deleteProduct($product_id, $seller_id);
    header("Location: ../View/seller/dashboard.php?success=Product deleted successfully!");
    exit();
}
?>