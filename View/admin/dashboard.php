<?php
session_start();
require_once __DIR__ . '/../../Model/AdminModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Admin - Artistry of Tridha";
$userRole = "Admin";
require_once __DIR__ . '/../layouts/header.php';

$stats = getAdminStats();
$customOrders = getAllCustomOrders();
$storeOrders = getAllStoreOrders();
$registrations = getAllRegistrations();
$riders = getActiveRiders();

$riderList = array();
while ($r = mysqli_fetch_assoc($riders)) {
    $riderList[] = $r;
}
?>

<div class="stats-grid">
    <div class="stat-box">
        <h4>Total Revenue</h4>
        <div class="count" style="color: #2b6cb0;"><?php echo number_format($stats['revenue'], 2); ?> ৳</div>
    </div>
    <div class="stat-box">
        <h4>Custom Requests</h4>
        <div class="count" style="color: #c05621;"><?php echo $stats['custom_pending']; ?> Pending</div>
    </div>
    <div class="stat-box">
        <h4>Active Sellers</h4>
        <div class="count"><?php echo $stats['sellers']; ?> Artisans</div>
    </div>
    <div class="stat-box">
        <h4>Delivery Riders</h4>
        <div class="count"><?php echo $stats['riders']; ?> Active</div>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 15px 0;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<div class="panel">
    <div class="panel-header">
        <h3>Custom Craft Requests (Review & Pricing)</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Customer Name</th>
                <th>Craft Requirements</th>
                <th>Sample Reference</th>
                <th>Offered Budget</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($customOrders) > 0): ?>
                <?php while ($co = mysqli_fetch_assoc($customOrders)): ?>
                    <tr>
                        <td>#CR-<?php echo $co['id']; ?></td>
                        <td>
                            <?php echo htmlspecialchars($co['customer_name']); ?><br>
                            <small style="color:#718096;">📞 <?php echo htmlspecialchars($co['customer_phone']); ?></small>
                        </td>
                        <td>
                            <b><?php echo htmlspecialchars($co['craft_type']); ?></b> 
                            (<?php echo htmlspecialchars($co['craft_size']); ?>, <?php echo $co['layers']; ?> Layers, Theme: <?php echo htmlspecialchars($co['color_theme']); ?>)
                            <br><small style="color: #555;">Note: <?php echo htmlspecialchars($co['instructions']); ?></small>
                        </td>
                        <td>
                            <a href="../../assets/images/uploads/<?php echo $co['sample_image']; ?>" target="_blank">
                                <img src="../../assets/images/uploads/<?php echo $co['sample_image']; ?>" alt="Sample" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;" onerror="this.src='../../assets/images/sample1.jpg';">
                            </a>
                        </td>
                        <td><?php echo number_format($co['budget'], 2); ?> ৳</td>
                        <td>
                            <span class="badge <?php echo ($co['status'] === 'Accepted') ? 'badge-ready' : (($co['status'] === 'Rejected') ? 'badge-danger' : 'badge-pending'); ?>">
                                <?php echo htmlspecialchars($co['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($co['status'] === 'Pending Review'): ?>
                                <a href="../../Controller/AdminController.php?action=accept&order_id=<?php echo $co['id']; ?>" class="btn-action btn-approve" style="display:inline-block; text-decoration:none; text-align:center;">Accept</a>
                                <br><br>
                                <a href="../../Controller/AdminController.php?action=reject&order_id=<?php echo $co['id']; ?>" class="btn-action btn-reject" style="display:inline-block; text-decoration:none; text-align:center;">Reject</a>
                            <?php else: ?>
                                <small style="color: #888;">Processed</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #888;">No custom craft requests submitted yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Store Orders & Payment Verification</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer & Craft</th>
                <th>Payment Info</th>
                <th>Amount</th>
                <th>Assign Rider</th>
                <th>Status & Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($storeOrders) > 0): ?>
                <?php while ($ord = mysqli_fetch_assoc($storeOrders)): ?>
                    <tr>
                        <td><b>#ORD-<?php echo $ord['id']; ?></b></td>
                        <td>
                            <strong><?php echo htmlspecialchars($ord['customer_name']); ?></strong> (📞 <?php echo htmlspecialchars($ord['customer_phone']); ?>)<br>
                            <small><?php echo htmlspecialchars($ord['product_name']); ?> (Qty: <?php echo $ord['quantity']; ?>)</small><br>
                            <small style="color: #666;">📍 <?php echo htmlspecialchars($ord['delivery_address']); ?></small>
                        </td>
                        <td>
                            <b>Gateway:</b> <?php echo htmlspecialchars($ord['payment_gateway']); ?><br>
                            <small>Sender: <?php echo htmlspecialchars($ord['sender_number']); ?></small><br>
                            <small>TrxID: <code><?php echo htmlspecialchars($ord['trx_id']); ?></code></small>
                        </td>
                        <td><?php echo number_format($ord['total_price'], 2); ?> ৳</td>
                        <td>
                            <form action="../../Controller/AdminController.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="assign_rider">
                                <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                <input type="hidden" name="address" value="<?php echo htmlspecialchars($ord['delivery_address']); ?>">
                                <select name="rider_id" class="table-select" onchange="this.form.submit()">
                                    <option value="">-- Select Rider --</option>
                                    <?php foreach ($riderList as $rider): ?>
                                        <option value="<?php echo $rider['id']; ?>" <?php echo (isset($ord['rider_id']) && $ord['rider_id'] == $rider['id']) ? 'selected' : ''; ?>>
                                            Rider: <?php echo htmlspecialchars($rider['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge <?php echo ($ord['status'] === 'Confirmed') ? 'badge-ready' : 'badge-making'; ?>">
                                <?php echo htmlspecialchars($ord['status']); ?>
                            </span>
                            <?php if ($ord['status'] === 'Payment pending'): ?>
                                <br><br>
                                <a href="../../Controller/AdminController.php?action=confirm_order&order_id=<?php echo $ord['id']; ?>" style="color: #28a745; font-weight: bold; text-decoration: none; font-size: 12px; border: 1px solid #28a745; padding: 3px 6px; border-radius: 3px;">Verify & Confirm</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888;">No store orders placed yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>User & Artisan Approval Management</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Approval Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($registrations) > 0): ?>
                <?php while ($u = mysqli_fetch_assoc($registrations)): ?>
                    <tr>
                        <td>#UID-<?php echo $u['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['phone']); ?></td>
                        <td><b><?php echo htmlspecialchars($u['role']); ?></b></td>
                        <td>
                            <span class="badge <?php echo ($u['status'] === 'Active') ? 'badge-ready' : (($u['status'] === 'Pending') ? 'badge-pending' : 'badge-danger'); ?>">
                                <?php echo htmlspecialchars($u['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'Pending' || $u['status'] === 'Suspended'): ?>
                                <a href="../../Controller/AdminController.php?action=approve_user&user_id=<?php echo $u['id']; ?>" style="color: green; font-weight: bold; text-decoration: none; margin-right: 8px;">Approve</a>
                            <?php endif; ?>
                            <?php if ($u['status'] === 'Active'): ?>
                                <a href="../../Controller/AdminController.php?action=suspend_user&user_id=<?php echo $u['id']; ?>" style="color: red; font-weight: bold; text-decoration: none;">Suspend</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #888;">No registered users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>