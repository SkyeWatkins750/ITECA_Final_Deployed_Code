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
        <form action="../includes/CreateAccountFormhandler.inc.php" method="POST" onsubmit="return ValidateInputsUser()" novalidate>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name">
            <span class="error-message" id="nameError"></span>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
            <span class="error-message" id="emailError"></span>

            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <span class="error-message" id="passwordError"></span>
            
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password">
            <span class="error-message" id="confirm-passwordError"></span>

            <div>
                <label for="province">Province</label>
                <select class="province-select" name="province" id="province">
                    <option value="">Select Province</option>
                </select>
            </div>
            <span class="error-message" id="provinceError"></span>

            <!-- City -->
            <div>
                <label for="city">City</label>
                <input type="text" name="city" id="city">
            </div>
            <span class="error-message" id="cityError"></span>

            <!-- Street Address -->
            <div>
                <label for="street">Street Address</label>
                <input type="text" name="street" id="street">
            </div>
            <span class="error-message" id="streetError"></span>

            <!-- Postal Code -->
            <div>
                <label for="postal">Postal Code</label>
                <input type="number" name="postal" id="postal" pattern="\d{4}">
            </div>
            <span class="error-message" id="postalError"></span>

            <button type="submit">Sign Up</button>

            <div class="links">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
    <script>
        function ValidateInputsUser() {
            let isValid = true;

            const name = document.getElementById("name");
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const confirm_password = document.getElementById("confirm-password");
            const province = document.getElementById("province");
            const city = document.getElementById("city");
            const streetAddress = document.getElementById("street");
            const postal = document.getElementById("postal");

            const nameError = document.getElementById("nameError");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");
            const confirm_passwordError = document.getElementById("confirm-passwordError");
            const provinceError = document.getElementById("provinceError");
            const cityError = document.getElementById("cityError");
            const streetAddressError = document.getElementById("streetError");
            const postalError = document.getElementById("postalError");

            nameError.textContent = "";
            emailError.textContent = "";
            passwordError.textContent = "";
            confirm_passwordError.textContent = "";
            provinceError.textContent = "";
            cityError.textContent = "";
            streetAddressError.textContent = "";
            postalError.textContent = "";

            if (name.value.trim() === "") {
                nameError.textContent = "Name is required.";
                isValid = false;
            }

            if (email.value.trim() === "") {
                emailError.textContent = "Email is required.";
                isValid = false;
            }

            if (province.value === "") {
                provinceError.textContent = "Province is required.";
                isValid = false;
            }

            if (city.value.trim() === "") {
                cityError.textContent = "City is required.";
                isValid = false;
            }

            if (streetAddress.value.trim() === "") {
                streetAddressError.textContent = "Address is required.";
                isValid = false;
            }

            if (postal.value.trim() === "") {
                postalError.textContent = "Postal code is required.";
                isValid = false;
            } else if (postal.value.trim().length < 4) {
                postalError.textContent = "Postal code is not complete.";
                isValid = false;
            }else if (postal.value.trim().length > 4) {
                postalError.textContent = "Postal code is too long.";
                isValid = false;
            }

            if (password.value.trim() === "") {
                passwordError.textContent = "Password is required.";
                isValid = false;
            }

            if (confirm_password.value.trim() === "") {
                confirm_passwordError.textContent = "Confirm password is required.";
                isValid = false;
            } else if (password.value.trim() !== confirm_password.value.trim()) {
                confirm_passwordError.textContent = "Passwords do not match.";
                isValid = false;
            }

            return isValid;
        }
    </script>
    
</body>
</html>