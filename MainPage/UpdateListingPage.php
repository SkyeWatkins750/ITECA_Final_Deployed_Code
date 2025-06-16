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
    <script src="../JS/UpdateListingPageJS.js"></script>

    <title>Edit Listing</title>
</head>
<body>
<?php
    require_once '../includes/UpdateListingPageGetFormHandler.php'
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
            </div>
        </header>
    </div>

    <!-- Main Content -->
        <div class="main-content">
        <div class="form-container">
            <h2>Edit Product Listing</h2>
            <form action="../includes/UpdateListingPageFormHandler.php" method="POST" enctype="multipart/form-data" onsubmit="return ValidateInputs()" novalidate>
                <!-- Item Name -->
                <label for="itemName">Item Name</label>
                <input type="text" name="itemName" id="itemName" value="<?=htmlspecialchars($item["itemName"]);?>">
                <span class="error-message" id="itemNameError"></span>

                <!-- Price -->
                <label for="price">Price (R)</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="<?=htmlspecialchars($item["price"]);?>">
                <span class="error-message" id="priceError"></span>

                <!-- Category -->
                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="">Select Category</option>
                    <?php $categories = getCategoriesCreateForUpdate();
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
                    <?php $conditions = getConditionsForUpdate();
                        foreach($conditions as $condition):
                    ?>
                        <option value="<?=htmlspecialchars($condition['conditionName']) ?>"><?=htmlspecialchars($condition['conditionName']) ?></option>
                    <?php endforeach;?>                     
                </select>
                <span class="error-message" id="conditionError"></span>

                <!-- Image -->
                <label for="image">Product Image</label>
                <div id="imagePreviewContainer" style="margin-top: 10px; display: block;">
                    <img
                        id="imagePreview"
                        src="../images/<?= htmlspecialchars($item['imagePath']) ?>"
                        alt="Current product Image"
                        style="max-width: 200px; max-height: 200px; border-radius: 8px;"
                    >
                </div>
                <input type="file" name="image" id="image" accept="image/*">
                <span class="error-message" id="imageError"></span>
                
                <!-- Description -->
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5"><?=htmlspecialchars($item["description"]);?></textarea>
                <span class="error-message" id="descriptionError"></span>

                <input type="hidden" name="itemId" value="<?=$itemId?>">


                <button type="submit">Edit Listing</button>
                <button type="button" class="delete-button" onclick="confirmDelete(<?= htmlspecialchars($itemId) ?>)">
                    <i class="bi bi-trash" style="margin-right: 8px;"></i>Remove Listing
                </button>

            </form>
        </div>
    </div>
                     
    <script>
        const selectedCategory = <?= json_encode($item["Category_Name"]);?>;
        const selectedCondition = <?= json_encode($item["Condition_Name"]);?>;
        document.addEventListener('DOMContentLoaded', () => {
            const selectCat = document.getElementById('category');
            selectCat.value = selectedCategory;
            const selectCon = document.getElementById('condition');
            selectCon.value = selectedCondition;
        });

        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "../images/<?= htmlspecialchars($item['imagePath']) ?>";
                imagePreviewContainer.style.display = 'block'; // or 'none' if you want to hide when no file
            }
        });

        function confirmDelete(itemId) {
        if (confirm("Are you sure you want to delete this listing? This action cannot be undone.")) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../includes/RemoveItemFormHandler.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'itemId';
            input.value = itemId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>
</html>