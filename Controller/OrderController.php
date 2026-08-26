<?php
session_start();
require_once __DIR__ . '/../Model/OrderModel.php';
 
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../View/auth/login.php?error=Access Denied! Customer login required.");
    exit();
}
 
if (isset($_POST['action']) && $_POST['action'] === 'submit_custom_order') {
    $customerId   = $_SESSION['user_id'];
    $craftType    = trim($_POST['craft_type']);
    $craftSize    = trim($_POST['craft_size']);
    $layers       = trim($_POST['layers']);
    $colorTheme   = trim($_POST['color_theme']);
    $budget       = trim($_POST['budget']);
    $instructions = trim($_POST['instructions']);
 
    if (empty($craftType) || empty($craftSize) || empty($budget)) {
        header("Location: ../View/customer/custom_order.php?error=Please fill all required fields");
        exit();
    }
 
    $sampleImage = 'sample1.jpg';
    if (isset($_FILES['sample_image']) && $_FILES['sample_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['sample_image']['tmp_name'];
        $fileName      = $_FILES['sample_image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
 
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = time() . '_custom_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
            $uploadPath  = __DIR__ . '/../assets/images/uploads/' . $newFileName;
 
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $sampleImage = $newFileName;
            }
        }
    }
 
    $isCreated = createCustomOrder($customerId, $craftType, $craftSize, $layers, $colorTheme, $budget, $sampleImage, $instructions);
 
    if ($isCreated) {
        header("Location: ../View/customer/cart.php?success=Custom craft request submitted successfully!");
        } else {
        header("Location: ../View/customer/custom_order.php?error=Failed to submit request.");
    }
    exit();
}
 
if (isset($_POST['action']) && $_POST['action'] === 'place_cart_order') {
    $customerId      = $_SESSION['user_id'];
    $deliveryAddress = trim($_POST['delivery_address']);
    $paymentGateway  = trim($_POST['payment_gateway']);
    $senderNumber    = trim($_POST['sender_number']);
    $trxId           = trim($_POST['trx_id']);
    $cartItems       = $_SESSION['cart'] ?? array();
 
    if (empty($deliveryAddress) || empty($senderNumber) || empty($trxId) || empty($cartItems)) {
        header("Location: ../View/customer/checkout.php?error=All fields are required");
        exit();
    }
 
    global $conn;
    foreach ($cartItems as $item) {
        $productId = intval($item['id']);
        $quantity  = intval($item['quantity']);
        $itemTotal = floatval($item['price'] * $quantity);
 
        $sql = "INSERT INTO orders (customer_id, product_id, quantity, total_price, payment_gateway, sender_number, trx_id, delivery_address, status) 
                VALUES ($customerId, $productId, $quantity, $itemTotal, '$paymentGateway', '$senderNumber', '$trxId', '$deliveryAddress', 'Payment pending')";
        mysqli_query($conn, $sql);
 
        mysqli_query($conn, "UPDATE products SET stock = stock - $quantity WHERE id = $productId AND stock >= $quantity");
    }
 
    unset($_SESSION['cart']);
    header("Location: ../View/customer/cart.php?success=Order placed successfully! Awaiting payment verification.");    exit();
}
?>