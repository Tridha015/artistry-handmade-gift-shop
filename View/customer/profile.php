<?php
session_start();
require_once __DIR__ . '/../../Model/UserModel.php';
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Customer Profile - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';

$customerId = intval($_SESSION['user_id']);
$user = getUserById($customerId);
$username = $user['name'] ?? $_SESSION['user_name'] ?? "Valued Customer";

global $conn;

$deliveredOrdersQuery = mysqli_query($conn, "SELECT o.*, p.title AS product_name, p.image AS product_image 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    JOIN deliveries d ON o.id = d.order_id 
    WHERE o.customer_id = $customerId AND d.delivery_status = 'Delivered' 
    ORDER BY o.id DESC");
?>

<style>
    .profile-banner { width: 85%; margin: 25px auto; background: #ffffff; padding: 25px 30px; border: 1px solid #dddddd; border-radius: 4px; display: flex; align-items: center; gap: 20px; box-sizing: border-box; }
    .profile-avatar { width: 80px; height: 80px; background: #ebd2f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #4a235a; border: 1px solid #d2b4de; }
    .profile-info h2 { margin: 0 0 5px 0; color: #222; font-size: 24px; }
    .profile-info p { margin: 0 0 8px 0; color: #666; font-size: 14px; }
    .badge-active { background: #d5f5e3; color: #1e8449; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }

    .content-panel { width: 85%; margin: 0 auto 30px auto; background: #ffffff; padding: 30px; border: 1px solid #dddddd; border-radius: 4px; box-sizing: border-box; }
    .panel-header { border-bottom: 2px solid #eeeeee; padding-bottom: 12px; margin-bottom: 20px; }
    .panel-header h3 { margin: 0 0 5px 0; color: #222; font-size: 18px; }
    .panel-header p { margin: 0; color: #666; font-size: 13px; }
    
    .input-row { margin-bottom: 18px; overflow: hidden; }
    .input-half { width: 48%; float: left; }
    .input-half-right { float: right; }
    .input-row label { display: block; font-weight: bold; margin-bottom: 6px; color: #222; font-size: 14px; }
    .input-row input[type="text"], 
    .input-row input[type="email"], 
    .input-row textarea { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
    
    .btn-group { margin-top: 25px; display: flex; justify-content: space-between; align-items: center; }
    .btn-save { background-color: #4a235a; color: white; padding: 10px 22px; border: none; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; }
    .btn-save:hover { background-color: #381a44; }
    .btn-back { background-color: #e2e8f0; color: #333; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-size: 15px; font-weight: bold; display: inline-block; }
    .btn-back:hover { background-color: #cbd5e1; }

    .order-history-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 10px; }
    .order-history-table th, .order-history-table td { padding: 12px; border: 1px solid #eeeeee; font-size: 13px; }
    .order-history-table th { background-color: #f8fafc; color: #333; font-weight: bold; }
    .badge-delivered { background-color: #c6f6d5; color: #22543d; padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; display: inline-block; }

    .logout-box { width: 85%; margin: 0 auto 50px auto; background: #ffffff; padding: 20px 30px; border: 1px solid #dddddd; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; }
    .btn-logout-danger { background-color: #e53e3e; color: white; padding: 10px 24px; text-decoration: none; border-radius: 4px; font-size: 14px; font-weight: bold; display: inline-block; }
    .btn-logout-danger:hover { background-color: #c53030; }
</style>

<div class="profile-banner">
    <div class="profile-avatar">👤</div>
    <div class="profile-info">
        <h2><?php echo htmlspecialchars($username); ?></h2>
        <p>Member since: August 2026 | Role: <b>Customer</b></p>
        <span class="badge-active">Active Account</span>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div style="width: 85%; margin: 0 auto 20px auto; background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; font-weight: bold;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<div class="content-panel">
    <div class="panel-header">
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

<div class="content-panel">
    <div class="panel-header">
        <h3>📜 Order History (Completed Deliveries)</h3>
        <p>List of all crafts and orders that have been successfully delivered to you.</p>
    </div>

    <table class="order-history-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Item & Preview</th>
                <th>Quantity</th>
                <th>Total Paid</th>
                <th>Delivery Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($deliveredOrdersQuery && mysqli_num_rows($deliveredOrdersQuery) > 0): ?>
                <?php while ($ord = mysqli_fetch_assoc($deliveredOrdersQuery)): ?>
                    <tr>
                        <td><b>#ORD-<?php echo $ord['id']; ?></b></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="../../assets/images/uploads/<?php echo htmlspecialchars($ord['product_image']); ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='../../assets/images/sample1.jpg';">
                                <span><?php echo htmlspecialchars($ord['product_name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo $ord['quantity']; ?></td>
                        <td><strong><?php echo number_format($ord['total_price'], 2); ?> ৳</strong></td>
                        <td>
                            <span class="badge-delivered">✔ Delivered</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #888; padding: 25px;">No completed delivery records found yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="logout-box">
    <span style="color: #666; font-size: 14px;">Session Management</span>
    <a href="../../Controller/AuthController.php?action=logout" class="btn-logout-danger">Logout from Account</a>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>