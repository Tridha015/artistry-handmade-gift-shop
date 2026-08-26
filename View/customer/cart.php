<?php
session_start();
$pageTitle = "Shopping Cart - Artistry";
require_once __DIR__ . '/../layouts/header.php';
 
$cartItems = $_SESSION['cart'] ?? array();
$grandTotal = 0;
?>
 
<div style="max-width: 900px; margin: 30px auto; font-family: sans-serif; padding: 0 20px;">
<h2>Your Shopping Cart</h2>
 
    <?php if (isset($_GET['success'])): ?>
<div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
<?php echo htmlspecialchars($_GET['success']); ?>
</div>
<?php endif; ?>
 
    <?php if (!empty($cartItems)): ?>
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
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
<a href="checkout.php" style="background-color: rgb(87, 37, 83); color: white; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;">Proceed to Checkout</a>
</div>
<?php else: ?>
<div style="text-align: center; padding: 40px; border: 1px dashed #ccc; border-radius: 6px;">
<p style="color: #666; font-size: 16px;">Your cart is currently empty.</p>
<a href="../../index.php" style="background-color: rgb(87, 37, 83); color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 10px;">Browse Crafts</a>
</div>
<?php endif; ?>
</div>
 
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>