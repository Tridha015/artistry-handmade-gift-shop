<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Seller') {
    header("Location: ../auth/login.php?error=Unauthorized Access");
    exit();
}

$pageTitle = "Add Craft Product - Artistry";
require_once __DIR__ . '/../layouts/header.php';
?>

<div style="max-width: 600px; margin: 40px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; font-family: sans-serif; background: #fff;">
    <h2 style="text-align: center; margin-bottom: 20px; color: #572553;">Add New Craft Product</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../Controller/ProductController.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_product">

        <div style="margin-bottom: 15px;">
            <label>Product Title</label><br>
            <input type="text" name="title" required placeholder="ex: Explosion Box, Floral Scrapbook" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Category</label><br>
            <select name="category" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
    <option value="Scrapbook">Memory Scrapbooks</option>
    <option value="Explosion Box">Explosion Boxes</option>
    <option value="Greeting Cards">Greeting Cards</option>
    <option value="Nikah & Signature Pens">Nikah & Signature Pens</option>
    <option value="Floral Bouquets">Handmade Floral Bouquets</option>
    <option value="Photo Frames">Photo Frames</option>
    <option value="Chocolate Gift Hampers">Chocolate Gift Hampers</option>
    <option value="Shadow Boxes & Light Jars">Shadow Boxes & Light Jars</option>
    <option value="Custom Illustration Art">Custom Illustration Art</option>
</select>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label>Price (৳)</label><br>
                <input type="number" step="0.01" name="price" required placeholder="500.00" style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label>Available Stock</label><br>
                <input type="number" name="stock" value="1" min="1" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Craft Showcase Image</label><br>
            <input type="file" name="image" accept="image/*" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background-color: rgb(87, 37, 83); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Upload Craft
        </button>
    </form>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>