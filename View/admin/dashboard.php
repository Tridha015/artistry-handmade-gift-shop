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
$riders = getActiveRiders();

$riderList = [];
while ($r = mysqli_fetch_assoc($riders)) {
    $riderList[] = $r;
}
?>

<div class="stats-grid">
    <div class="stat-box">
        <h4>Total Revenue</h4>
        <div class="count" style="color: #2b6cb0;">42,500 ৳</div>
    </div>
    <div class="stat-box">
        <h4>Custom Requests</h4>
        <div class="count" style="color: #c05621;">8 Pending</div>
    </div>
    <div class="stat-box">
        <h4>Active Sellers</h4>
        <div class="count">1 Artisans</div>
    </div>
    <div class="stat-box">
        <h4>Delivery Riders</h4>
        <div class="count">3 Active</div>
    </div>
</div>

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
            <tr>
                <td>#CR-201</td>
                <td>Nafisa Ali<br><small style="color:#718096;">📞 01711-234567</small></td>
                <td><b>Explosion Box</b> (3 Layers, Maroon Theme, 15 Photos)</td>
                <td>

                    <a href="../../assets/images/sample1.jpg" target="_blank">
                        <img src="../../assets/images/sample1.jpg" alt="Sample" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                    </a>
                    <br><small style="color: #2b6cb0; font-size: 11px;">View Full Image</small>
                </td>
                <td>1,800 ৳</td>
                <td><span class="badge badge-pending">Under Review</span></td>
                <td>
                    <button class="btn-action btn-approve">Accept</button>
                    <br><br>
                    <button class="btn-action btn-reject">Reject</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel">
    <div class="panel-header">
        <h3>Store Orders & Delivery Assignment</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Craft Details</th>
                <th>Delivery Address</th>
                <th>Amount</th>
                <th>Assign Rider</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($storeOrders) > 0): ?>
                <?php while ($ord = mysqli_fetch_assoc($storeOrders)): ?>
                    <tr>
                        <td><b>#ORD-<?php echo $ord['id']; ?></b></td>
                        <td><?php echo htmlspecialchars($ord['product_name']); ?> (Qty: <?php echo $ord['quantity']; ?>)</td>
                        <td><?php echo htmlspecialchars($ord['delivery_address']); ?></td>
                        <td><?php echo number_format($ord['total_price'], 2); ?> ৳</td>
                        <td>
                            <form action="../../Controller/AdminController.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="action" value="assign_rider">
                                <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                <input type="hidden" name="address" value="<?php echo htmlspecialchars($ord['delivery_address']); ?>">
                                <select name="rider_id" class="table-select" onchange="this.form.submit()">
                                    <option value="">Select Rider </option>
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
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #888;">No regular store orders placed yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php'
?>