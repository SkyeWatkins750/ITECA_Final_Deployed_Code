<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../CSS/loginStyles.css">
    <script src="../JS/createAccountPageJS.js"></script>
</head>
<body>
    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form action="../includes/CreateAccountFormhandler.inc.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <div>
                <label for="province">Province</label>
                <select class="province-select" name="province" id="province">
                    <option value="">Select Province</option>
                </select>
            </div>

            <!-- City -->
            <div>
                <label for="city">City</label>
                <input type="text" name="city" id="city">
            </div>

            <!-- Street Address -->
            <div>
                <label for="street">Street Address</label>
                <input type="text" name="street" id="street">
            </div>

            <!-- Postal Code -->
            <div>
                <label for="postal">Postal Code</label>
                <input type="number" name="postal" id="postal" pattern="\d{4}">
            </div>

            <button type="submit">Sign Up</button>

            <div class="links">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
    
</body>
</html>