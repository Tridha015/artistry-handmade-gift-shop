<?php
session_start();
$pageTitle = "Request Custom Craft - Artistry of Tridha";
require_once __DIR__ . '/../layouts/header.php';

$username = $_SESSION["loggedInUsername"] ?? "Customer";
?>

<style>
    .form-wrapper { width: 60%; margin: 40px auto; background: #ffffff; padding: 30px 40px; border: 1px solid #dddddd; font-family: Arial, sans-serif; }
    .form-title { border-bottom: 1px solid #eeeeee; padding-bottom: 15px; margin-bottom: 25px; }
    .form-title h2 { margin: 0 0 5px 0; color: #222; font-size: 24px; }
    .form-title p { margin: 0; color: #666; font-size: 15px; }
    .input-group { margin-bottom: 18px; overflow: hidden; }
    .input-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #222; font-size: 14px; }
    .input-group input[type="text"], 
    .input-group input[type="number"], 
    .input-group select, 
    .input-group textarea { width: 100%; padding: 10px; border: 1px solid #cccccc; box-sizing: border-box; font-size: 14px; }
    .col-half { width: 48%; float: left; }
    .col-right { float: right; }
    .submit-btn { width: 100%; background-color: #4a235a; color: white; padding: 12px; border: none; font-size: 16px; cursor: pointer; margin-top: 15px; font-weight: bold; }
    .submit-btn:hover { background-color: #381a44; }
</style>

<div class="form-wrapper">
    <div class="form-title">
        <h2>Request a Custom Handmade Craft</h2>
        <p>Provide details for your personalized handmade item.</p>
    </div>

    <form action="../../Controller/customOrderController.php" method="POST" enctype="multipart/form-data">
        
        <div class="input-group">
            <label>Select Craft Category</label>
            <select name="category" required>
                <option value="">-- Choose Category --</option>
                <option value="Scrapbook">Scrapbook</option>
                <option value="Explosion Box">Explosion Box</option>
                <option value="Greeting Cards">Greeting Cards</option>
                <option value="Floral Bouquets">Handmade Floral Bouquets</option>
            </select>
        </div>

        <div class="input-group">
            <div class="col-half">
                <label>Size Option</label>
                <select name="size">
                    <option value="Small">Small (Standard)</option>
                    <option value="Medium">Medium</option>
                    <option value="Large">Large</option>
                </select>
            </div>
            <div class="col-half col-right">
                <label>Layers / Pages</label>
                <input type="text" name="layers" placeholder="e.g. 3 Layers">
            </div>
        </div>

        <div class="input-group">
            <div class="col-half">
                <label>Preferred Color Theme</label>
                <input type="text" name="color_theme" placeholder="e.g. Maroon & Gold">
            </div>
            <div class="col-half col-right">
                <label>Offered Budget (BDT)</label>
                <input type="number" name="budget" placeholder="e.g. 1500" required>
            </div>
        </div>

        <div class="input-group" style="clear: both; padding-top: 10px;">
            <label>Upload Sample Reference Photo</label>
            <input type="file" name="sample_photo" accept="image/*" style="border: none; padding: 0;">
        </div>

        <div class="input-group">
            <label>Customization Notes</label>
            <textarea name="notes" rows="4" placeholder="Write custom messages, dates or requests..."></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit Custom Order Request</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>