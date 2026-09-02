<?php
session_start();
require_once __DIR__ . '/../../Model/ProductModel.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$productId = intval($_GET['id']);
$sellerId  = $_SESSION['user_id'] ?? 0;
$product   = getProductById($productId, $sellerId);

if (!$product) {
    header("Location: dashboard.php?error=Product not found or access denied.");
    exit();
}

$pageTitle = "Edit Craft Product - Artistry";
require_once __DIR__ . '/../layouts/header.php';
?>

<div style="max-width: 600px; margin: 40px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; font-family: sans-serif; background: #fff;">
    <h2 style="text-align: center; margin-bottom: 20px; color: #572553;">Edit Craft Product</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../Controller/ProductController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit_product">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

        <div style="margin-bottom: 15px;">
            <label>Product Title</label><br>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Category</label><br>
            <select name="category" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                <option value="Scrapbook" <?php echo ($product['category'] === 'Scrapbook') ? 'selected' : ''; ?>>Memory Scrapbooks</option>
                <option value="Explosion Box" <?php echo ($product['category'] === 'Explosion Box') ? 'selected' : ''; ?>>Explosion Boxes</option>
                <option value="Greeting Cards" <?php echo ($product['category'] === 'Greeting Cards') ? 'selected' : ''; ?>>Greeting Cards</option>
                <option value="Nikah & Signature Pens" <?php echo ($product['category'] === 'Nikah & Signature Pens') ? 'selected' : ''; ?>>Nikah & Signature Pens</option>
                <option value="Floral Bouquets" <?php echo ($product['category'] === 'Floral Bouquets') ? 'selected' : ''; ?>>Handmade Floral Bouquets</option>
                <option value="Photo Frames" <?php echo ($product['category'] === 'Photo Frames') ? 'selected' : ''; ?>>Photo Frames</option>
                <option value="Chocolate Gift Hampers" <?php echo ($product['category'] === 'Chocolate Gift Hampers') ? 'selected' : ''; ?>>Chocolate Gift Hampers</option>
                <option value="Shadow Boxes & Light Jars" <?php echo ($product['category'] === 'Shadow Boxes & Light Jars') ? 'selected' : ''; ?>>Shadow Boxes & Light Jars</option>
                <option value="Custom Illustration Art" <?php echo ($product['category'] === 'Custom Illustration Art') ? 'selected' : ''; ?>>Custom Illustration Art</option>
            </select>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label>Price (৳)</label><br>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label>Available Stock</label><br>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Craft Size / Dimensions</label><br>
            <input type="text" name="size" value="<?php echo htmlspecialchars($product['size'] ?? ''); ?>" placeholder="ex: 6x6 inches, 8x10 inches" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Product Description</label><br>
            <textarea name="description" rows="3" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Current Image</label><br>
            <img src="../../assets/images/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin: 8px 0; border: 1px solid #ccc;" onerror="this.src='../../assets/images/sample1.jpg';"><br>
            <label>Change Image (Optional)</label><br>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="dashboard.php" style="flex: 1; text-align: center; padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Cancel</a>
            <button type="submit" style="flex: 2; padding: 10px; background-color: rgb(87, 37, 83); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Update Product
            </button>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>