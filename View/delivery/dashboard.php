<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Delivery') {
    header("Location: ../auth/login.php?error=Unauthorized Access. Please login as Delivery Agent.");
    exit();
}

require_once __DIR__ . '/../../Model/DeliveryModel.php';
$pageTitle = "Delivery Agent Dashboard - Artistry";
require_once __DIR__ . '/../layouts/header.php';

$riderId = intval($_SESSION['user_id'] ?? 0);
$assignedParcels  = getAssignedParcels($riderId);
$completedParcels = getCompletedDeliveries($riderId);
?>

<div style="max-width: 1100px; margin: 30px auto 60px auto; font-family: sans-serif; padding: 0 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 25px;">
        <div>
            <h2 style="color: #4a154b; margin: 0 0 5px 0;">🛵 Delivery Agent Dashboard</h2>
            <p style="margin: 0; color: #666;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Agent'); ?></strong></p>
        </div>
        <span style="background: #28a745; color: white; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: bold;">● Active on Duty</span>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 25px; margin-bottom: 35px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2d3748; border-bottom: 1px solid #eee; padding-bottom: 10px;">📦 Assigned Active Deliveries</h3>
        
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
            <thead>
                <tr style="background-color: #572553; color: white;">
                    <th>Order ID</th>
                    <th>Customer Info</th>
                    <th>Delivery Address</th>
                    <th>Item & Price</th>
                    <th>Current Status</th>
                    <th>Update Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($assignedParcels && mysqli_num_rows($assignedParcels) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($assignedParcels)): ?>
                        <tr>
                            <td><b>#ORD-<?php echo $row['order_id']; ?></b></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['customer_name'] ?? 'Customer'); ?></strong><br>
                                <small>📞 <?php echo htmlspecialchars($row['customer_phone'] ?? 'N/A'); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['delivery_address'] ?? 'No address provided'); ?></td>
                            <td>
                                <?php echo htmlspecialchars($row['product_name'] ?? 'Craft Item'); ?><br>
                                <strong style="color: #2b6cb0;">৳ <?php echo number_format($row['total_price'] ?? 0, 2); ?></strong>
                            </td>
                            <td>
                                <span style="background: #f39c12; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                                    <?php echo htmlspecialchars($row['delivery_status']); ?>
                                </span>
                            </td>
                            <td>
                                <form action="../../Controller/DeliveryController.php" method="POST" style="margin: 0; display: flex; gap: 6px;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="delivery_id" value="<?php echo $row['delivery_id']; ?>">
                                    <select name="new_status" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                                        <option value="Picked Up" <?php echo ($row['delivery_status'] === 'Picked Up') ? 'selected' : ''; ?>>Picked Up</option>
                                        <option value="Out for Delivery" <?php echo ($row['delivery_status'] === 'Out for Delivery') ? 'selected' : ''; ?>>Out for Delivery</option>
                                        <option value="Delivered">Delivered ✅</option>
                                        <option value="Failed" <?php echo ($row['delivery_status'] === 'Failed') ? 'selected' : ''; ?>>Failed / Return</option>
                                    </select>
                                    <button type="submit" style="background: #4a154b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #718096; padding: 35px 20px;">
                            No pending parcels assigned to you at the moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 4px; border: 1px solid #d2d6dc;">
        <span style="color: #666; font-size: 14px;">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Agent'); ?></strong></span>
        <a href="../../Controller/AuthController.php?action=logout" style="background-color: #e53e3e; color: white; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">Logout from Delivery Panel</a>
    </div>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>