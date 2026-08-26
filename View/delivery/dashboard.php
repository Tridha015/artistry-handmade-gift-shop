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

$rider      = $model->getRiderProfile($riderId);   
$deliveries = $model->getAssignedParcels($riderId);


$flashMessage = $_SESSION['flash_message'] ?? null;
$flashType    = $_SESSION['flash_type'] ?? 'success';
unset($_SESSION['flash_message'], $_SESSION['flash_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delivery Dashboard</title>
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
        max-width: 1100px;
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

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
        padding: 12px;
        background-color: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .filter-bar label {
        font-size: 13px;
        margin-right: 4px;
    }

    .filter-bar input[type="text"],
    .filter-bar select {
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 13px;
    }

    .filter-bar button {
        padding: 7px 14px;
        border: 1px solid #999;
        background-color: #563f668a;
        border-radius: 3px;
        cursor: pointer;
        font-size: 13px;
    }

    .filter-bar button:hover {
        background-color: #6f5780;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    thead th {
        text-align: left;
        background-color: #341946;
        color: #ffffff;
        padding: 10px 8px;
        font-size: 13px;
        font-weight: normal;
    }

    tbody td {
        padding: 10px 8px;
        border-bottom: 1px solid #e5e5e5;
        font-size: 13px;
        vertical-align: top;
    }

    tbody tr:hover {
        background-color: #f7f7f7;
    }

    .address-cell {
        max-width: 220px;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        border: 1px solid #ccc;
        background-color: #f0f0f0;
    }

    .status-form {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .status-form select {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 13px;
    }

    .status-form button {
        padding: 5px 10px;
        border: 1px solid #4a7a4a;
        background-color: #eaf3ea;
        color: #2c5a2c;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
    }

    .status-form button:hover {
        background-color: #dcecdc;
    }

    .no-action-text {
        font-size: 12px;
        color: #999;
        font-style: italic;
    }

    .empty-row td {
        text-align: center;
        padding: 25px 8px;
        color: #888;
        font-style: italic;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-top: 10px;
    }

    .pagination a {
        padding: 6px 11px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
        border-radius: 3px;
        font-size: 13px;
    }

    .pagination a:hover {
        background-color: #f0f0f0;
    }
</style>
</head>
<body>

<div class="container">


    <div class="page-header">
        <h1>Delivery Dashboard</h1>
        <p>Parcels assigned to you for delivery. Update the status as you pick up and deliver each one.</p>
    </div>

    <?php if ($flashMessage): ?>
        <div class="flash-message flash-<?php echo htmlspecialchars($flashType); ?>">
            <?php echo htmlspecialchars($flashMessage); ?>
        </div>
    <?php endif; ?>

    <form class="filter-bar" method="get" action="dashboard.php">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Customer name or Order ID">

        <label for="status_filter">Status:</label>
        <select id="status_filter" name="status_filter">
            <option value="">All</option>
            <?php foreach ($model->allStatuses() as $statusOption): ?>
                <option value="<?php echo htmlspecialchars($statusOption); ?>"><?php echo htmlspecialchars($statusOption); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Delivery ID</th>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Scheduled Date</th>
                <th>Current Status</th>
                <th>Update Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($deliveries)): ?>
            <?php foreach ($deliveries as $delivery): ?>
                <?php $nextStatuses = $model->getNextStatuses($delivery['status']); ?>
                <tr>
                    <td><?php echo htmlspecialchars($delivery['delivery_id']); ?></td>
                    <td><?php echo htmlspecialchars($delivery['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($delivery['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($delivery['customer_phone']); ?></td>
                    <td class="address-cell"><?php echo htmlspecialchars($delivery['address']); ?></td>
                    <td><?php echo htmlspecialchars($delivery['scheduled_date']); ?></td>
                    <td>
                        <span class="status-badge"><?php echo htmlspecialchars($delivery['status']); ?></span>
                    </td>
                    <td>
                        <?php if (!empty($nextStatuses)): ?>
                            <form class="status-form" method="post" action="../../Controller/DeliveryController.php">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="delivery_id" value="<?php echo htmlspecialchars($delivery['delivery_id']); ?>">
                                <select name="new_status">
                                    <?php foreach ($nextStatuses as $option): ?>
                                        <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        <?php else: ?>
                            <span class="no-action-text">No further action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="empty-row">
                <td colspan="8">No deliveries assigned to you right now.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <a href="?page=1">1</a>
        <a href="?page=2">2</a>
        <a href="?page=3">3</a>
    </div>

</div>

</body>
</html>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
