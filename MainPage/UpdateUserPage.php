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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/createListingPageStyles.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="../JS/UpdateUserJS..js"></script>
    
</head>
<body>
    <?php
    require_once '../includes/UpateUserGetFormHandler.php';
    $user = getUserDetails();
    ?>
    <!-- Header -->
    <div class="page_header">
            <header>
                <h1 id="HeaderHeading">MyTrader</h1>

                <a href="MainPage.php" class="home-page-button">
                    <i class="bi bi-house" style="margin-right: 8px;"></i>Home Page
                </a>
                <a href="MyListingsPage.php" class="my-listings-button">
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
                        <a class="logout" href="../Login/login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
                    </div>
                </div>
            </header>
        </div>
    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2>Edit User Details</h2>
            <form action="../includes/UpdateUserFormHandler.php" method="POST" onsubmit="return ValidateInputsUser()" novalidate>
                <!-- Full Name -->
                <label for="fullName">Full Name</label>
                <input type="text" name="fullName" id="fullName" value="<?=htmlspecialchars($user['fullname'])?>">
                <span class="error-message" id="fullNameError"></span>

                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?=htmlspecialchars($user['email'])?>">
                <span class="error-message" id="emailError"></span>

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter new password">
                <span class="error-message" id="passwordError"></span>

                <!-- Province -->
                <label for="province">Province</label>
                <select name="province" id="province">
                    <option value="">Select a province</option>
                </select>
                <span class="error-message" id="provinceError"></span>

                <!-- City -->
                <label for="city">City</label>
                <input type="text" name="city" id="city" value="<?=htmlspecialchars($user['City'])?>">
                <span class="error-message" id="cityError"></span>

                <!-- Street Address -->
                <label for="streetAddress">Street Address</label>
                <input type="text" name="streetAddress" id="streetAddress" value="<?=htmlspecialchars($user['StreetAddress'])?>">
                <span class="error-message" id="streetAddressError"></span>

                <!-- Postal Code -->
                <label for="postalCode">Postal Code</label>
                <input type="text" name="postalCode" id="postalCode" value="<?=htmlspecialchars($user['PostalCode'])?>">
                <span class="error-message" id="postalCodeError"></span>

                <input type="hidden" name="userId" value="">

                <button type="submit">Save Details</button>
            </form>
        </div>
    </div>
    <script>
        const selectedProvince = <?= json_encode($user['Province']); ?>;
        document.addEventListener("DOMContentLoaded", () => {
            const provinces = [
                "Eastern Cape",
                "Free State",
                "Gauteng",
                "KwaZulu-Natal",
                "Limpopo",
                "Mpumalanga",
                "North West",
                "Northern Cape",
                "Western Cape"
            ];

            const provinceSelect = document.getElementById("province");
            provinces.forEach(province => {
                const option = document.createElement("option");
                option.value = province;
                option.textContent = province;

                if(province === selectedProvince) {
                    option.selected = true;
                }

                provinceSelect.appendChild(option);
            });
        });

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