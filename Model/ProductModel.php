<?php
require_once __DIR__ . '/../config/db.php';

// ১. সব প্রোডাক্ট পাওয়ার ফাংশন
function getAllProducts() {
    global $conn;
    $sql = "SELECT * FROM products ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

// ২. ক্যাটাগরি অনুযায়ী ফিল্টার করা
function getProductsByCategory($category) {
    global $conn;
    $category = mysqli_real_escape_string($conn, $category);
    $sql = "SELECT * FROM products WHERE category = '$category' ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

// ৩. নির্দিষ্ট সেলারের প্রোডাক্ট লিস্ট পাওয়া
function getProductsBySeller($seller_id) {
    global $conn;
    $seller_id = intval($seller_id);
    $sql = "SELECT * FROM products WHERE seller_id = $seller_id ORDER BY id DESC";
    return mysqli_query($conn, $sql);
}

// ৪. আইডি দিয়ে নির্দিষ্ট ১টি প্রোডাক্ট আনা
function getProductById($id) {
    global $conn;
    $id = intval($id);
    $sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : false;
}

// ৫. নতুন প্রোডাক্ট ডেটাবেজে যুক্ত করা
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

// ৬. প্রোডাক্ট আপডেট করা
function updateProduct($id, $seller_id, $title, $category, $price, $stock, $image = null) {
    global $conn;
    $id        = intval($id);
    $seller_id = intval($seller_id);
    $title     = mysqli_real_escape_string($conn, $title);
    $category  = mysqli_real_escape_string($conn, $category);
    $price     = floatval($price);
    $stock     = intval($stock);

    if (!empty($image)) {
        $image = mysqli_real_escape_string($conn, $image);
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock, image = '$image' 
                WHERE id = $id AND seller_id = $seller_id";
    } else {
        $sql = "UPDATE products SET title = '$title', category = '$category', price = $price, stock = $stock 
                WHERE id = $id AND seller_id = $seller_id";
    }

    return mysqli_query($conn, $sql);
}

// ৭. প্রোডাক্ট মুছে ফেলা
function deleteProduct($id, $seller_id) {
    global $conn;
    $id        = intval($id);
    $seller_id = intval($seller_id);
    $sql = "DELETE FROM products WHERE id = $id AND seller_id = $seller_id";
    return mysqli_query($conn, $sql);
}
?>