<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Model/ProductModel.php';

$id = intval($_GET['id'] ?? 0);
$product = getProductById($id);

if (!$product) {
    header("Location: index.php");
    exit();
}

$pageTitle = htmlspecialchars($product['title']) . " - Artistry";
require_once __DIR__ . '/layouts/header.php';
?>

<div style="max-width: 900px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); font-family: sans-serif; display: flex; gap: 30px;">
    <div style="flex: 1;">
        <img src="../assets/images/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Craft Image" style="width: 100%; border-radius: 8px; object-fit: cover;" onerror="this.src='../assets/images/sample1.jpg';">
    </div>
    <div style="flex: 1.2;">
        <span style="background: #f3e8f2; color: #572553; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
            <?php echo htmlspecialchars($product['category']); ?>
        </span>
        <h1 style="color: #222; margin: 12px 0;"><?php echo htmlspecialchars($product['title']); ?></h1>
        <h2 style="color: #572553; margin-bottom: 15px;">৳ <?php echo number_format($product['price'], 2); ?></h2>
        
        <p style="color: #666; font-size: 14px;"><strong>Stock Available:</strong> <?php echo $product['stock']; ?> items</p>
        
        <h4 style="margin: 20px 0 8px 0; color: #333;">Description</h4>
        <p style="color: #555; line-height: 1.6; white-space: pre-line;">
            <?php echo htmlspecialchars($product['description'] ?: 'No description provided.'); ?>
        </p>

        <form action="../Controller/CartController.php" method="POST" style="margin-top: 25px;">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" style="padding: 12px 25px; background: #572553; color: #fff; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;">
                Add to Cart 🛒
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>