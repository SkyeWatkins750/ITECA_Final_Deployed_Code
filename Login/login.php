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
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/loginStyles.css" type="text/css">

</head>
<body>
    <?php
    require_once '../config.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        $emailError = $_SESSION['emailError'] ?? '';
        $passwordError = $_SESSION['passwordError'] ??'';

        $email = $_SESSION['email'] ?? '';

        unset($_SESSION['emailError'], $_SESSION['passwordError'], $_SESSION['email']);
    ?>
    <div class="login-container">
        <h2>Login to Your Account</h2>
        <form action="../includes/loginFormHandler.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="error"><?php echo $emailError; ?></div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <div class="error"><?php echo $passwordError; ?></div>

            <button type="submit">Login</button>

            <div class="links">
                <a href="createAccount.php">Create an Account</a>
            </div>
        </form>
    </div>

</body>
</html>