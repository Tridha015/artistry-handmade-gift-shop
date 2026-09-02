<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Delivery') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}
 
$pageTitle = "Delivery Dashboard - Artistry of Tridha";
$userRole = "Delivery";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../Model/DeliveryModel.php';
require_once __DIR__ . '/../../Model/UserModel.php';
 
$riderId = intval($_SESSION['user_id'] ?? 0);
$riderData = getUserById($riderId);
$deliveries = getDeliveriesByRider($riderId);
$stats = getRiderDeliveryStats($riderId);
 
$isOnline = intval($riderData['is_online'] ?? 1);
?>
 
<!-- Audio Alert -->
<audio id="delivery-alert-sound" preload="auto">
<source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>
 
<div style="width: 85%; margin: 20px auto; display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 25px; border-radius: 6px; border: 1px solid #ddd;">
<div style="display: flex; align-items: center; gap: 15px;">
<span style="font-weight: bold; font-size: 15px;">Duty Status:</span>
<label style="position: relative; display: inline-block; width: 50px; height: 26px; margin: 0;">
<input type="checkbox" id="duty-toggle" <?php echo $isOnline ? 'checked' : ''; ?> onchange="toggleDuty(this.checked)" style="opacity: 0; width: 0; height: 0;">
<span id="duty-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: <?php echo $isOnline ? '#28a745' : '#ccc'; ?>; transition: .3s; border-radius: 34px;">
<span id="duty-knob" style="position: absolute; content: ''; height: 18px; width: 18px; left: <?php echo $isOnline ? '28px' : '4px'; ?>; bottom: 4px; background-color: white; transition: .3s; border-radius: 50%;"></span>
</span>
</label>
<span id="duty-text" style="font-size: 13px; font-weight: bold; color: <?php echo $isOnline ? '#28a745' : '#718096'; ?>;">
            ● <?php echo $isOnline ? 'Online (Accepting Tasks)' : 'Offline (Hidden from Admin)'; ?>
</span>
</div>
 
    <div style="display: flex; gap: 10px;">
<a href="profile.php" style="background: #4a235a; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px;">👤 Rider Profile</a>
</div>
</div>
 
<div class="stats-grid">
<div class="stat-box">
<h4>Total Assigned</h4>
<div class="count" id="stat-total" style="color: #2b6cb0;"><?php echo $stats['total_assigned']; ?></div>
</div>
<div class="stat-box">
<h4>In Progress</h4>
<div class="count" id="stat-progress" style="color: #c05621;"><?php echo $stats['in_progress']; ?></div>
</div>
<div class="stat-box">
<h4>Completed Deliveries</h4>
<div class="count" id="stat-completed" style="color: #28a745;"><?php echo $stats['completed']; ?></div>
</div>
</div>
 
<div class="panel" style="width: 85%; margin: 0 auto 40px auto; background: #fff; padding: 25px; border-radius: 6px; border: 1px solid #ddd;">
<div class="panel-header" style="border-bottom: 2px solid #eee; padding-bottom: 12px; margin-bottom: 20px;">
<h3 style="margin: 0; color: #333;">📦 Assigned Craft Deliveries</h3>
</div>
 
    <div id="alert-msg" style="display: none; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-weight: bold;"></div>
 
    <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
