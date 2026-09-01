<?php
session_start();
require_once __DIR__ . '/../../Model/ProductModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Seller Dashboard - Artistry";
require_once __DIR__ . '/../layouts/header.php';

$sellerId = intval($_SESSION['user_id']);
$products = getProductsBySeller($sellerId);
?>

<div style="max-width: 1100px; margin: 30px auto 60px auto; font-family: sans-serif; padding: 0 20px;">
    
    <!-- Floating Toast Notification -->
    <div id="seller-toast" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; color: white; padding: 12px 20px; border-radius: 4px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"></div>

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #4a154b; margin: 0 0 5px 0;">🎨 My Craft Inventory</h2>
            <p style="margin: 0; color: #666;">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Artisan'); ?></strong></p>
        </div>
        <a href="add_product.php" style="background: #4a154b; color: white; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">+ Add New Craft</a>
    </div>

    <!-- Product Table Panel -->
    <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #572553; color: white;">
                    <th style="width: 70px;">Preview</th>
                    <th>Product Title</th>
                    <th>Category</th>
                    <th style="width: 130px;">Price (BDT)</th>
                    <th style="width: 100px;">Stock Units</th>
                    <th style="width: 110px; text-align: center;">Action</th>
                    <th style="width: 120px; text-align: center;">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products && mysqli_num_rows($products) > 0): ?>
                    <?php while ($p = mysqli_fetch_assoc($products)): ?>
                        <tr id="row-<?php echo $p['id']; ?>">
                            <td>
                                <img src="../../assets/images/uploads/<?php echo htmlspecialchars($p['image']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.src='../../assets/images/sample1.jpg';">
                            </td>
                            <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>
                                <input type="number" step="0.01" id="price-<?php echo $p['id']; ?>" value="<?php echo $p['price']; ?>" style="width: 100px; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                            <td>
                                <input type="number" id="stock-<?php echo $p['id']; ?>" value="<?php echo $p['stock']; ?>" style="width: 70px; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                            <td style="text-align: center;">
                                <button id="btn-save-<?php echo $p['id']; ?>" onclick="updateStockPrice(<?php echo $p['id']; ?>)" style="background: #28a745; color: white; border: none; padding: 7px 14px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 13px;">⚡ Save</button>
                            </td>
                            <td style="text-align: center;">
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" style="color: #007bff; text-decoration: none; font-weight: bold; margin-right: 12px;">Edit</a>
                                <a href="../../Controller/ProductController.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this craft?');" style="color: #e53e3e; text-decoration: none; font-weight: bold;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #777; padding: 30px;">No products uploaded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bottom Profile & Logout Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 4px; border: 1px solid #d2d6dc;">
        <div>
            <span style="color: #666; font-size: 14px;">Logged in as Artisan: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Seller'); ?></strong></span>
        </div>
        <div>
            <a href="../../index.php" style="background-color: #e2e8f0; color: #333; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; margin-right: 10px;">← View Shop</a>
            <a href="../../Controller/AuthController.php?action=logout" style="background-color: #e53e3e; color: white; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-block;">Logout from Seller Panel</a>
        </div>
    </div>

</div>

<!-- AJAX JavaScript Logic -->
<script>
function updateStockPrice(productId) {
    const priceVal = document.getElementById('price-' + productId).value;
    const stockVal = document.getElementById('stock-' + productId).value;
    const saveBtn  = document.getElementById('btn-save-' + productId);
    const toast    = document.getElementById('seller-toast');
    const origText = saveBtn.innerText;

    saveBtn.innerText = 'Saving... ⏳';
    saveBtn.disabled = true;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../Controller/ProductController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        saveBtn.disabled = false;
        saveBtn.innerText = origText;
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                toast.innerText = res.message;
                toast.style.backgroundColor = (res.status === 'success') ? '#28a745' : '#dc3545';
                toast.style.display = 'block';
                setTimeout(() => { toast.style.display = 'none'; }, 3000);
            } catch(e) {
                console.error('JSON parse error', e);
            }
        }
    };
    xhr.send('action=ajax_update_stock_price&product_id=' + encodeURIComponent(productId) + '&price=' + encodeURIComponent(priceVal) + '&stock=' + encodeURIComponent(stockVal));
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>