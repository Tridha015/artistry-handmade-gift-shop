<?php
session_start();
$pageTitle = "Customer Dashboard - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';

$username = $_SESSION["loggedInUsername"] ?? "Customer";
?>

<style>
    .dashboard-wrapper { width: 75%; margin: 40px auto; background: #ffffff; padding: 30px; border: 1px solid #dddddd; font-family: Arial, sans-serif; }
    .dash-header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #eeeeee; padding-bottom: 15px; margin-bottom: 20px; }
    .dash-header h2 { margin: 0 0 5px 0; color: #222; font-size: 24px; }
    .dash-header p { margin: 0; color: #666; font-size: 15px; }
    .btn-new-order { background-color: #f1c40f; color: #333; padding: 10px 18px; text-decoration: none; font-weight: bold; border: 1px solid #d4ac0d; font-size: 14px; }
    .btn-new-order:hover { background-color: #d4ac0d; }
    .custom-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .custom-table th, .custom-table td { padding: 12px 15px; text-align: left; border: 1px solid #dddddd; }
    .custom-table th { background-color: #f4f4f4; color: #333; font-weight: bold; }
    .badge-review { background: #fdebd0; color: #a04000; padding: 4px 8px; font-size: 12px; font-weight: bold; }
    .badge-ready { background: #d5f5e3; color: #1e8449; padding: 4px 8px; font-size: 12px; font-weight: bold; }
    .btn-action { background: #5d6d7e; color: white; padding: 6px 12px; text-decoration: none; font-size: 13px; border-radius: 2px; }
</style>

<div class="dashboard-wrapper">
    <div class ="dash-header">
        <div>
            <h2>My Orders & Tracking</h2>
            <p>Track your regular and custom craft requests in real-time</p>
        </div>
        <a href="custom_order.php" class="btn-new-order">+ New Custom Order</a>
    </div>
    
    <table class="custom-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Craft Details</th>
                <th>Estimated Cost</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#CR-201</td>
                <td><strong>Explosion Box</strong><br>3 Layers, Maroon Theme</td>
                <td>1,800 ৳</td>
                <td><span class="badge-review">Under Review</span></td>
                <td><a href="#" class="btn-action">Details</a></td>
            </tr>
            <tr>
                <td>#ORD-102</td>
                <td><strong>Handmade Floral Bouquet</strong><br>Pastel Ribbon</td>
                <td>850 ৳</td>
                <td><span class="badge-ready">Ready for Pickup</span></td>
                <td><a href="#" class="btn-action">Track</a></td>
            </tr>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>