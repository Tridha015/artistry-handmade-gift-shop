<?php
session_start();
require_once __DIR__ . '/../../Model/ProductModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Seller Dashboard - Artistry";
require_once __DIR__ . '/../layouts/header.php';

$seller_id = $_SESSION['user_id'];
$products  = getProductsBySeller($seller_id);
?>

<div style="max-width: 1100px; margin: 30px auto; font-family: sans-serif; padding: 0 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2>Artisan Dashboard</h2>
            <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
        </div>
        <a href="add_product.php" style="background-color: rgb(87, 37, 83); color: white; padding: 10px 18px; text-decoration: none; border-radius: 4px; font-weight: bold;">+ Add New Craft</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <h3>My Inventory</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
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
            <?php if (mysqli_num_rows($products) > 0): ?>
                <?php while ($p = mysqli_fetch_assoc($products)): ?>
                    <tr>
                        <td style="width: 70px;">
                            <img src="../../assets/images/uploads/<?php echo htmlspecialchars($p['image']); ?>" alt="Craft" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='../../assets/images/sample1.jpg';">
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
                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" style="color: #007bff; text-decoration: none; font-weight: bold; margin-right: 12px;">Edit</a>
                            <a href="../../Controller/ProductController.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');" style="color: #dc3545; text-decoration: none; font-weight: bold;">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #888; padding: 20px;">No craft products listed yet. Click "+ Add New Craft" to start selling.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>