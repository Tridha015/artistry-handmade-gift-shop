<?php session_start();
require_once __DIR__ . '/../layouts/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Artistry Craft Platform</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div style="max-width: 400px; margin: 60px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; font-family: sans-serif;">
        <h2 style="text-align: center; margin-bottom: 20px;">Login Now!</h2>

        <?php if (isset($_GET['error'])): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="../../Controller/AuthController.php" method="POST">
            <input type="hidden" name="action" value="login">

            <div style="margin-bottom: 15px;">
                <label>Email Address</label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Password</label><br>
                <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <button type="submit" style="width: 100%; padding: 10px; background-color:rgb(97, 38, 100); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Login
            </button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="register.php" style="color: #795548;">Register here</a>
        </p>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>