<?php
$pageTitle = "Admin - Artistry of Tridha";
$userRole = "Admin";
require_once __DIR__ . '/../layouts/header.php';
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
            <tr>
                <td><b>#ORD-101</b></td>
                <td>Vintage Scrapbook (Large)</td>
                <td>House 14, Road 27, Dhanmondi, Dhaka</td>
                <td>2,800 ৳</td>
                <td>
                    <select class="table-select">
                        <option value="1">Rider: Rafiq Ahmed</option>
                        <option value="2">Rider: Shakil Khan</option>
                    </select>
                </td>
                <td><span class="badge badge-making">Making</span></td>
            </tr>
            <tr>
                <td><b>#ORD-102</b></td>
                <td>Handmade Floral Bouquet</td>
                <td>Sector 7, Uttara, Dhaka</td>
                <td>850 ৳</td>
                <td>
                    <select class="table-select">
                        <option value="2" selected>Rider: Shakil Khan</option>
                        <option value="1">Rider: Rafiq Ahmed</option>
                    </select>
                </td>
                <td><span class="badge badge-ready">Ready for Pickup</span></td>
            </tr>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>