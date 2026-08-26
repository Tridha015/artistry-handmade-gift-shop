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
 
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
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
        header("Location: ../View/customer/dashboard.php?success=Custom craft request submitted successfully!");
    } else {
        header("Location: ../View/customer/custom_order.php?error=Failed to submit request.");
    }
    exit();
}
?>