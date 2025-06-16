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
    <title>Cart</title>
    <link rel="stylesheet" href="../CSS/cartPageStyles.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <?php 
        require_once '../includes/CartMainPageGetFormHandler.php';
    ?>
    <!-- Header -->
    <div class="page_header">
        <header>
            <h1 id="HeaderHeading">MyTrader</h1>

            <a href="../MainPage/MainPage.php" class="home-page-button">
                <i class="bi bi-house" style="margin-right: 8px;"></i>Home Page
            </a>
            <a href="../MainPage/MyListingsPage.php" class="my-listings-button">
                <i class="bi bi-card-list" style="margin-right: 8px;"></i>My Listings
            </a>

            <div class="settingsButton">
                <button class="buttonSettings">
                    <svg
                    class="settings-btn"
                    height="24"
                    viewBox="0 -960 960 960"
                    width="24"
                    fill="white"
                    >
                        <path
                            d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 ```html
                            99t99.5 41Zm-2-140Z"
                        ></path>
                    </svg>
                </button>
                <div class="settings-dropdown" id="settingsDropdown">
                    <a href="../MainPage/UpdateUserPage.php">Edit Profile</a>
                    <a class="logout" href="../Login/login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </div>
        </header>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="cart-heading">Your Cart</h2>

        <?php 
            echo getCartItemDetails();
        ?>

        <!-- Total and checkout -->
        <div class="cart-summary">
            <span class="total">Total: R<?=getTotalPrice();?></span>
            <form action="../Cart/PaymentDetailsPage.php" method="POST">
                <button class="checkout-btn">Checkout</button>
            </form>
        </div>
        
    </div>

    <script>
        function deleteCartItem(element) {
            const itemId = element.getAttribute('data-id');

            fetch('../includes/CartDeleteItemFormHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(itemId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartItem = element.closest('form');
                    cartItem.remove();

                    document.querySelector('.total').textContent = `Total: R${data.newTotal}`;
                } else {
                    alert('Failed to delete item from cart.');
                }
            })
            .catch(err =>{
                console.error('Error: ', err);
                alert('Something went wrong.')
            });
        }

        document.querySelector(".buttonSettings").addEventListener("click", function (e) {
            e.stopPropagation();
            const dropdown = document.getElementById("settingsDropdown");
            const button = e.currentTarget;

            if (dropdown.style.display === "flex") {
                dropdown.style.display = "none";
                return;
            }

            dropdown.style.display = "flex";

            dropdown.style.right = "";
            dropdown.style.left = "";

            const dropdownRect = dropdown.getBoundingClientRect();
            const buttonRect = button.getBoundingClientRect();
            const viewportWidth = window.innerWidth;

            const spaceOnRight = viewportWidth - buttonRect.right;

            if (dropdownRect.right > viewportWidth && dropdownRect.width > spaceOnRight) {
                dropdown.style.left = "auto";
                dropdown.style.right = (viewportWidth - buttonRect.left) + "px"; 
            } else {
                dropdown.style.left = buttonRect.left + "px";
                dropdown.style.right = "auto";
            }
        });
    </script>
</body>
</html>