<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Please login as Customer to checkout");
    exit();
}
 
$cartItems = $_SESSION['cart'] ?? array();
if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}
 
$grandTotal = 0;
foreach ($cartItems as $item) {
    $grandTotal += ($item['price'] * $item['quantity']);
}
 
$pageTitle = "Checkout - Artistry";
require_once __DIR__ . '/../layouts/header.php';
?>
 
<div style="max-width: 650px; margin: 30px auto; font-family: sans-serif; padding: 25px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
<h2 style="text-align: center; color: #572553; margin-bottom: 20px;">Complete Your Order</h2>
 
    <div style="background: #fdf2e9; padding: 12px; border-left: 4px solid #e67e22; margin-bottom: 20px;">
<p style="margin: 0; font-size: 14px; color: #333;">Total Payable Amount: <strong>৳ <?php echo number_format($grandTotal, 2); ?></strong></p>
<p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">Send Money / Merchant Payment to: <b>01852-275057</b> (bKash/Nagad)</p>
</div>
 
    <form action="../../Controller/OrderController.php" method="POST">
<input type="hidden" name="action" value="place_cart_order">
<input type="hidden" name="total_amount" value="<?php echo $grandTotal; ?>">
 
        <div style="margin-bottom: 15px;">
<label><strong>Delivery Address</strong></label><br>
<textarea name="delivery_address" required rows="3" placeholder="Full delivery address (House, Road, Area, City)" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;"></textarea>
</div>
 
        <div style="margin-bottom: 15px;">
<label><strong>Payment Gateway</strong></label><br>
<select name="payment_gateway" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
<option value="bKash">bKash (Send Money / Payment)</option>
<option value="Nagad">Nagad</option>
<option value="Rocket">Rocket</option>
</select>
</div>
 
        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
<div style="flex: 1;">
<label><strong>Sender Account Number</strong></label><br>
<input type="text" name="sender_number" required placeholder="017xxxxxxxx" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
</div>
<div style="flex: 1;">
<label><strong>Transaction ID (TrxID)</strong></label><br>
<input type="text" name="trx_id" required placeholder="e.g. 9J28DA..." style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
</div>
</div>
 
        <button type="submit" style="width: 100%; padding: 12px; background-color: rgb(87, 37, 83); color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer;">
            Confirm & Place Order
</button>
</form>
</div>
 
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>