<?php
session_start();
$pageTitle = "Cart & Order History - Artistry";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../Model/OrderModel.php';

$cartItems = $_SESSION['cart'] ?? array();
$grandTotal = 0;

$customerId = $_SESSION['user_id'] ?? 0;
$customOrders = ($customerId > 0) ? getCustomOrdersByCustomer($customerId) : null;
$storeOrders  = ($customerId > 0) ? getOrdersByCustomer($customerId) : null;
?>

<div style="max-width: 1050px; margin: 30px auto; font-family: sans-serif; padding: 0 20px;">

    <?php if (isset($_GET['success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 35px;">
        <h2 style="color: #4a154b; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">🛒 Active Shopping Cart</h2>
        
        <?php if (!empty($cartItems)): ?>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #572553; color: white;">
                        <th>Preview</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                    ?>
                    <tr>
                        <td style="width: 60px;">
                            <img src="../../assets/images/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Craft" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.src='../../assets/images/sample1.jpg';">
                        </td>
                        <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                        <td>৳ <?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>৳ <?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="../../Controller/CartController.php?action=remove&id=<?php echo $id; ?>" style="color: red; text-decoration: none; font-weight: bold;">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold; font-size: 16px;">Grand Total:</td>
                        <td colspan="2" style="font-weight: bold; font-size: 16px; color: #2b6cb0;">৳ <?php echo number_format($grandTotal, 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <a href="../../index.php" style="background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Continue Shopping</a>
                <a href="checkout.php" style="background-color: rgb(87, 37, 83); color: white; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;">Proceed to Checkout →</a>
            </div>
        <?php else: ?>
            <p style="color: #666; margin: 15px 0 5px 0;">Your active shopping cart is currently empty.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Customer'): ?>
        
        <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 35px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                <h3 style="color: #4a154b; margin: 0;">🎨 Custom Craft Requests</h3>
                <a href="custom_order.php" style="background-color: #572553; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold;">+ New Custom Order</a>
            </div>

            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #4a154b; color: white;">
                        <th>Req ID</th>
                        <th>Craft Item & Theme</th>
                        <th>Size / Layers</th>
                        <th>Offered Budget</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($customOrders && mysqli_num_rows($customOrders) > 0): ?>
                        <?php while ($co = mysqli_fetch_assoc($customOrders)): ?>
                            <tr>
                                <td>#CR-<?php echo $co['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($co['craft_type']); ?></strong><br>
                                    <small style="color: #666;">Theme: <?php echo htmlspecialchars($co['color_theme']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($co['craft_size']); ?> (<?php echo $co['layers']; ?> Layers)</td>
                                <td>৳ <?php echo number_format($co['budget'], 2); ?></td>
                                <td>
                                    <?php 
                                        $st = $co['status'];
                                        $color = ($st === 'Accepted') ? '#28a745' : (($st === 'Confirmed') ? '#2b6cb0' : (($st === 'Rejected') ? '#dc3545' : '#e67e22'));
                                    ?>
                                    <span style="background: <?php echo $color; ?>; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                                        <?php echo htmlspecialchars($st); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($co['status'] === 'Accepted'): ?>
                                        <a href="../../Controller/OrderController.php?action=customer_confirm_custom&order_id=<?php echo $co['id']; ?>" 
                                           style="background: #28a745; color: white; padding: 5px 12px; text-decoration: none; border-radius: 3px; font-weight: bold; font-size: 12px; display: inline-block;">
                                            Confirm Order
                                        </a>
                                    <?php elseif ($co['status'] === 'Confirmed'): ?>
                                        <small style="color: #2b6cb0; font-weight: bold;">✔ Added to Cart / Processing</small>
                                    <?php elseif ($co['status'] === 'Pending Review'): ?>
                                        <small style="color: #718096;">Waiting for Review</small>
                                    <?php else: ?>
                                        <small style="color: #dc3545;">Declined</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888; padding: 20px;">No custom craft requests submitted yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 40px;">
            <h3 style="color: #4a154b; margin: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">📦 Ready-to-Ship Store Orders</h3>
            
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #4a154b; color: white;">
                        <th>Order ID</th>
                        <th>Product Title</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Delivery Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($storeOrders && mysqli_num_rows($storeOrders) > 0): ?>
                        <?php while ($so = mysqli_fetch_assoc($storeOrders)): ?>
                            <tr>
                                <td>#ORD-<?php echo $so['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($so['product_name']); ?></strong></td>
                                <td><?php echo $so['quantity']; ?> pcs</td>
                                <td>৳ <?php echo number_format($so['total_price'], 2); ?></td>
                                <td>
                                    <?php $isConfirmed = (strtolower($so['status']) === 'confirmed'); ?>
                                    <span style="background: <?php echo $isConfirmed ? '#28a745' : '#f39c12'; ?>; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                                        <?php echo $isConfirmed ? 'Order Confirmed' : 'Verifying Request'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                            $delStatus = !empty($so['delivery_status']) ? trim($so['delivery_status']) : '';
                                            $rName     = !empty($so['rider_name']) ? htmlspecialchars($so['rider_name']) : 'Assigned Rider';
                                    
                                            if ($delStatus === 'Delivered') {
                                                echo "<span style='background: #28a745; color: white; padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;'>✔ Delivered</span>";
                                            } elseif ($delStatus === 'Out for Delivery') {
                                                echo "<span style='background: #3182ce; color: white; padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold;'>🚚 Out for Delivery (" . $rName . ")</span>";
                                            } elseif (!empty($so['rider_id']) || $delStatus === 'Assigned' || $delStatus === 'Ready for Delivery') {
                                                echo "<span style='color: #2b6cb0; font-weight: bold;'>🚴 Assigned Rider: " . $rName . "</span>";
                                            } elseif (strtolower($so['status']) === 'confirmed') {
                                                echo "<strong style='color: #d69e2e;'>🛠 Crafting in Workshop</strong>";
                                            } else {
                                                echo "<span style='color: #718096;'>Verifying Payment</span>";
                                            }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #888; padding: 20px;">No store orders placed yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>