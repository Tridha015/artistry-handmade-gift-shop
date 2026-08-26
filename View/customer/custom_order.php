
<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Customer') {
    header("Location: ../auth/login.php?error=Customer login required");
    exit();
}
 
$pageTitle = "Request Custom Craft - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';
?>
 
<style>
    .form-wrapper { width: 60%; margin: 40px auto; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 8px; font-family: sans-serif; }
    .form-title { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
    .form-title h2 { margin: 0 0 5px 0; color: #2d3748; }
    .form-title p { margin: 0; color: #666; font-size: 14px; }
    .input-group { margin-bottom: 18px; }
    .input-group label { display: block; font-weight: bold; margin-bottom: 6px; }
    .input-group input[type="text"], .input-group input[type="number"], .input-group select, .input-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .row-split { display: flex; gap: 15px; }
    .row-split .input-group { flex: 1; }
    .submit-btn { width: 100%; padding: 12px; background-color: rgb(87, 37, 83); color: #fff; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; }
</style>
 
<div class="form-wrapper">
<div class="form-title">
<h2>Custom Craft Request</h2>
<p>Tell us your custom requirements, theme, layers, and budget.</p>
</div>
 
    <?php if (isset($_GET['error'])): ?>
<div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
<?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>
 
    <form action="../../Controller/OrderController.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="action" value="submit_custom_order">
 
        <div class="input-group">
<label>Craft Type / Item</label>
<select name="craft_type" required>
<option value="Explosion Box">Explosion Box</option>
<option value="Scrapbook Album">Scrapbook Album</option>
<option value="Floral Bouquet">Floral Bouquet</option>
<option value="Handmade Card & Box">Handmade Card & Box</option>
</select>
</div>
 
        <div class="row-split">
<div class="input-group">
<label>Size</label>
<select name="craft_size" required>
<option value="Small">Small</option>
<option value="Medium">Medium</option>
<option value="Large">Large</option>
</select>
</div>
<div class="input-group">
<label>Number of Layers / Pages</label>
<input type="number" name="layers" min="1" value="1" required>
</div>
</div>
 
        <div class="row-split">
<div class="input-group">
<label>Color Theme</label>
<input type="text" name="color_theme" placeholder="e.g. Maroon & Black, Pastel Pink" required>
</div>
<div class="input-group">
<label>Your Expected Budget (৳)</label>
<input type="number" step="0.01" name="budget" placeholder="1500.00" required>
</div>
</div>
 
        <div class="input-group">
<label>Reference Image (Optional)</label>
<input type="file" name="sample_image" accept="image/*">
</div>
 
        <div class="input-group">
<label>Detailed Notes & Instructions</label>
<textarea name="instructions" rows="4" placeholder="Mention photo count, custom texts, or specific decorations..."></textarea>
</div>
 
        <button type="submit" class="submit-btn">Submit Request</button>
</form>
</div>
 
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>