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
    <title>Item Details</title>
    <link rel="stylesheet" href="../CSS/detailPageStyles.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <?php
    require_once '../includes/DetailPageFormHandler.php';
    ?>
    <!-- Header -->
    <div class="page_header">
            <header>
                <h1 id="HeaderHeading">MyTrader</h1>

                <a href="../Cart/CartMainPage.php" class="cart-container">
                    <i class="bi bi-cart cart-icon"></i>
                    <span id="cart-count" class="cart-count">0</span>
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
     <div class = "main-content">
        <div class="product-details-container">
            <div class="product-image">
                <img src="../images/<?=htmlspecialchars($item['imagePath']) ?>" class="item-image">
            </div>
            <div class="product-info">
                <h1 class="product-name"><?= htmlspecialchars($item['itemName']) ?></h1>
                <p class="product-price"><?= "R" . number_format($item['price'], 2) ?></p>
                <p class="product-category"><?= htmlspecialchars($item['Category_Name']) ?></p>
                <p class="product-condition"><?= htmlspecialchars($item['Condition_Name']) ?></p>
                <p class="product-date"><?= htmlspecialchars($item['dateListed']) ?></p>
                <p class="product-description"><?= htmlspecialchars($item['description']) ?></p>
                <button id="addToCartBtn" class="cart-button <?= $inCart ? 'cart-green' : '' ?>" data-item-id="<?= htmlspecialchars($item['id']) ?>"
                    <?= $inCart ? 'disabled' : '' ?>>
                    <i class="bi <?= $inCart ? 'bi-cart-check' : 'bi-cart-plus' ?>"></i>
                    <?= $inCart ? 'Added to Cart' : 'Add to Cart' ?>
                </button>

                <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] === 'admin'): ?>
                    <form action="../MainPage/UpdateListingPage.php" method="POST">
                        <input type="hidden" name="itemId" value="<?= htmlspecialchars($item['id']) ?>">
                        <button type="submit" class="edit-button">
                            <i class="bi bi-pencil-square"></i> Edit Listing
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
         <div class="contact-seller">
            <h2>Seller Information</h2>
            <div class="contact-details">
                <p><strong>Province:</strong> <?=htmlspecialchars($item['Province'])?></p>
                <p><strong>City:</strong> <?=htmlspecialchars($item['City'])?></p>
                <p><strong>Address:</strong> <?=htmlspecialchars($item['StreetAddress'])?></p>
                <p><strong>Postal Code:</strong> <?=htmlspecialchars($item['Postal_Code'])?></p>
                <p><strong>Email:</strong> <a ><?=htmlspecialchars($item['email'])?></a></p>
            </div>
        </div>
     </div>
     <script>
        const cartButton = document.getElementById('addToCartBtn');

        cartButton.addEventListener('click', () => {
            const itemId = cartButton.getAttribute('data-item-id');

            fetch('../includes/DetailPageAddFormHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(itemId)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartButton.innerHTML = '<i class="bi bi-cart-check"></i> Added to Cart';
                    cartButton.classList.add('cart-green');
                    cartButton.disabled = true;
                    updateCartCount();
                } else {
                    alert('An error occurred.');
                }
            })
            .catch(() => {
                alert('An error occurred.');
            });
        });

        function updateCartCount() {
            fetch('../includes/getCartCount.php')
            .then(res => res.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                cartCount.textContent = data.count;
            })
            .catch(err =>{
                console.error("Failed to fetch cart count", err);
            });
        }

        updateCartCount();

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