<thead>
<tr style="background: #f8fafc; border-bottom: 2px solid #edf2f7;">
<th style="padding: 12px;">Order ID</th>
<th style="padding: 12px;">Customer Details</th>
<th style="padding: 12px;">Craft Item</th>
<th style="padding: 12px;">Payment Info</th>
<th style="padding: 12px;">Status</th>
<th style="padding: 12px;">Actions</th>
</tr>
</thead>
<tbody id="delivery-rows">
<?php if ($deliveries && mysqli_num_rows($deliveries) > 0): ?>
<?php while ($d = mysqli_fetch_assoc($deliveries)): ?>
<tr id="row-del-<?php echo $d['delivery_id']; ?>" style="border-bottom: 1px solid #eee;">
<td style="padding: 12px;"><b>#ORD-<?php echo $d['order_id']; ?></b></td>
<td style="padding: 12px;">
<strong><?php echo htmlspecialchars($d['customer_name']); ?></strong><br>
<small style="color: #666;">📞 <?php echo htmlspecialchars($d['customer_phone']); ?></small><br>
<small style="color: #4a5568;">📍 <?php echo htmlspecialchars($d['delivery_address']); ?></small>
</td>
<td style="padding: 12px;">
<div style="display: flex; align-items: center; gap: 8px;">
<img src="../../assets/images/uploads/<?php echo htmlspecialchars($d['product_image']); ?>" style="width: 42px; height: 42px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='../../assets/images/sample1.jpg';">
<span><?php echo htmlspecialchars($d['product_name']); ?></span>
</div>
</td>
<td style="padding: 12px;">
<b><?php echo number_format($d['total_price'], 2); ?> ৳</b><br>
<small style="color: #666;"><?php echo htmlspecialchars($d['payment_gateway']); ?></small>
</td>
<td style="padding: 12px;">
<span id="badge-<?php echo $d['delivery_id']; ?>" style="padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; background: <?php echo ($d['delivery_status'] === 'Delivered') ? '#c6f6d5; color: #22543d;' : (($d['delivery_status'] === 'Out for Delivery') ? '#bee3f8; color: #2b6cb0;' : '#feebc8; color: #c05621;'); ?>">
<?php echo htmlspecialchars($d['delivery_status']); ?>
</span>
</td>
<td style="padding: 12px;">
<div style="display: flex; flex-direction: column; gap: 6px;">
<select onchange="updateDeliveryStatus(<?php echo $d['delivery_id']; ?>, this.value)" style="padding: 5px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
<option value="Assigned" <?php echo ($d['delivery_status'] === 'Assigned') ? 'selected' : ''; ?>>Assigned</option>
<option value="Out for Delivery" <?php echo ($d['delivery_status'] === 'Out for Delivery') ? 'selected' : ''; ?>>Out for Delivery</option>
<option value="Delivered" <?php echo ($d['delivery_status'] === 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
</select>
<?php if ($d['delivery_status'] !== 'Delivered'): ?>
<button type="button" onclick="rejectDelivery(<?php echo $d['delivery_id']; ?>)" style="background: #e53e3e; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 11px; cursor: pointer; font-weight: bold;">
                                        Reject Task
</button>
<?php endif; ?>
</div>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr id="no-task-row">
<td colspan="6" style="text-align: center; color: #888; padding: 25px;">No delivery tasks assigned to you right now.</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
 
<script>
let currentTaskCount = <?php echo $stats['in_progress']; ?>;
 
function showAlert(message, isSuccess = true) {
    const alertBox = document.getElementById('alert-msg');
    alertBox.style.display = 'block';
    alertBox.style.background = isSuccess ? '#d4edda' : '#f8d7da';
    alertBox.style.color = isSuccess ? '#155724' : '#721c24';
    alertBox.innerText = message;
    setTimeout(() => { alertBox.style.display = 'none'; }, 4000);
}
 
function toggleDuty(isOnline) {
    const slider = document.getElementById('duty-slider');
    const knob = document.getElementById('duty-knob');
    const text = document.getElementById('duty-text');
 
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Controller/DeliveryController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
 
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    if (isOnline) {
                        slider.style.backgroundColor = '#28a745';
                        knob.style.left = '28px';
                        text.style.color = '#28a745';
                        text.innerText = '● Online (Accepting Tasks)';
                    } else {
                        slider.style.backgroundColor = '#ccc';
                        knob.style.left = '4px';
                        text.style.color = '#718096';
                        text.innerText = '● Offline (Hidden from Admin)';
                    }
                    showAlert(res.message);
                } else {
                    showAlert(res.message, false);
                }
            } catch(e) {
                console.error(e);
            }
        }
    };
    xhr.send('action=ajax_toggle_duty&is_online=' + (isOnline ? 1 : 0));
}
 
function updateDeliveryStatus(deliveryId, newStatus) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Controller/DeliveryController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
 
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    const badge = document.getElementById('badge-' + deliveryId);
                    badge.innerText = res.new_status;
                    if (res.new_status === 'Delivered') {
                        badge.style.background = '#c6f6d5';
                        badge.style.color = '#22543d';
                    } else if (res.new_status === 'Out for Delivery') {
                        badge.style.background = '#bee3f8';
                        badge.style.color = '#2b6cb0';
                    } else {
                        badge.style.background = '#feebc8';
                        badge.style.color = '#c05621';
                    }
 
                    if (res.stats) {
                        document.getElementById('stat-total').innerText = res.stats.total_assigned;
                        document.getElementById('stat-progress').innerText = res.stats.in_progress;
                        document.getElementById('stat-completed').innerText = res.stats.completed;
                        currentTaskCount = res.stats.in_progress;
                    }
 
                    showAlert('Status updated to ' + res.new_status);
                } else {
                    showAlert(res.message, false);
                }
            } catch(e) {
                console.error(e);
            }
        }
    };
    xhr.send('action=ajax_update_status&delivery_id=' + encodeURIComponent(deliveryId) + '&status=' + encodeURIComponent(newStatus));
}
 
function rejectDelivery(deliveryId) {
    if (!confirm('Are you sure you want to reject this delivery assignment?')) return;
 
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Controller/DeliveryController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
 
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    const row = document.getElementById('row-del-' + deliveryId);
                    if (row) row.remove();
 
                    if (res.stats) {
                        document.getElementById('stat-total').innerText = res.stats.total_assigned;
                        document.getElementById('stat-progress').innerText = res.stats.in_progress;
                        document.getElementById('stat-completed').innerText = res.stats.completed;
                        currentTaskCount = res.stats.in_progress;
                    }
 
                    const rows = document.getElementById('delivery-rows');
                    if (rows.children.length === 0) {
                        rows.innerHTML = '<tr id="no-task-row"><td colspan="6" style="text-align: center; color: #888; padding: 25px;">No delivery tasks assigned to you right now.</td></tr>';
                    }
 
                    showAlert('Delivery task rejected and returned to pool.');
                } else {
                    showAlert(res.message, false);
                }
            } catch(e) {
                console.error(e);
            }
        }
    };
    xhr.send('action=ajax_reject_delivery&delivery_id=' + encodeURIComponent(deliveryId));
}
 
// Background Auto-Polling: Checks every 15 seconds for newly assigned tasks
setInterval(function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../Controller/DeliveryController.php?action=ajax_poll_tasks', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    if (res.active_tasks > currentTaskCount) {
                        currentTaskCount = res.active_tasks;
                        const sound = document.getElementById('delivery-alert-sound');
                        if (sound) sound.play().catch(e => console.log('Audio autoplay prevented:', e));
 
                        showAlert('🔔 New Delivery Task Assigned! Refreshing list...', true);
                        setTimeout(() => { window.location.reload(); }, 1500);
                    }
                }
            } catch(e) {
                console.error(e);
            }
        }
    };
    xhr.send();
}, 15000);
</script>
 
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>