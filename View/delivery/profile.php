<?php


session_start();

require_once __DIR__ . '/../../Config/database.php';
require_once __DIR__ . '/../../Model/DeliveryModel.php';
require_once __DIR__ . '/../layouts/header.php';


if (!isset($_SESSION['rider_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$riderId = (int) $_SESSION['rider_id'];
$model   = new DeliveryModel($pdo);

$rider = $model->getRiderProfile($riderId);

$flashMessage = $_SESSION['flash_message'] ?? null;
$flashType    = $_SESSION['flash_type'] ?? 'success';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rider Profile</title>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px 25px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .site-logo {
        text-align: center;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
    }

    .site-logo .logo-text {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 26px;
        font-weight: bold;
        color: #341946;
        letter-spacing: 1px;
    }

    .site-logo .logo-text span {
        color: #7a5a93;
        font-style: italic;
        font-weight: normal;
    }

    .module-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #eee;
    }

    .module-nav-links a {
        display: inline-block;
        margin-right: 8px;
        padding: 7px 14px;
        text-decoration: none;
        color: #341946;
        border: 1px solid #341946;
        border-radius: 3px;
        font-size: 13px;
    }

    .module-nav-links a.active,
    .module-nav-links a:hover {
        background-color: #341946;
        color: #ffffff;
    }

    .module-nav-duty {
        font-size: 13px;
        color: #555;
    }

    .duty-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }

    .duty-on {
        background-color: #e4f5e4;
        color: #2c7a2c;
        border: 1px solid #b6dfb6;
    }

    .duty-off {
        background-color: #f5e4e4;
        color: #a03333;
        border: 1px solid #dfb6b6;
    }

    .page-header {
        border-bottom: 2px solid #eee;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    .page-header h1 {
        margin: 0 0 5px 0;
        font-size: 22px;
    }

    .page-header p {
        margin: 0;
        color: #777;
        font-size: 13px;
    }

    .flash-message {
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 13px;
        margin-bottom: 18px;
        border: 1px solid transparent;
    }

    .flash-success {
        background-color: #e9f7e9;
        border-color: #bfe4bf;
        color: #2c6b2c;
    }

    .flash-error {
        background-color: #fbebeb;
        border-color: #e6b8b8;
        color: #a33a3a;
    }

    .info-card {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .info-card h2 {
        margin: 0;
        font-size: 14px;
        background-color: #f4f0f7;
        color: #341946;
        padding: 10px 14px;
        border-bottom: 1px solid #e0e0e0;
    }

    table.info-table {
        width: 100%;
        border-collapse: collapse;
    }

    table.info-table td {
        padding: 10px 14px;
        font-size: 13px;
        border-bottom: 1px solid #eee;
    }

    table.info-table tr:last-child td {
        border-bottom: none;
    }

    table.info-table td.label {
        color: #777;
        width: 180px;
    }

    .action-card {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 20px;
        padding: 14px;
    }

    .action-card h2 {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #341946;
    }

    .inline-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .inline-form select {
        padding: 7px 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 13px;
    }

    .inline-form button {
        padding: 7px 14px;
        border: 1px solid #999;
        background-color: #eeeeee;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
    }

    .inline-form button:hover {
        background-color: #e2e2e2;
    }

    .duty-btn-on {
        border: 1px solid #4a7a4a !important;
        background-color: #eaf3ea !important;
        color: #2c5a2c !important;
    }

    .duty-btn-on:hover {
        background-color: #dcecdc !important;
    }

    .duty-btn-off {
        border: 1px solid #a04a4a !important;
        background-color: #f6e9e9 !important;
        color: #7a2c2c !important;
    }

    .duty-btn-off:hover {
        background-color: #f0dcdc !important;
    }
</style>
</head>
<body>

<div class="container">

    <div class="page-header">
        <h1>My Profile</h1>
        <p>Your vehicle details, delivery zone, and duty status.</p>
    </div>

    <?php if ($flashMessage): ?>
        <div class="flash-message flash-<?php echo htmlspecialchars($flashType); ?>">
            <?php echo htmlspecialchars($flashMessage); ?>
        </div>
    <?php endif; ?>

    <?php if ($rider): ?>

        <div class="info-card">
            <h2>Rider &amp; Vehicle Information</h2>
            <table class="info-table">
                <tr>
                    <td class="label">Name</td>
                    <td><?php echo htmlspecialchars($rider['name']); ?></td>
                </tr>
                <tr>
                    <td class="label">Phone</td>
                    <td><?php echo htmlspecialchars($rider['phone']); ?></td>
                </tr>
                <tr>
                    <td class="label">Vehicle Type</td>
                    <td><?php echo htmlspecialchars($rider['vehicle_type']); ?></td>
                </tr>
                <tr>
                    <td class="label">Vehicle Number</td>
                    <td><?php echo htmlspecialchars($rider['vehicle_number']); ?></td>
                </tr>
                <tr>
                    <td class="label">Current Zone</td>
                    <td><?php echo htmlspecialchars($rider['zone']); ?></td>
                </tr>
                <tr>
                    <td class="label">Duty Status</td>
                    <td>
                        <span class="duty-pill <?php echo $rider['duty_status'] === 'On Duty' ? 'duty-on' : 'duty-off'; ?>">
                            <?php echo htmlspecialchars($rider['duty_status']); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="action-card">
            <h2>Change Delivery Zone</h2>
            <form class="inline-form" method="post" action="../../Controller/DeliveryController.php">
                <input type="hidden" name="action" value="update_zone">
                <select name="zone">
                    <?php foreach ($model->allZones() as $zoneOption): ?>
                        <option value="<?php echo htmlspecialchars($zoneOption); ?>"
                            <?php echo ($zoneOption === $rider['zone']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($zoneOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Update Zone</button>
            </form>
        </div>

        <div class="action-card">
            <h2>Duty Status</h2>
            <form class="inline-form" method="post" action="../../Controller/DeliveryController.php">
                <input type="hidden" name="action" value="update_duty">
                <?php if ($rider['duty_status'] === 'On Duty'): ?>
                    <input type="hidden" name="duty_status" value="Off Duty">
                    <button type="submit" class="duty-btn-off">Go Off Duty</button>
                <?php else: ?>
                    <input type="hidden" name="duty_status" value="On Duty">
                    <button type="submit" class="duty-btn-on">Go On Duty</button>
                <?php endif; ?>
            </form>
        </div>

    <?php else: ?>
        <p class="no-action-text">Rider profile not found.</p>
    <?php endif; ?>

</div>

</body>
</html>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
