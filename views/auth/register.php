<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-container" style="max-width: 450px;">
    <h2>Create an Account</h2>
    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Join Artistry to place orders and track crafts</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../controllers/AuthController.php" method="POST">
        <input type="hidden" name="action" value="register">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Your Name" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@gmail.com" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="018XXXXXXXX" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; outline: none;" placeholder="Choose a password" required>
        </div>

        <button type="submit" class="btn-submit">Register</button>
    </form>

    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
        Already have an account? <a href="login.php" style="color: #7c3aed; font-weight: bold; text-decoration: none;">Login here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>