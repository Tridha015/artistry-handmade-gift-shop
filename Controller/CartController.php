<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/ProductModel.php';

global $conn;

$action = $_REQUEST['action'] ?? '';

if ($action === 'add' || $action === 'add_to_cart') {
    $productId = intval($_REQUEST['product_id'] ?? $_REQUEST['id'] ?? $_REQUEST['item_id'] ?? 0);
    $quantity  = intval($_REQUEST['quantity'] ?? 1);
    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($productId > 0) {
        $product = null;
        if (function_exists('getProductById')) {
            $product = getProductById($productId);
        }
        
        if (!$product) {
            $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $productId LIMIT 1");
            if ($res && mysqli_num_rows($res) > 0) {
                $product = mysqli_fetch_assoc($res);
            }
        }

        if ($product) {
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            $key = (string)$productId;
            $title = $product['title'] ?? $product['name'] ?? 'Handmade Craft';
            $price = floatval($product['price'] ?? 0);
            $image = !empty($product['image']) ? $product['image'] : 'sample1.jpg';

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$key] = [
                    'id'       => $productId,
                    'title'    => $title,
                    'price'    => $price,
                    'quantity' => $quantity,
                    'image'    => $image
                ];
            }

            header("Location: ../View/customer/cart.php?success=" . urlencode("Item added to cart!"));
            exit();
        }
    }

    header("Location: ../View/customer/cart.php?error=" . urlencode("Product not found (ID: $productId)"));
    exit();
}

if ($action === 'remove') {
    $rawKey = isset($_GET['id']) ? trim($_GET['id']) : '';

    if (!empty($rawKey) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach (array_keys($_SESSION['cart']) as $cartKey) {
            if ((string)$cartKey === (string)$rawKey) {
                unset($_SESSION['cart'][$cartKey]);
                break;
            }
        }
    }

    header("Location: ../View/customer/cart.php?success=" . urlencode("Item removed from cart!"));
    exit();
}

if ($action === 'clear') {
    unset($_SESSION['cart']);
    header("Location: ../View/customer/cart.php?success=" . urlencode("Cart cleared successfully!"));
    exit();
}

header("Location: ../View/customer/cart.php");
exit();
?>