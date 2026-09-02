<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$pageTitle = "Artistry of Tridha - Handmade Gift & Craft Shop";
require_once __DIR__ . '/View/layouts/header.php';
require_once __DIR__ . '/Model/ProductModel.php';

$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : null;
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

<!-- Add to Cart / Ready Products Section -->
<div id="store-products" style="max-width: 1200px; margin: 40px auto 20px auto; padding: 0 20px; font-family: sans-serif;">
    <div class="section-title" style="margin-bottom: 25px;">
        <h2>Ready-to-Ship Handmade Crafts</h2>
        <p>
            <?php if (!empty($selectedCategory)): ?>
                Showing category: <strong style="color: #4a154b;"><?php echo htmlspecialchars($selectedCategory); ?></strong> 
                — <a href="index.php#store-products" style="color: #c53030; text-decoration: underline; font-weight: bold; font-size: 13px;">(View All Items)</a>
            <?php else: ?>
                Browse newly crafted items available in stock and add them directly to your cart
            <?php endif; ?>
        </p>
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

                            <!-- button -->
                            <div style="display: flex; gap: 8px;">
                                <button type="button" 
                                        onclick='openProductModal(<?php echo json_encode([
                                            "id"          => $prod["id"],
                                            "title"       => $prod["title"],
                                            "category"    => $prod["category"],
                                            "price"       => number_format($prod["price"], 2),
                                            "stock"       => $prod["stock"],
                                            "size"        => !empty($prod["size"]) ? $prod["size"] : "Standard Craft Fit",
                                            "description" => !empty($prod["description"]) ? $prod["description"] : "Handcrafted with special care and premium materials. No extra description provided.",
                                            "image"       => !empty($prod["image"]) ? $prod["image"] : "sample1.jpg"
                                        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'
                                        style="flex: 1; padding: 9px 0; background: #fff; color: #341946; border: 1.5px solid #341946; border-radius: 4px; font-weight: 600; font-size: 13px; cursor: pointer;">
                                    View Details
                                </button>

                                <?php if ($prod['stock'] > 0): ?>
                                    <form action="Controller/CartController.php" method="POST" style="flex: 1; margin: 0;">
                                        <input type="hidden" name="action" value="add_to_cart">
                                        <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                        <button type="submit" style="width: 100%; background: #341946; color: white; border: none; padding: 9px 0; border-radius: 4px; font-weight: bold; font-size: 13px; cursor: pointer;">
                                            Add to Cart 🛒
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button disabled style="flex: 1; background: #ccc; color: #666; border: none; padding: 9px 0; border-radius: 4px; cursor: not-allowed; font-size: 13px;">
                                        Sold Out
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; border: 1px dashed #ccc; border-radius: 6px; color: #888; background: #fff;">
                <h3>No crafts listed in this category yet.</h3>
                <p style="margin-top: 8px;">Check other albums or request a custom order.</p>
                <a href="index.php#store-products" style="display: inline-block; margin-top: 10px; color: #4a154b; font-weight: bold;">View All Crafts</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Craft Albums Section -->
<div id="albums" class="section-title" style="margin-top: 50px;">
    <h2>Featured Craft Albums</h2>
    <p>Select a category to view items in stock</p>
</div>

<div class="album-grid">
    <div class="album-card">
        <div class="album-thumb">📖</div>
        <h3>Memory Scrapbooks</h3>
        <p>Custom storytelling photo albums with interactive fold-outs and notes.</p>
        <div class="album-price">Starts from 800 BDT</div>
        <a href="index.php?category=Scrapbook#store-products" class="btn-card">View Items</a> 
    </div>

    <div class="album-card">
        <div class="album-thumb">🎁</div>
        <h3>Explosion Boxes</h3>
        <p>Multi-layered surprise gift boxes packed with photo pockets and pop-ups.</p>
        <div class="album-price">Starts from 500 BDT</div>
        <a href="index.php?category=Explosion Box#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">💌</div>
        <h3>Greeting Cards</h3>
        <p>Intricate handmade 3D pop-up cards for birthdays, anniversaries & other special occasions.</p>
        <div class="album-price">Starts from 250 BDT</div>
        <a href="index.php?category=Greeting Cards#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">✒️</div>
        <h3>Nikah & Signature Pens</h3>
        <p>Custom feather and gold-accented pens for weddings and milestones.</p>
        <div class="album-price">Starts from 450 BDT</div>
        <a href="index.php?category=Nikah & Signature Pens#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">💐</div>
        <h3>Handmade Floral Bouquets</h3>
        <p>Everlasting craft paper, ribbon, and fabric flower arrangements.</p>
        <div class="album-price">Starts from 650 BDT</div>
        <a href="index.php?category=Floral Bouquets#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">🖼️</div>
        <h3>Photo Frames</h3>
        <p>Customized wall and desk frames with pictures, handmade lettering and motifs.</p>
        <div class="album-price">Starts from 300 BDT</div>
        <a href="index.php?category=Photo Frames#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">🍫</div>
        <h3>Chocolate Gift Hampers</h3>
        <p>Curated gift sets featuring craft organizers, treats, and message cards.</p>
        <div class="album-price">Starts from 1,100 BDT</div>
        <a href="index.php?category=Chocolate Gift Hampers#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">🏮</div>
        <h3>Shadow Boxes & Light Jars</h3>
        <p>Illuminated layered paper silhouettes and decorative craft jars.</p>
        <div class="album-price">Starts from 1,350 BDT</div>
        <a href="index.php?category=Shadow Boxes & Light Jars#store-products" class="btn-card">View Items</a>
    </div>

    <div class="album-card">
        <div class="album-thumb">🎨</div>
        <h3>Custom Illustration Art</h3>
        <p>Personalized portrait sketches and stylized handmade art pieces.</p>
        <div class="album-price">Starts from 900 BDT</div>
        <a href="index.php?category=Custom Illustration Art#store-products" class="btn-card">View Items</a>
    </div>
