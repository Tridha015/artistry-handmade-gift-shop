<?php
session_start();
require_once __DIR__ . '/../Model/ProductModel.php';
 
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
 
if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $productId = intval($_POST['product_id']);
    $quantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
 
    global $conn;
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id = $productId");
    $product = mysqli_fetch_assoc($query);
 
    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = array(
                'id'       => $product['id'],
                'title'    => $product['title'],
                'price'    => $product['price'],
                'image'    => $product['image'],
                'quantity' => $quantity
            );
        }
        header("Location: ../View/customer/cart.php?success=Craft added to cart!");
    } else {
        header("Location: ../index.php?error=Product not found");
    }
    exit();
}
 
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    header("Location: ../View/customer/cart.php?success=Item removed from cart");
    exit();
}
 
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['cart']);
    header("Location: ../View/customer/cart.php");
    exit();
}
?>