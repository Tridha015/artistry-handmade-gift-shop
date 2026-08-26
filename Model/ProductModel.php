<?php
require_once __DIR__ . '/../config/db.php';

function addProduct($seller_id, $title, $category, $price, $stock, $image) {
    global $conn;
    $seller_id = intval($seller_id);
    $title     = mysqli_real_escape_string($conn, $title);
    $category  = mysqli_real_escape_string($conn, $category);
    $price     = floatval($price);
    $stock     = intval($stock);
    $image     = mysqli_real_escape_string($conn, $image);

    $sql = "INSERT INTO products (seller_id, title, category, price, stock, image) 
            VALUES ($seller_id, '$title', '$category', $price, $stock, '$image')";
            
    return mysqli_query($conn, $sql);
}

function getProductsBySeller($seller_id) {
    global $conn;
    $seller_id = intval($seller_id);
    $sql = "SELECT * FROM products WHERE seller_id = $seller_id ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getProductById($productId, $sellerId) {
    global $conn;
    $productId = intval($productId);
    $sellerId  = intval($sellerId);
    $sql = "SELECT * FROM products WHERE id = $productId AND seller_id = $sellerId";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}

function updateProduct($productId, $sellerId, $title, $category, $price, $stock, $image = null) {
    global $conn;
    $productId = intval($productId);
    $sellerId  = intval($sellerId);
    $title     = mysqli_real_escape_string($conn, $title);
    $category  = mysqli_real_escape_string($conn, $category);
    $price     = floatval($price);
    $stock     = intval($stock);

    if ($image) {
        $image = mysqli_real_escape_string($conn, $image);
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, image = '$image' 
                WHERE id = $productId AND seller_id = $sellerId";
    } else {
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock 
                WHERE id = $productId AND seller_id = $sellerId";
    }

    return mysqli_query($conn, $sql);
}

function deleteProduct($productId, $sellerId) {
    global $conn;
    $productId = intval($productId);
    $sellerId  = intval($sellerId);
    $sql = "DELETE FROM products WHERE id = $productId AND seller_id = $sellerId";
    return mysqli_query($conn, $sql);
}

function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}
?>