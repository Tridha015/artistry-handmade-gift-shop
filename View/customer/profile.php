<?php
session_start();
require_once __DIR__ . '/../../Model/UserModel.php';
require_once __DIR__ . '/../../Model/OrderModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Customer Profile - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';

$customerId = $_SESSION['user_id'];
$user = getUserById($customerId);
$orders = getOrdersByCustomer($customerId);
$username = $user['name'] ?? $_SESSION['user_name'] ?? "Valued Customer";
?>

<style>
    .profile-banner { width: 80%; margin: 25px auto; background: #ffffff; padding: 25px 30px; border: 1px solid #dddddd; border-radius: 4px; display: flex; align-items: center; gap: 20px; box-sizing: border-box; }
    .profile-avatar { width: 80px; height: 80px; background: #ebd2f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #4a235a; border: 1px solid #d2b4de; }
    .profile-info h2 { margin: 0 0 5px 0; color: #222; font-size: 24px; }
    .profile-info p { margin: 0 0 8px 0; color: #666; font-size: 14px; }
    .badge-active { background: #d5f5e3; color: #1e8449; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }

    .form-container { width: 80%; margin: 0 auto 30px auto; background: #ffffff; padding: 30px; border: 1px solid #dddddd; border-radius: 4px; box-sizing: border-box; }
    .form-header { border-bottom: 2px solid #eeeeee; padding-bottom: 12px; margin-bottom: 20px; }
    .form-header h3 { margin: 0 0 5px 0; color: #222; font-size: 18px; }
    .form-header p { margin: 0; color: #666; font-size: 13px; }
    
    .input-row { margin-bottom: 18px; overflow: hidden; }
    .input-half { width: 48%; float: left; }
    .input-half-right { float: right; }
    .input-row label { display: block; font-weight: bold; margin-bottom: 6px; color: #222; font-size: 14px; }
    .input-row input[type="text"], 
    .input-row input[type="email"], 
    .input-row textarea { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
    
    .btn-group { margin-top: 25px; overflow: hidden; }
    .btn-save { background-color: #4a235a; color: white; padding: 10px 22px; border: none; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; float: right; }
    .btn-save:hover { background-color: #381a44; }
    .btn-back { background-color: #e2e8f0; color: #333; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-size: 15px; font-weight: bold; display: inline-block; }
    .btn-back:hover { background-color: #cbd5e1; }

    /* Orders Section Styles */
    .orders-container { width: 80%; margin: 0 auto 50px auto; background: #ffffff; padding: 30px; border: 1px solid #dddddd; border-radius: 4px; box-sizing: border-box; }
    .order-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px; }
    .order-table th { background-color: #4a235a; color: white; padding: 12px; font-size: 14px; }
    .order-table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
</style>

<!-- ব্যানার -->
<div class="profile-banner">
    <div class="profile-avatar">👤</div>
    <div class="profile-info">
        <h2><?php echo htmlspecialchars($username); ?></h2>
        <p>Member since: August 2026 | Role: <b>Customer</b></p>
        <span class="badge-active">Active Account</span>
    </div>
</div>

<!-- পার্সোনাল ও ডেলিভারি ডিটেইলস ফর্ম -->
<div class="form-container">
    <div class="form-header">
        <h3>📍 Personal & Delivery Details</h3>
        <p>Keep your contact and shipping information up-to-date for smooth craft deliveries.</p>
    </div>

    <form action="../../Controller/profileUpdateController.php" method="POST">
        <div class="input-row">
            <div class="input-half">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="input-half input-half-right">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="input-row">
            <div class="input-half">
                <label>Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>
            <div class="input-half input-half-right">
                <label>Alternate Contact (Optional)</label>
                <input type="text" name="alt_contact" placeholder="e.g. 01811-000000">
            </div>
        </div>

        <div class="input-row" style="clear: both;">
            <label>Primary Delivery Address</label>
            <textarea name="address" rows="3" placeholder="House / Flat, Road, Area details"></textarea>
        </div>

        <div class="input-row">
            <div class="input-half">
                <label>City / Area</label>
                <input type="text" name="city" value="Dhaka">
            </div>
            <div class="input-half input-half-right">
                <label>Postal Code</label>
                <input type="text" name="postal_code" value="1209">
            </div>
        </div>

        <div class="btn-group" style="clear: both;">
            <a href="../../index.php" class="btn-back">← Back to Shop</a>
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
    </form>
</div>

<!-- নিচের My Orders & Custom Requests সেকশন -->
<div class="orders-container">
    <div class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>📦 My Orders & Custom Requests</h3>
            <p>Track live progress of your handmade crafts and custom orders.</p>
        </div>
        <a href="custom_order.php" style="background-color: #4a235a; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 13px;">+ Request Custom Craft</a>
    </div>

    <table class="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Type</th>
                <th>Craft Item / Details</th>
                <th>Amount (৳)</th>
                <th>Status</th>
                <th>Delivery Progress</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders && mysqli_num_rows($orders) > 0): ?>
                <?php while ($ord = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($ord['id']); ?></td>
                        <td><strong><?php echo htmlspecialchars($ord['order_type']); ?></strong></td>
                        <td><?php echo htmlspecialchars($ord['item_name']); ?></td>
                        <td>৳ <?php echo number_format($ord['amount'], 2); ?></td>
                        <td>
                            <?php 
                                $status = $ord['status'];
                                $bgColor = '#ffc107';
                                if ($status === 'Accepted' || $status === 'Confirmed') {
                                    $bgColor = '#28a745';
                                } elseif ($status === 'Rejected' || $status === 'Cancelled') {
                                    $bgColor = '#dc3545';
                                }
                            ?>
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; background-color: <?php echo $bgColor; ?>;">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($ord['delivery_status']) && $ord['delivery_status'] !== 'N/A'): ?>
                                <span style="font-weight: bold; color: #2b6cb0;"><?php echo htmlspecialchars($ord['delivery_status']); ?></span>
                            <?php else: ?>
                                <span style="color: #888;">Reviewing / In Workshop</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; padding: 30px;">
                        No orders or custom requests found.<br><br>
                        <a href="custom_order.php" style="color: #4a235a; font-weight: bold; text-decoration: underline;">Place your first custom craft request</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>