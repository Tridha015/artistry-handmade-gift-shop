<?php
session_start();
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Customer login required");
    exit();
}

global $conn;
$orderId = intval($_GET['order_id'] ?? 0);
$customerId = intval($_SESSION['user_id'] ?? 0);

$query = mysqli_query($conn, "SELECT * FROM custom_orders WHERE id = $orderId AND customer_id = $customerId LIMIT 1");
$order = mysqli_fetch_assoc($query);

if (!$order || $order['status'] !== 'Accepted') {
    header("Location: cart.php?error=Invalid custom order for checkout");
    exit();
}
?>

<div style="max-width: 650px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 6px; border: 1px solid #ddd; font-family: sans-serif;">
    <h2 style="color: #4a154b; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">💳 Custom Order Payment</h2>
    
    <div style="background: #fdf6f0; border-left: 4px solid #c05621; padding: 15px; margin-bottom: 25px; border-radius: 3px;">
        <h4 style="margin: 0 0 5px 0; color: #4a154b;">#CR-<?php echo $order['id']; ?>: <?php echo htmlspecialchars($order['craft_type']); ?></h4>
        <p style="margin: 0; color: #555; font-size: 13px;">Size: <?php echo htmlspecialchars($order['craft_size']); ?> | Layers: <?php echo $order['layers']; ?> | Theme: <?php echo htmlspecialchars($order['color_theme']); ?></p>
        <h3 style="margin: 10px 0 0 0; color: #28a745;">Payable Amount: ৳ <?php echo number_format($order['budget'], 2); ?></h3>
    </div>

    <form action="../../Controller/OrderController.php" method="POST">
        <input type="hidden" name="action" value="place_custom_payment">
        <input type="hidden" name="custom_order_id" value="<?php echo $order['id']; ?>">
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Delivery Address</label>
            <textarea name="delivery_address" rows="3" required placeholder="House / Flat, Road, Area details" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 6px;">Payment Gateway</label>
            <select name="payment_gateway" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="bKash">bKash (Merchant: 01852275057)</option>
                <option value="Nagad">Nagad (Merchant: 01852275057)</option>
                <option value="Rocket">Rocket (Merchant: 01852275057)</option>
            </select>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Sender Number</label>
                <input type="text" name="sender_number" placeholder="e.g. 018XXXXXXXX" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 6px;">Transaction ID (TrxID)</label>
                <input type="text" name="trx_id" placeholder="e.g. TRX893278" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="cart.php" style="color: #666; text-decoration: none; font-size: 14px;">← Back</a>
            <button type="submit" style="background: #28a745; color: white; border: none; padding: 12px 25px; border-radius: 4px; font-weight: bold; font-size: 15px; cursor: pointer;">Place Order Now</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>