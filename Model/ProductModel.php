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

function getAllProducts() {
    global $conn;
    $sql = "SELECT p.*, u.name AS artisan_name 
            FROM products p 
            JOIN users u ON p.seller_id = u.id 
            ORDER BY p.id DESC";
    return mysqli_query($conn, $sql);
}
?>