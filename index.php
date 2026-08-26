<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$pageTitle = "Artistry of Tridha - Handmade Gift & Craft Shop";
require_once __DIR__ . '/View/layouts/header.php';
require_once __DIR__ . '/Model/ProductModel.php';
$selectedCategory = $_GET['category'] ?? null;
if (!empty($selectedCategory)) {
    $allProducts = getProductsByCategory($selectedCategory);
} else {
    $allProducts = getAllProducts();
}
?>

<div class="hero-banner">
    <div class="hero-content">
        <h1>Love, Creativity & Dedication,<br><span class="highlight-text">that's what goes with handmade</span></h1>
        <p>From memory scrapbooks and explosion boxes to personalized gifts—everything in one place.</p>
        <div class="hero-buttons">
            <a href="View/customer/custom_order.php" class="btn-hero-primary">Request Custom Order</a>
            <a href="#albums" class="btn-hero-secondary">Explore Craft Albums ↓</a>
        </div>
    </div>
</div>

<!-- Add to Cart Section -->
<div style="max-width: 1200px; margin: 40px auto 20px auto; padding: 0 20px; font-family: sans-serif;">
    <div class="section-title" style="margin-bottom: 25px;">
        <h2>Ready-to-Ship Handmade Crafts</h2>
        <p>Browse newly crafted items available in stock and add them directly to your cart</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
        <?php if ($allProducts && mysqli_num_rows($allProducts) > 0): ?>
            <?php while ($prod = mysqli_fetch_assoc($allProducts)): ?>
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
                    <img src="assets/images/uploads/<?php echo htmlspecialchars($prod['image'] ?? 'default_craft.jpg'); ?>" alt="Craft" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.src='assets/images/sample1.jpg';">
                    
                    <div style="padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; background: #f3e5f5; color: #6a1b9a; padding: 3px 6px; border-radius: 3px; font-weight: bold;"><?php echo htmlspecialchars($prod['category']); ?></span>
                            <h3 style="margin: 10px 0 5px 0; font-size: 16px; color: #2d3748;"><?php echo htmlspecialchars($prod['title']); ?></h3>
                            <p style="margin: 0; font-size: 12px; color: #718096;">Artisan: <b>Artistry In-House Crafts</b></p>
                        </div>

                        <div style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-size: 18px; font-weight: bold; color: #2b6cb0;">৳ <?php echo number_format($prod['price'], 2); ?></span>
                                <span style="font-size: 12px; color: <?php echo ($prod['stock'] > 0) ? '#38a169' : '#e53e3e'; ?>;">
                                    <?php echo ($prod['stock'] > 0) ? $prod['stock'] . ' in stock' : 'Out of Stock'; ?>
                                </span>
                            </div>

                            <?php if ($prod['stock'] > 0): ?>
                                <form action="Controller/CartController.php" method="POST">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                    <button type="submit" style="width: 100%; background: #341946; color: white; border: none; padding: 9px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                                        Add to Cart 🛒
                                    </button>
                                </form>
                            <?php else: ?>
                                <button disabled style="width: 100%; background: #ccc; color: #666; border: none; padding: 9px; border-radius: 4px; cursor: not-allowed;">
                                    Sold Out
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; border: 1px dashed #ccc; border-radius: 6px; color: #888;">
                <h3>No crafts listed in store right now. Check back soon!</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Craft Albums Section -->
<div id="albums" class="section-title" style="margin-top: 50px;">
    <h2>Featured Craft Albums</h2>
    <p>Select a category to browse ready items or order custom handmade creations</p>
</div>

<div class="album-grid">

    <!-- Album 1 -->
    <div class="album-card">
        <div class="album-thumb">📖</div>
        <h3>Memory Scrapbooks</h3>
        <p>Custom storytelling photo albums with interactive fold-outs and notes.</p>
        <div class="album-price">Starts from 800 BDT</div>
        <a href="index.php?category=Scrapbook#store-products" class="btn-card">View Items</a> 
    </div>

    <!-- Album 2 -->
    <div class="album-card">
        <div class="album-thumb">🎁</div>
        <h3>Explosion Boxes</h3>
        <p>Multi-layered surprise gift boxes packed with photo pockets and pop-ups.</p>
        <div class="album-price">Starts from 500 BDT</div>
        <a href="index.php?category=explosion_box#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 3 -->
    <div class="album-card">
        <div class="album-thumb">💌</div>
        <h3>Greeting Cards</h3>
        <p>Intricate handmade 3D pop-up cards for birthdays, anniversaries & other special occasions.</p>
        <div class="album-price">Starts from 250 BDT</div>
        <a href="index.php?category=popup_card#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 4 -->
    <div class="album-card">
        <div class="album-thumb">✒️</div>
        <h3>Nikah & Signature Pens</h3>
        <p>Custom feather and gold-accented pens for weddings and milestones.</p>
        <div class="album-price">Starts from 450 BDT</div>
        <a href="index.php?category=nikah_pen#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 5 -->
    <div class="album-card">
        <div class="album-thumb">💐</div>
        <h3>Handmade Floral Bouquets</h3>
        <p>Everlasting craft paper, ribbon, and fabric flower arrangements.</p>
        <div class="album-price">Starts from 650 BDT</div>
        <a href="index.php?category=bouquet#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 6 -->
    <div class="album-card">
        <div class="album-thumb">🖼️</div>
        <h3>Photo Frames</h3>
        <p>Customized wall and desk frames with pictures, handmade lettering and motifs.</p>
        <div class="album-price">Starts from 300 BDT</div>
        <a href="index.php?category=frame#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 7 -->
    <div class="album-card">
        <div class="album-thumb">🍫</div>
        <h3>Chocolate Gift Hampers</h3>
        <p>Curated gift sets featuring craft organizers, treats, and message cards.</p>
        <div class="album-price">Starts from 1,100 BDT</div>
        <a href="index.php?category=hamper#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 8 -->
    <div class="album-card">
        <div class="album-thumb">🏮</div>
        <h3>Shadow Boxes & Light Jars</h3>
        <p>Illuminated layered paper silhouettes and decorative craft jars.</p>
        <div class="album-price">Starts from 1,350 BDT</div>
        <a href="index.php?category=shadow_box#store-products" class="btn-card">View Items</a>
    </div>

    <!-- Album 9 -->
    <div class="album-card">
        <div class="album-thumb">🎨</div>
        <h3>Custom Illustration Art</h3>
        <p>Personalized portrait sketches and stylized handmade art pieces.</p>
        <div class="album-price">Starts from 900 BDT</div>
        <a href="index.php?category=illustration#store-products" class="btn-card">View Items</a>
    </div>

</div>

<?php
require_once __DIR__ . '/View/layouts/footer.php';
?>