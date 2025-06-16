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
    <link rel="stylesheet" href="../CSS/createListingPageStyles.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <title>Create Listing</title>
</head>
<body>
<?php
    require_once '../includes/CreateListingPageGetFormHandler.php'
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
                        <a href="../MainPage/UpdateUserPage.php">Edit Profile</a>
                        <a class="logout" href="../Login/login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
                    </div>
                </div>
            </header>
        </div>

        <!-- Main Content -->
         <div class="main-content">
            <div class="form-container">
                <h2>Create a Product Listing</h2>
                <form action="../includes/CreateListingPageFormHandler.php" method="POST" enctype="multipart/form-data" onsubmit="return ValidateInputs()" novalidate>
                    <!-- Item Name -->
                    <label for="itemName">Item Name</label>
                    <input type="text" name="itemName" id="itemName" placeholder="Enter Item Name">
                    <span class="error-message" id="itemNameError"></span>

                    <!-- Price -->
                    <label for="price">Price (R)</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" placeholder="Enter Price">
                    <span class="error-message" id="priceError"></span>

                    <!-- Category -->
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="">Select Category</option>
                        <?php $categories = getCategoriesCreate();
                            foreach($categories as $category):
                        ?>
                            <option value="<?=htmlspecialchars($category['categoryName']) ?>"><?=htmlspecialchars($category['categoryName']) ?></option>
                        <?php endforeach;?>
                    </select>
                    <span class="error-message" id="categoryError"></span>

                    <!-- Condition -->
                    <label for="condition">Condition</label>
                    <select name="condition" id="condition">
                        <option value="">Select Condition</option>
                        <?php $conditions = getConditions();
                            foreach($conditions as $condition):
                        ?>
                            <option value="<?=htmlspecialchars($condition['conditionName']) ?>"><?=htmlspecialchars($condition['conditionName']) ?></option>
                        <?php endforeach;?>                     
                    </select>
                    <span class="error-message" id="conditionError"></span>

                    <!-- Image -->
                    <label for="image">Product Image</label>
                    <div id="imagePreviewContainer" style="margin-top: 10px; display: none;">
                        <img id="imagePreview" src="" alt="Image Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                    </div>
                    <input type="file" name="image" id="image" accept="image/*">
                    <span class="error-message" id="imageError"></span>
                    
                    <!-- Description -->
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="5" placeholder="Describe the item..."></textarea>
                    <span class="error-message" id="descriptionError"></span>

                    <button type="submit">Create Listing</button>
                </form>
            </div>
        </div>
    <script>
        function ValidateInputs() {
            isValid = true;

            const itemName = document.getElementById("itemName");
            const price = document.getElementById("price");
            const category = document.getElementById("category");
            const condition = document.getElementById("condition");
            const image = document.getElementById("image");
            const description = document.getElementById("description");

            const itemNameError = document.getElementById("itemNameError");
            const priceError = document.getElementById("priceError");
            const categoryError = document.getElementById("categoryError");
            const conditionError = document.getElementById("conditionError");
            const imageError = document.getElementById("imageError");
            const descriptionError = document.getElementById("descriptionError");

            itemNameError.textContent = "";
            priceError.textContent = "";
            categoryError.textContent = "";
            conditionError.textContent = "";
            imageError.textContent = "";
            descriptionError.textContent = "";

            if (itemName.value.trim() === "") {
                itemNameError.textContent = "Item name is required.";
                isValid = false;
            }
            if (price.value.trim() === "") {
                priceError.textContent = "Price is required.";
                isValid = false;
            }
            if (parseFloat(price.value.trim()) < 0) {
                priceError.textContent = "Price cannot be negative";
                isValid = false;
            }
            if (category.value === "") {
                categoryError.textContent = "Select a category.";
                isValid = false;
            }
            if (condition.value === "") {
                conditionError.textContent = "Select a condition.";
                isValid = false;
            }
            if (image.files.length === 0) {
                imageError.textContent = "Upload an image.";
                isValid = false;
            }
            if (description.value.trim() === "") {
                descriptionError.textContent = "Provide an item description.";
                isValid = false;
            }
            return isValid;
        }

        document.getElementById('image').addEventListener('change', function(event) {
            const fileInput = event.target;
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImage = document.getElementById('imagePreview');

            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                previewImage.src = '';
            }
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