<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/OrderModel.php';

global $conn;

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../View/auth/login.php?error=Access Denied! Customer login required.");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'customer_reject_custom' && isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    $customerId = intval($_SESSION['user_id'] ?? 0);

    if ($orderId > 0 && $customerId > 0) {
        mysqli_query($conn, "UPDATE custom_orders SET status = 'Rejected' WHERE id = $orderId AND customer_id = $customerId");
        
        $cartKey = 'custom_' . $orderId;
        if (isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
        }

        header("Location: ../View/customer/cart.php?success=Quotation declined and request cancelled.");
        exit();
    }
    header("Location: ../View/customer/cart.php?error=Action failed");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'place_custom_payment') {
    $customerId      = intval($_SESSION['user_id']);
    $customOrderId   = intval($_POST['custom_order_id'] ?? 0);
    $deliveryAddress = mysqli_real_escape_string($conn, trim($_POST['delivery_address']));
    $paymentGateway  = mysqli_real_escape_string($conn, trim($_POST['payment_gateway']));
    $senderNumber    = mysqli_real_escape_string($conn, trim($_POST['sender_number']));
    $trxId           = mysqli_real_escape_string($conn, trim($_POST['trx_id']));

    if ($customOrderId > 0 && !empty($deliveryAddress) && !empty($senderNumber) && !empty($trxId)) {
        $check = mysqli_query($conn, "SELECT budget, craft_type FROM custom_orders WHERE id = $customOrderId AND customer_id = $customerId LIMIT 1");
        $co = mysqli_fetch_assoc($check);
        $totalPrice = floatval($co['budget'] ?? 0);

        $anyProd = mysqli_query($conn, "SELECT id FROM products LIMIT 1");
        $anyRow = mysqli_fetch_assoc($anyProd);
        $prodId = intval($anyRow['id'] ?? 1);

        $customTrx = "CUSTOM-" . $customOrderId . "-" . $trxId;

        $sql = "INSERT INTO orders (customer_id, product_id, quantity, total_price, payment_gateway, sender_number, trx_id, delivery_address, status) 
                VALUES ($customerId, $prodId, 1, $totalPrice, '$paymentGateway', '$senderNumber', '$customTrx', '$deliveryAddress', 'Payment pending')";
        mysqli_query($conn, $sql);

        $cartKey = 'custom_' . $customOrderId;
        if (isset($_SESSION['cart'][$cartKey])) {
            unset($_SESSION['cart'][$cartKey]);
        }

        header("Location: ../View/customer/cart.php?success=Payment submitted! Verification in progress.");
        exit();
    }

    header("Location: ../View/customer/custom_checkout.php?order_id=$customOrderId&error=Please fill all fields");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'submit_custom_order') {
    $customerId   = intval($_SESSION['user_id']);
    $craftType    = trim($_POST['craft_type']);
    $craftSize    = trim($_POST['craft_size']);
    $layers       = trim($_POST['layers']);
    $colorTheme   = trim($_POST['color_theme']);
    $budget       = floatval($_POST['budget']);
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
    $customerId      = intval($_SESSION['user_id']);
    $deliveryAddress = mysqli_real_escape_string($conn, trim($_POST['delivery_address']));
    $paymentGateway  = mysqli_real_escape_string($conn, trim($_POST['payment_gateway']));
    $senderNumber    = mysqli_real_escape_string($conn, trim($_POST['sender_number']));
    $trxId           = mysqli_real_escape_string($conn, trim($_POST['trx_id']));
    $cartItems       = $_SESSION['cart'] ?? array();

    if (empty($deliveryAddress) || empty($senderNumber) || empty($trxId) || empty($cartItems)) {
        header("Location: ../View/customer/checkout.php?error=All fields are required");
        exit();
    }

    $anyProd = mysqli_query($conn, "SELECT id FROM products LIMIT 1");
    $anyRow = mysqli_fetch_assoc($anyProd);
    $defaultProdId = intval($anyRow['id'] ?? 1);

    foreach ($cartItems as $key => $item) {
        $rawId     = intval($item['id']);
        $quantity  = intval($item['quantity']);
        $itemTotal = floatval($item['price'] * $quantity);

        $productId = ($rawId > 0) ? $rawId : $defaultProdId;

        $sql = "INSERT INTO orders (customer_id, product_id, quantity, total_price, payment_gateway, sender_number, trx_id, delivery_address, status) 
                VALUES ($customerId, $productId, $quantity, $itemTotal, '$paymentGateway', '$senderNumber', '$trxId', '$deliveryAddress', 'Payment pending')";
        mysqli_query($conn, $sql);

        if ($rawId > 0) {
            mysqli_query($conn, "UPDATE products SET stock = stock - $quantity WHERE id = $rawId AND stock >= $quantity");
        }
    }

    unset($_SESSION['cart']);
    header("Location: ../View/customer/cart.php?success=Order placed successfully! Verifying your request.");
    exit();
}

header("Location: ../View/customer/cart.php");
exit();
?>