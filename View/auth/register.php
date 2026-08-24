<?php
session_start();
require_once __DIR__ . '/../layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Artistry Craft Platform</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div style="max-width: 450px; margin: 40px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; font-family: sans-serif; background: #fff;">
        <h2 style="text-align: center; margin-bottom: 20px;">Create an Account</h2>

        <?php if (isset($_GET['error'])): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="../../Controller/AuthController.php" method="POST">
            <input type="hidden" name="action" value="register">

            <div style="margin-bottom: 12px;">
                <label>Full Name</label><br>
                <input type="text" name="name" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Email Address</label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Phone Number</label><br>
                <input type="text" name="phone" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 12px;">
                <label>Password</label><br>
                <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 18px;">
                <label>Join As</label><br>
                <select name="role" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
                    <option value="Customer">Customer (Shop & Order)</option>
                    <option value="Seller">Seller (Requires Approval)</option>
                    <option value="Delivery">Delivery Rider (Requires Approval)</option>
                </select>
            </div>

            <button type="submit" style="width: 100%; padding: 10px; background-color: rgb(87, 37, 83); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Register
            </button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php" style="color: #795548;">Login here</a>
        </p>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/../layouts/footer.php';
?>