</div>

<!-- Craft Details Modal Popup -->
<div id="craftDetailsModal" style="display: none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.65); backdrop-filter: blur(4px); justify-content: center; align-items: center;">
    <div style="background: #fff; width: 92%; max-width: 650px; border-radius: 12px; padding: 25px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.25); font-family: sans-serif;">
        <button onclick="closeProductModal()" style="position: absolute; right: 15px; top: 12px; background: none; border: none; font-size: 28px; cursor: pointer; color: #888; line-height: 1;">&times;</button>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 10px;">
            <div style="flex: 1; min-width: 220px;">
                <img id="mProductImg" src="" alt="Craft Details" style="width: 100%; height: 260px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;" onerror="this.src='assets/images/sample1.jpg';">
            </div>

            <div style="flex: 1.2; min-width: 240px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <span id="mProductCategory" style="background: #f3e5f5; color: #6a1b9a; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold;">Category</span>
                    <h2 id="mProductTitle" style="margin: 8px 0; color: #2d3748; font-size: 20px;">Product Title</h2>
                    
                    <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 12px;">
                        <span id="mProductPrice" style="font-size: 20px; font-weight: bold; color: #2b6cb0;">৳ 0.00</span>
                        <span id="mProductStock" style="font-size: 12px; color: #38a169; font-weight: bold;">In Stock</span>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; font-size: 12px; color: #4a5568;">
                        <div style="margin-bottom: 4px;"><strong>Craft Type:</strong> 100% Authentic Handmade</div>
                        <div style="margin-bottom: 4px;"><strong>Size / Dimension:</strong> <span id="mProductSize" style="color: #572553; font-weight: bold;">Standard</span></div>
                        <div><strong>Care Instructions:</strong> Keep away from water & direct flame</div>
                    </div>

                    <div style="font-size: 13px; font-weight: bold; color: #2d3748; margin-bottom: 4px;">Product Description:</div>
                    <div id="mProductDesc" style="font-size: 13px; color: #4a5568; line-height: 1.5; max-height: 100px; overflow-y: auto; white-space: pre-line; background: #fff; padding: 4px 0;">
                    </div>
                </div>

                <form action="Controller/CartController.php" method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="action" value="add_to_cart">
                    <input type="hidden" name="product_id" id="mProductId" value="">
                    <button type="submit" id="mCartBtn" style="width: 100%; background: #341946; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                        Add to Cart 🛒
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openProductModal(product) {
    document.getElementById('mProductId').value = product.id;
    document.getElementById('mProductTitle').innerText = product.title;
    document.getElementById('mProductCategory').innerText = product.category;
    document.getElementById('mProductPrice').innerText = '৳ ' + product.price;
    document.getElementById('mProductSize').innerText = product.size;
    document.getElementById('mProductDesc').innerText = product.description;
    
    const stockEl = document.getElementById('mProductStock');
    const cartBtn = document.getElementById('mCartBtn');
    if (parseInt(product.stock) > 0) {
        stockEl.innerText = product.stock + ' in stock';
        stockEl.style.color = '#38a169';
        cartBtn.disabled = false;
        cartBtn.innerText = 'Add to Cart 🛒';
        cartBtn.style.background = '#341946';
        cartBtn.style.cursor = 'pointer';
    } else {
        stockEl.innerText = 'Out of Stock';
        stockEl.style.color = '#e53e3e';
        cartBtn.disabled = true;
        cartBtn.innerText = 'Sold Out';
        cartBtn.style.background = '#ccc';
        cartBtn.style.cursor = 'not-allowed';
    }

    document.getElementById('mProductImg').src = 'assets/images/uploads/' + product.image;
    document.getElementById('craftDetailsModal').style.display = 'flex';
}

function closeProductModal() {
    document.getElementById('craftDetailsModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('craftDetailsModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php
require_once __DIR__ . '/View/layouts/footer.php';
?>