<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-container">
    <h2>Customize Your Order</h2>
    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Fill in your details and custom preferences. We will review and reach out to confirm your order.</p>

    <form action="../../controllers/CustomOrderController.php" method="POST" enctype="multipart/form-data" id="customOrderForm" onsubmit="return validateForm()">
        <input type="hidden" name="action" value="custom_order">

        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="full_name" id="fullName" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
        </div>

        <div class="form-group">
            <label>Phone Number (WhatsApp preferred) *</label>
            <input type="text" name="phone" id="phone" placeholder="e.g. 01852275057" required>
        </div>

        <div class="form-group">
            <label>Delivery Address *</label>
            <textarea name="address" id="address" rows="3" placeholder="Full home delivery address" required></textarea>
        </div>

        <div class="form-group">
            <label>Select Product Category *</label>
            <select name="category" required>
                <option value="Scrapbook">1. Scrapbook</option>
                <option value="Explosion Box">2. Explosion Box</option>
                <option value="Gift Card">3. Gift Card</option>
                <option value="Photo Frame">4. Photo Frame</option>
                <option value="Envelope">5. Envelope</option>
                <option value="Bookmark">6. Bookmark</option>
                <option value="Phone Cover">7. Phone Cover</option>
                <option value="Flower Bouquet">8. Flower Bouquet</option>
                <option value="Nikah Pen">9. Nikah Pen</option>
                <option value="Painting & Props">10. Painting & Props</option>
            </select>
        </div>

        <div class="form-group">
            <label>Preferred Color(s)</label>
            <input type="text" name="preferred_color" placeholder="e.g. Pastel Pink, Maroon, Black & Gold">
        </div>

        <div class="form-group">
            <label>Select Size *</label>
            <div class="radio-group">
                <label><input type="radio" name="size" value="S" checked> Small (S)</label>
                <label><input type="radio" name="size" value="M"> Medium (M)</label>
                <label><input type="radio" name="size" value="L"> Large (L)</label>
            </div>
        </div>

        <div class="form-group">
            <label>Number of Pages (For Scrapbook: Min 2 - Max 20)</label>
            <input type="number" name="scrapbook_pages" min="2" max="20" placeholder="e.g. 10">
        </div>

        <div class="form-group">
            <label>Number of Images *</label>
            <div class="radio-group">
                <label><input type="radio" name="num_images" value="5" checked> 5</label>
                <label><input type="radio" name="num_images" value="10"> 10</label>
                <label><input type="radio" name="num_images" value="15"> 15</label>
                <label><input type="radio" name="num_images" value="20"> 20</label>
                <label><input type="radio" name="num_images" value="25"> 25</label>
                <label><input type="radio" name="num_images" value="30"> 30</label>
                <label><input type="radio" name="num_images" value="more than 30"> >30</label>
            </div>
        </div>

        <div class="form-group">
            <label>Select Your Budget Range (BDT) *</label>
            <div class="radio-group">
                <label><input type="radio" name="budget_range" value="Below 1000 Tk" checked> Below 1000 Tk</label>
                <label><input type="radio" name="budget_range" value="1000 - 2000 Tk"> 1000 – 2000 Tk</label>
                <label><input type="radio" name="budget_range" value="2000 - 3000 Tk"> 2000 – 3000 Tk</label>
                <label><input type="radio" name="budget_range" value="Above 3000 Tk"> Above 3000 Tk</label>
            </div>
        </div>

        <div class="form-group">
            <label>Upload Reference/Sample Image</label>
            <input type="file" name="reference_image" accept="image/*,.pdf">
        </div>

        <button type="submit" class="btn-submit">Submit Custom Order</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>