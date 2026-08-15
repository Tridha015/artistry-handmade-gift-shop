<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="form-container" style="max-width: 450px;">
    <h2>Login to Artistry</h2>
    <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Access your account and dashboard</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: #dc2626; background: #fee2e2; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../../controllers/AuthController.php" method="POST">
        <input type="hidden" name="action" value="login">

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; outline: none;" placeholder="Enter your password" required>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember" style="margin-bottom: 0; font-weight: normal; font-size: 14px;">Remember Me</label>
        </div>

        <button type="submit" class="btn-submit">Login</button>
    </form>

    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
        Don't have an account? <a href="register.php" style="color: #7c3aed; font-weight: bold; text-decoration: none;">Register here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>