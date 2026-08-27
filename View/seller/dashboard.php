<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../../Model/ProductModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Seller Dashboard - Artistry";
require_once __DIR__ . '/../layouts/header.php';

$seller_id = $_SESSION['user_id'] ?? 0;
$products  = getProductsBySeller($seller_id);
?>

<div style="max-width: 1100px; margin: 30px auto 60px auto; font-family: sans-serif; padding: 0 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #edf2f7; padding-bottom: 15px;">
        <div>
            <h2 style="color: #4a154b; margin: 0 0 5px 0;">Artisan Dashboard</h2>
            <p style="margin: 0; color: #666;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Artisan'); ?></strong></p>
        </div>
        <a href="add_product.php" style="background-color: #4a154b; color: white; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">+ Add New Craft</a>
    </div>

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

    <div style="background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #2d3748; border-bottom: 1px solid #eee; padding-bottom: 10px;">📦 My Craft Inventory</h3>
        
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 15px;">
            <thead>
                <tr style="background-color: #572553; color: white;">
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products && mysqli_num_rows($products) > 0): ?>
                    <?php while ($p = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td style="width: 70px;">
                                <img src="../../assets/images/uploads/<?php echo htmlspecialchars($p['image']); ?>" alt="Craft" style="width: 55px; height: 55px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='../../assets/images/sample1.jpg';">
                            </td>
                            <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>৳ <?php echo number_format($p['price'], 2); ?></td>
                            <td><?php echo $p['stock']; ?> pcs</td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; background-color: <?php echo ($p['stock'] > 0) ? '#28a745' : '#dc3545'; ?>;">
                                    <?php echo ($p['stock'] > 0) ? 'Available' : 'Out of Stock'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" style="color: #2b6cb0; text-decoration: none; font-weight: bold; margin-right: 15px;">Edit</a>
                                <a href="../../Controller/ProductController.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this craft item?');" style="color: #dc3545; text-decoration: none; font-weight: bold;">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #718096; padding: 35px 20px;">
                            No craft products listed yet.<br><br>
                            <a href="add_product.php" style="background-color: #4a154b; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: bold;">+ Add Your First Craft Item</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border-radius: 4px; border: 1px solid #d2d6dc;">
        <div>
            <span style="color: #666; font-size: 14px;">Logged in as Artisan: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Seller'); ?></strong></span>
        </div>
        <div>
            <a href="../../index.php" style="background-color: #e2e8f0; color: #333; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; margin-right: 10px;">← View Shop</a>
            <a href="../../Controller/AuthController.php?action=logout" style="background-color: #e53e3e; color: white; padding: 10px 22px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-block;">Logout from Seller Panel</a>
        </div>
    </div>

</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>