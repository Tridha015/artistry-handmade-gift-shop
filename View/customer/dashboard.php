<?php
session_start();
require_once __DIR__ . '/../../Model/OrderModel.php';
 
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}
 
$pageTitle = "My Orders - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';
 
$customerId = $_SESSION['user_id'];
$orders = getOrdersByCustomer($customerId);
?>
 
<div style="max-width: 1100px; margin: 30px auto; font-family: sans-serif; padding: 0 20px;">
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
<div>
<h2>My Activity & Orders</h2>
<p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
</div>
<a href="custom_order.php" style="background-color: rgb(87, 37, 83); color: white; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold;">+ Request Custom Craft</a>
</div>
 
    <?php if (isset($_GET['success'])): ?>
<div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
<?php echo htmlspecialchars($_GET['success']); ?>
</div>
<?php endif; ?>
 
    <h3>Placed Orders & Custom Requests</h3>
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
<thead>
<tr style="background-color: #572553; color: white;">
<th>Order Type</th>
<th>Item / Requirements</th>
<th>Amount (৳)</th>
<th>Order Status</th>
<th>Delivery Progress</th>
</tr>
</thead>
<tbody>
<?php if (mysqli_num_rows($orders) > 0): ?>
<?php while ($ord = mysqli_fetch_assoc($orders)): ?>
<tr>
<td><strong><?php echo htmlspecialchars($ord['order_type']); ?></strong></td>
<td><?php echo htmlspecialchars($ord['item_name']); ?></td>
<td>৳ <?php echo number_format($ord['amount'], 2); ?></td>
<td>
<span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; background-color: <?php echo ($ord['status'] === 'Accepted' || $ord['status'] === 'Confirmed') ? '#28a745' : (($ord['status'] === 'Rejected') ? '#dc3545' : '#ffc107'); ?>;">
<?php echo htmlspecialchars($ord['status']); ?>
</span>
</td>
<td>
<?php if ($ord['delivery_status'] !== 'N/A' && !empty($ord['delivery_status'])): ?>
<span style="font-weight: bold; color: #2b6cb0;"><?php echo htmlspecialchars($ord['delivery_status']); ?></span>
<?php else: ?>
<span style="color: #888;">Reviewing / In Workshop</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="5" style="text-align: center; color: #888; padding: 20px;">You haven't placed any orders yet.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
 
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>