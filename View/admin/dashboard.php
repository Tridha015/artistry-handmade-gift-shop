<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Model/AdminModel.php';

if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') {
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
if ($riders) {
    while ($r = mysqli_fetch_assoc($riders)) {
        $riderList[] = $r;
    }
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
        <div class="count"><span id="stat-sellers"><?php echo $stats['sellers']; ?></span> Artisans</div>
    </div>
    <div class="stat-box">
        <h4>Delivery Riders</h4>
        <div class="count"><span id="stat-riders"><?php echo $stats['riders']; ?></span> Active</div>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin: 15px 0; font-weight: bold;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin: 15px 0; font-weight: bold;">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="panel">
    <div class="panel-header">
        <h3>Custom Craft Requests</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Customer Name</th>
                <th>Craft Requirements</th>
                <th>Sample</th>
                <th>Offered Budget</th>
                <th>Status</th>
                <th>Review & Pricing Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($customOrders && mysqli_num_rows($customOrders) > 0): ?>
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
                                <img src="../../assets/images/uploads/<?php echo $co['sample_image']; ?>" alt="Sample" style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;" onerror="this.src='../../assets/images/sample1.jpg';">
                            </a>
                        </td>
                        <td><strong><?php echo number_format($co['budget'], 2); ?> ৳</strong></td>
                        <td>
                            <span class="badge <?php echo ($co['status'] === 'Accepted') ? 'badge-ready' : (($co['status'] === 'Rejected') ? 'badge-danger' : 'badge-pending'); ?>">
                                <?php echo htmlspecialchars($co['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($co['status'] === 'Pending Review'): ?>
                                <form action="../../Controller/AdminController.php" method="POST" style="margin: 0; display: flex; flex-direction: column; gap: 5px;">
                                    <input type="hidden" name="action" value="accept_and_quote">
                                    <input type="hidden" name="order_id" value="<?php echo $co['id']; ?>">
                                    <input type="number" step="0.01" name="final_price" placeholder="Set Price (৳)" value="<?php echo ($co['budget'] > 0) ? $co['budget'] : ''; ?>" required style="padding: 5px; font-size: 12px; border: 1px solid #ccc; border-radius: 4px; width: 120px;">
                                    <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 8px; border-radius: 3px; cursor: pointer; font-size: 11px; font-weight: bold;">Accept & Send Price</button>
                                </form>
                                <a href="../../Controller/AdminController.php?action=reject&order_id=<?php echo $co['id']; ?>" style="color: #e53e3e; font-size: 11px; text-decoration: none; margin-top: 4px; display: inline-block;">Reject Request</a>
                            <?php elseif ($co['status'] === 'Accepted'): ?>
                                <span style="color: #2b6cb0; font-size: 12px; font-weight: bold;">Quotation Sent (Waiting for Customer)</span>
                            <?php else: ?>
                                <small style="color: #888;"><?php echo htmlspecialchars($co['status']); ?></small>
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
            <?php if ($storeOrders && mysqli_num_rows($storeOrders) > 0): ?>
                <?php while ($ord = mysqli_fetch_assoc($storeOrders)): ?>
                    <?php $isConfirmed = (strtolower($ord['status']) === 'confirmed'); ?>
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
                            <?php if ($isConfirmed): ?>
                                <form action="../../Controller/AdminController.php" method="POST" style="margin: 0; display: flex; flex-direction: column; gap: 5px;">
                                    <input type="hidden" name="action" value="assign_rider">
                                    <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                    <input type="hidden" name="address" value="<?php echo htmlspecialchars($ord['delivery_address']); ?>">
                                    <select name="rider_id" class="table-select" required style="font-size: 12px; padding: 4px;">
                                        <option value="">-- Select Rider --</option>
                                        <?php foreach ($riderList as $rider): ?>
                                            <option value="<?php echo $rider['id']; ?>" <?php echo (isset($ord['rider_id']) && $ord['rider_id'] == $rider['id']) ? 'selected' : ''; ?>>
                                                Rider: <?php echo htmlspecialchars($rider['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" style="background: #2b6cb0; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; cursor: pointer;">
                                        <?php echo (!empty($ord['rider_id'])) ? 'Re-assign Rider' : 'Assign Rider'; ?>
                                    </button>
                                </form>
                                <?php if (!empty($ord['rider_name'])): ?>
                                    <small style="color: #28a745; font-weight: bold; margin-top: 3px; display: block;">Assigned: <?php echo htmlspecialchars($ord['rider_name']); ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <small style="color: #718096;">Verify payment to enable rider assignment</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo $isConfirmed ? 'badge-ready' : 'badge-making'; ?>">
                                <?php echo $isConfirmed ? 'Order Confirmed' : 'Verifying Request'; ?>
                            </span>
                            <?php if (!$isConfirmed): ?>
                                <br><br>
                                <a href="../../Controller/AdminController.php?action=confirm_order&order_id=<?php echo $ord['id']; ?>" style="color: #28a745; font-weight: bold; text-decoration: none; font-size: 12px; border: 1px solid #28a745; padding: 4px 8px; border-radius: 3px; background: #eafaf1; display: inline-block;">Verify & Confirm</a>
                            <?php else: ?>
                                <br><small style="color: #28a745; font-weight: bold;">✔ Verified & Crafting</small>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($registrations && mysqli_num_rows($registrations) > 0): ?>
                <?php while ($u = mysqli_fetch_assoc($registrations)): ?>
                    <tr>
                        <td>#UID-<?php echo $u['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['phone']); ?></td>
                        <td><b><?php echo htmlspecialchars($u['role']); ?></b></td>
                        <td>
                            <span id="user-badge-<?php echo $u['id']; ?>" class="badge <?php echo ($u['status'] === 'Active') ? 'badge-ready' : (($u['status'] === 'Pending') ? 'badge-pending' : 'badge-danger'); ?>">
                                <?php echo htmlspecialchars($u['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php $targetStatus = ($u['status'] === 'Active') ? 'Suspended' : 'Active'; ?>
                            <button type="button" 
                                    id="btn-user-<?php echo $u['id']; ?>" 
                                    onclick="toggleUserStatus(<?php echo $u['id']; ?>, '<?php echo $targetStatus; ?>')" 
                                    style="background: <?php echo ($u['status'] === 'Active') ? '#e53e3e' : '#28a745'; ?>; color: white; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
                                <?php echo ($u['status'] === 'Active') ? 'Suspend' : 'Approve'; ?>
                            </button>
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

<div style="margin: 30px 0 50px 0; display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 4px; border: 1px solid #d2d6dc;">
    <div>
        <span style="color: #666; font-size: 14px;">Logged in as: <strong>Admin</strong></span>
    </div>
    <div>
        <a href="../../index.php" style="background-color: #e2e8f0; color: #333; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; margin-right: 10px;">View Shop</a>
        <a href="../../Controller/AuthController.php?action=logout" style="background-color: #e53e3e; color: white; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-block;">Logout</a>
    </div>
</div>

<script>
function toggleUserStatus(userId, targetStatus) {
    const btn = document.getElementById('btn-user-' + userId);
    const badge = document.getElementById('user-badge-' + userId);
    const originalText = btn.innerText;

    btn.innerText = 'Updating...';
    btn.disabled = true;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Controller/AdminController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        btn.disabled = false;
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    badge.innerText = res.new_status;
                    badge.className = 'badge ' + (res.new_status === 'Active' ? 'badge-ready' : 'badge-danger');

                    const nextStatus = (res.new_status === 'Active') ? 'Suspended' : 'Active';
                    btn.innerText = (res.new_status === 'Active') ? 'Suspend' : 'Approve';
                    btn.style.backgroundColor = (res.new_status === 'Active') ? '#e53e3e' : '#28a745';
                    btn.setAttribute('onclick', `toggleUserStatus(${userId}, '${nextStatus}')`);

                    if (res.riders_count !== undefined) {
                        document.getElementById('stat-riders').innerText = res.riders_count;
                    }
                    if (res.sellers_count !== undefined) {
                        document.getElementById('stat-sellers').innerText = res.sellers_count;
                    }
                } else {
                    alert('Error: ' + res.message);
                    btn.innerText = originalText;
                }
            } catch(e) {
                console.error(e);
                btn.innerText = originalText;
            }
        } else {
            alert('Server error occurred');
            btn.innerText = originalText;
        }
    };

    xhr.send('action=ajax_toggle_user_status&user_id=' + encodeURIComponent(userId) + '&new_status=' + encodeURIComponent(targetStatus));
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>