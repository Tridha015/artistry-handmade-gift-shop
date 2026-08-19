<?php
$pageTitle = "Artistry of Tridha - Handmade Gift & Craft Shop";
require_once __DIR__ . '/View/layouts/header.php';
?>

<div class="hero-banner">
    <div class="hero-content">
        <h1>Love,Creativity & Dedication,<br><span class="highlight-text">that's what goes with handmade</span></h1>
        <p>From memory scrapbooks and explosion boxes to personalized gifts—everything in one place.</p>
        <div class="hero-buttons">
            <a href="View/customer/custom_order.php" class="btn-hero-primary">Request Custom Order</a>
            <a href="#albums" class="btn-hero-secondary">Explore Craft Albums ↓</a>
        </div>
    </div>
</div>

<!-- Craft Albums Section -->
<div id="albums" class="section-title">
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
        <a href="View/customer/custom_order.php?category=scrapbook" class="btn-card">View items</a>
    </div>

    <!-- Album 2 -->
    <div class="album-card">
        <div class="album-thumb">🎁</div>
        <h3>Explosion Boxes</h3>
        <p>Multi-layered surprise gift boxes packed with photo pockets and pop-ups.</p>
        <div class="album-price">Starts from 500 BDT</div>
        <a href="View/customer/custom_order.php?category=explosion_box" class="btn-card">View items</a>
    </div>

<!-- Album 3 -->
<div class="album-card">
        <div class="album-thumb">💌</div>
        <h3>Greeting Cards</h3>
        <p>Intricate handmade 3D pop-up cards for birthdays,anniversaries & other special occasions.</p>
        <div class="album-price">Starts from 250 BDT</div>
        <a href="View/customer/custom_order.php?category=popup_card" class="btn-card">View items</a>
    </div>

    <!-- Album 4 -->
    <div class="album-card">
        <div class="album-thumb">✒️</div>
        <h3>Nikah & Signature Pens</h3>
        <p>Custom feather and gold-accented pens for weddings and milestones.</p>
        <div class="album-price">Starts from 450 BDT</div>
        <a href="View/customer/custom_order.php?category=nikah_pen" class="btn-card">View items</a>
    </div>

    <!-- Album 5 -->
    <div class="album-card">
        <div class="album-thumb">💐</div>
        <h3>Handmade Floral Bouquets</h3>
        <p>Everlasting craft paper, ribbon, and fabric flower arrangements.</p>
        <div class="album-price">Starts from 650 BDT</div>
        <a href="View/customer/custom_order.php?category=bouquet" class="btn-card">View items</a>
    </div>

    <!-- Album 6 -->
    <div class="album-card">
        <div class="album-thumb">🖼️</div>
        <h3>Photo Frames</h3>
        <p>Customized wall and desk frames with pictures,handmade lettering and motifs.</p>
        <div class="album-price">Starts from 300 BDT</div>
        <a href="View/customer/custom_order.php?category=frame" class="btn-card">View items</a>
    </div>

    <!-- Album 7 -->
    <div class="album-card">
        <div class="album-thumb">🍫</div>
        <h3>Chocolate Gift Hampers</h3>
        <p>Curated gift sets featuring craft organizers, treats, and message cards.</p>
        <div class="album-price">Starts from 1,100 BDT</div>
        <a href="View/customer/custom_order.php?category=hamper" class="btn-card">View items</a>
    </div>

    <!-- Album 8 -->
    <div class="album-card">
        <div class="album-thumb">🏮</div>
        <h3>Shadow Boxes & Light Jars</h3>
        <p>Illuminated layered paper silhouettes and decorative craft jars.</p>
        <div class="album-price">Starts from 1,350 BDT</div>
        <a href="View/customer/custom_order.php?category=shadow_box" class="btn-card">View items</a>
    </div>

    <!-- Album 9 -->
    <div class="album-card">
        <div class="album-thumb">🎨</div>
        <h3>Custom Illustration Art</h3>
        <p>Personalized portrait sketches and stylized handmade art pieces.</p>
        <div class="album-price">Starts from 900 BDT</div>
        <a href="View/customer/custom_order.php?category=illustration" class="btn-card">View items</a>
    </div>

</div>

<?php
require_once __DIR__ . '/View/layouts/footer.php';
?>