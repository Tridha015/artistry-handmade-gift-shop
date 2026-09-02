<?php
require_once __DIR__ . '/../config/db.php';

function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getProductsByCategory(string $category) {
    global $conn;
    $category = mysqli_real_escape_string($conn, $category);
    $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getProductsBySeller(int $seller_id) {
    global $conn;
    $seller_id = intval($seller_id);
    $sql = "SELECT * FROM products WHERE seller_id = $seller_id ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

function getProductById(int $id) {
    global $conn;
    $id = intval($id);
    $sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function searchProductsLive(string $keyword) {
    global $conn;
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $sql = "SELECT id, title, price, image, category FROM products 
            WHERE title LIKE '%$keyword%' OR category LIKE '%$keyword%' 
            ORDER BY id DESC LIMIT 6";
    return mysqli_query($conn, $sql);
}

function addProduct(int $seller_id, string $title, string $category, float $price, int $stock, string $description, string $image) {
    global $conn;
    $seller_id   = intval($seller_id);
    $title       = mysqli_real_escape_string($conn, $title);
    $category    = mysqli_real_escape_string($conn, $category);
    $price       = floatval($price);
    $stock       = intval($stock);
    $description = mysqli_real_escape_string($conn, $description);
    $image       = mysqli_real_escape_string($conn, $image);

    $sql = "INSERT INTO products (seller_id, title, category, price, stock, description, image) 
            VALUES ($seller_id, '$title', '$category', $price, $stock, '$description', '$image')";
    return mysqli_query($conn, $sql);
}

function updateProduct(int $id, int $seller_id, string $title, string $category, float $price, int $stock, string $description, ?string $image = null) {
    global $conn;
    $id          = intval($id);
    $seller_id   = intval($seller_id);
    $title       = mysqli_real_escape_string($conn, $title);
    $category    = mysqli_real_escape_string($conn, $category);
    $price       = floatval($price);
    $stock       = intval($stock);
    $description = mysqli_real_escape_string($conn, $description);

    if ($image) {
        $image = mysqli_real_escape_string($conn, $image);
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, description = '$description', image = '$image' WHERE id = $id AND seller_id = $seller_id";
    } else {
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, description = '$description' WHERE id = $id AND seller_id = $seller_id";
    }
    return mysqli_query($conn, $sql);
}

function deleteProduct(int $id, int $seller_id) {
    global $conn;
    $id        = intval($id);
    $seller_id = intval($seller_id);
    $sql = "DELETE FROM products WHERE id = $id AND seller_id = $seller_id";
    return mysqli_query($conn, $sql);
}
?>