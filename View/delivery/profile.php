<?php
session_start();
require_once __DIR__ . '/../../Model/UserModel.php';
require_once __DIR__ . '/../../Model/DeliveryModel.php';
 
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Delivery') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}
 
$pageTitle = "Rider Profile - Artistry of Tridha";
$userRole = "Delivery";
require_once __DIR__ . '/../layouts/header.php';
 
$riderId = intval($_SESSION['user_id'] ?? 0);
$user = getUserById($riderId);
$stats = getRiderDeliveryStats($riderId);
$riderName = $user['name'] ?? $_SESSION['user_name'] ?? 'Delivery Agent';
?>
 
<style>
    .profile-banner { width: 85%; margin: 25px auto; background: #ffffff; padding: 25px 30px; border: 1px solid #dddddd; border-radius: 4px; display: flex; align-items: center; gap: 20px; box-sizing: border-box; }
    .profile-avatar { width: 80px; height: 80px; background: #feebc8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #c05621; border: 1px solid #fbd38d; }
    .profile-info h2 { margin: 0 0 5px 0; color: #222; font-size: 24px; }
    .profile-info p { margin: 0 0 8px 0; color: #666; font-size: 14px; }
    .badge-active { background: #d5f5e3; color: #1e8449; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
 
    .content-panel { width: 85%; margin: 0 auto 30px auto; background: #ffffff; padding: 30px; border: 1px solid #dddddd; border-radius: 4px; box-sizing: border-box; }
    .panel-header { border-bottom: 2px solid #eeeeee; padding-bottom: 12px; margin-bottom: 20px; }
    .panel-header h3 { margin: 0 0 5px 0; color: #222; font-size: 18px; }
    .input-row { margin-bottom: 18px; overflow: hidden; }
    .input-half { width: 48%; float: left; }
    .input-half-right { float: right; }
    .input-row label { display: block; font-weight: bold; margin-bottom: 6px; color: #222; font-size: 14px; }
    .input-row input[type="text"], 
    .input-row input[type="email"] { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
 
    .btn-group { margin-top: 25px; display: flex; justify-content: space-between; align-items: center; }
    .btn-save { background-color: #4a235a; color: white; padding: 10px 22px; border: none; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; }
    .btn-save:hover { background-color: #381a44; }
    .btn-back { background-color: #e2e8f0; color: #333; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-size: 15px; font-weight: bold; display: inline-block; }
</style>
 
<div class="profile-banner">
<div class="profile-avatar">🚴</div>
<div class="profile-info">
<h2><?php echo htmlspecialchars($riderName); ?></h2>
<p>Delivery Partner | Completed Deliveries: <b><?php echo $stats['completed']; ?></b></p>
<span class="badge-active"><?php echo htmlspecialchars($user['status'] ?? 'Active'); ?></span>
</div>
</div>
 
<?php if (isset($_GET['success'])): ?>
<div style="width: 85%; margin: 0 auto 20px auto; background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; font-weight: bold;">
<?php echo htmlspecialchars($_GET['success']); ?>
</div>
<?php endif; ?>
 
<div class="content-panel">
<div class="panel-header">
<h3>📍 Rider Information & Contact</h3>
</div>
 
    <form action="../../Controller/profileUpdateController.php" method="POST">
<div class="input-row">
<div class="input-half">
<label>Rider Name</label>
<input type="text" name="full_name" value="<?php echo htmlspecialchars($riderName); ?>" required>
</div>
<div class="input-half input-half-right">
<label>Email Address</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
</div>
</div>
 
        <div class="input-row">
<div class="input-half">
<label>Active Phone Number</label>
<input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
</div>
<div class="input-half input-half-right">
<label>Assigned Zone / Hub</label>
<input type="text" name="city" value="Dhaka Central" readonly style="background: #f7fafc;">
</div>
</div>
 
        <div class="btn-group" style="clear: both;">
<a href="dashboard.php" class="btn-back">← Back to Delivery Dashboard</a>
<button type="submit" class="btn-save">Update Profile</button>
</div>
</form>
</div>
 
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>