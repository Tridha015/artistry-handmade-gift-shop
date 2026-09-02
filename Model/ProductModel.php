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

function getProductById(int $id, ?int $seller_id = null) {
    global $conn;
    $id = intval($id);
    if ($seller_id !== null) {
        $seller_id = intval($seller_id);
        $sql = "SELECT * FROM products WHERE id = $id AND seller_id = $seller_id LIMIT 1";
    } else {
        $sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
    }
    $query = mysqli_query($conn, $sql);
    if ($query && mysqli_num_rows($query) > 0) {
        return mysqli_fetch_assoc($query);
    }
    return null;
}

function searchProductsLive(string $keyword) {
    global $conn;
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $sql = "SELECT id, title, price, image, category FROM products 
            WHERE title LIKE '%$keyword%' OR category LIKE '%$keyword%' 
            ORDER BY id DESC LIMIT 6";
    return mysqli_query($conn, $sql);
}

function addProduct(int $seller_id, string $title, string $category, float $price, int $stock, ?string $size, string $description, string $image) {
    global $conn;
    $seller_id   = intval($seller_id);
    $title       = mysqli_real_escape_string($conn, $title);
    $category    = mysqli_real_escape_string($conn, $category);
    $price       = floatval($price);
    $stock       = intval($stock);
    $size        = mysqli_real_escape_string($conn, (string)$size);
    $description = mysqli_real_escape_string($conn, $description);
    $image       = mysqli_real_escape_string($conn, $image);

    $sql = "INSERT INTO products (seller_id, title, category, price, stock, size, description, image) 
            VALUES ($seller_id, '$title', '$category', $price, $stock, '$size', '$description', '$image')";
    return mysqli_query($conn, $sql);
}

function updateProduct(int $id, int $seller_id, string $title, string $category, float $price, int $stock, ?string $size, string $description, ?string $image = null) {
    global $conn;
    $id          = intval($id);
    $seller_id   = intval($seller_id);
    $title       = mysqli_real_escape_string($conn, $title);
    $category    = mysqli_real_escape_string($conn, $category);
    $price       = floatval($price);
    $stock       = intval($stock);
    $size        = mysqli_real_escape_string($conn, (string)$size);
    $description = mysqli_real_escape_string($conn, $description);

    if ($image) {
        $image = mysqli_real_escape_string($conn, $image);
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, size = '$size', description = '$description', image = '$image' WHERE id = $id AND seller_id = $seller_id";
    } else {
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, size = '$size', description = '$description' WHERE id = $id AND seller_id = $seller_id";
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