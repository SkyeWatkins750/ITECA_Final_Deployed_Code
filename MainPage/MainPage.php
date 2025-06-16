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
    <title>MyTrader</title>
    <link rel="stylesheet" href="../CSS/mainPageStyles.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    

</head>
    <body>
        <?php
        require_once '../includes/MainPageFormHandler.php';
        ?>
        
        <!-- Header -->
        <div class="page_header">
            <header>
                <div class="navbar">
                    <div class="burger" onclick="toggleSidebar()"><i class="bi bi-list"></i></div>
                </div>
                <h1 id="HeaderHeading">MyTrader</h1>

                <input id="searchBar" type="text" placeholder="Search...">
                <button id="searchBtn" type="button" onclick="handleSearchClick()"><i class="bi bi-search"></i></button>
                
                
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
        <form action="../includes/MainPageFormHandler.php" method="POST" id="categoryForm"></form>
            <!-- Sidebar -->
            
            <div class="Sidebar" id="sidebar">
                <h2 id="CategoriesHeading">Categories</h2>
                <div class="category-list">
                    <?php foreach($categories as $category): ?>
                        <label class="category-item">
                            <input type="checkbox" name="categories[]" value="<?=htmlspecialchars($category['id'])?>" onchange="updateItems();">
                            <i class="bi <?=htmlspecialchars($category['icon_class'])?>"></i> <?=htmlspecialchars($category['categoryName'])?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <h2 id="ActionsHeading">Actions</h2>
                
                <div class="sidebar-actions">
                    <a href="CreateListingPage.php" class="create-listing-button">
                    <i class="bi bi-plus-lg" style="margin-right: 8px;"></i> Create Listing
                    </a>
                    <a href="MyListingsPage.php" class="my-listings-button">
                        <i class="bi bi-card-list" style="margin-right: 8px;"></i> My Listings
                    </a>
                    <?php if (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] === 'admin'): ?>
                        <a href="../Admin/ViewUsersPage.php" class="my-listings-button">
                            <i class="bi bi-people-fill" style="margin-right: 8px;"></i> Manage Users
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <!-- Listing Area -->
        <div class="main-content">
            <div class="card-grid" id="item-list">
                <?php 
                // echo getItemsForCards();
                ?>
            </div>
        </div>

        <!-- <script src="../JS/MainPageJS.js"></script> -->
        <script>
        const currentUserKey = "<?php echo hash('sha256', $_SESSION['user_id']); ?>";
            const lastUserKey = "lastUserHash";

            // Clear old user data if user changed
            const lastUserId = localStorage.getItem(lastUserKey);
            if (lastUserId && lastUserId !== currentUserKey) {
                localStorage.removeItem(`selectedCategories_user_${lastUserId}`);
                localStorage.removeItem("itemOffset");
                localStorage.removeItem("scrollPosition");
                localStorage.removeItem("itemHTML");
            }
            localStorage.setItem(lastUserKey, currentUserKey);

            const keyName = `selectedCategories_user_${currentUserKey}`;

            let offset = 0;
            const limit = 100;
            let loading = false;

            function loadItems(reset = false, callback = null) {
                if (loading) return;
                loading = true;

                if (reset) {
                    offset = 0;
                    $("#item-list").empty();
                }

                let formData = new FormData();

                let storedCategories = localStorage.getItem(keyName);
                let selectedCategories = storedCategories ? JSON.parse(storedCategories) : [];

                if (selectedCategories.length > 0) {
                    selectedCategories.forEach(cat => formData.append('categories[]', cat));
                } else {
                    formData.append('categories[]', '');
                }

                let searchTerm = $("#searchBar").val();
                formData.append("search", searchTerm);
                formData.append('offset', offset);
                formData.append('limit', limit);

                $.ajax({
                    url: "../includes/MainPageFormHandler.php",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.trim() !== "") {
                            $("#item-list").append(response);
                            offset += limit;

                            // Save updated HTML to localStorage as you load more items
                            // localStorage.setItem("itemHTML", document.getElementById("item-list").innerHTML);
                            // localStorage.setItem("itemOffset", offset);
                        }

                        loading = false;

                        if (typeof callback === "function") {
                            callback();
                        }
                    },
                    error: function() {
                        alert("Error loading items.");
                        loading = false;
                    }
                });
            }

            function updateItems() {
                let selectedCategories = [];
                $('input[name="categories[]"]').each(function () {
                    if (this.checked) {
                        selectedCategories.push(this.value);
                    }
                });

                // Store selected categories per user
                localStorage.setItem(keyName, JSON.stringify(selectedCategories));

                loadItems(true);
            }

            function handleSearchClick() {
                loadItems(true);
            }

            $(document).ready(function () {
                // Restore selected categories
                let storedCategories = localStorage.getItem(keyName);
                if (storedCategories) {
                    let categories = JSON.parse(storedCategories);
                    $('input[name="categories[]"]').each(function () {
                        this.checked = categories.includes(this.value);
                    });
                }

                // Try to restore HTML & scroll position
                // let savedHTML = localStorage.getItem("itemHTML");
                // let savedScroll = parseInt(localStorage.getItem("scrollPosition") || "0", 10);
                // let savedOffset = parseInt(localStorage.getItem("itemOffset") || "0", 10);

                // if (savedHTML) {
                //     // $("#item-list").html(savedHTML);
                //     setTimeout(() => {
                //         $(window).scrollTop(savedScroll);
                //     }, 0);
                //     // Skip loading more items now, content restored from localStorage
                //     offset = savedOffset || 0;
                // } else {
                //     // No saved HTML: load first page and scroll after
                //     loadItems(false, () => {
                //         if (!isNaN(savedScroll)) {
                //             $(window).scrollTop(savedScroll);
                //         }
                //     });
                // }

                const savedState = history.state;

                if (savedState) {
                    // Restore search
                    $("#searchBar").val(savedState.search || "");

                    // Restore category checkboxes
                    $('input[name="categories[]"]').each(function () {
                        this.checked = savedState.selectedCategories?.includes(this.value);
                    });

                    // Load items up to previous offset
                    offset = 0;
                    const targetOffset = savedState.offset || 0;
                    function loadUntilOffset() {
                        if (offset < targetOffset) {
                            loadItems(false, loadUntilOffset);
                        } else {
                            $(window).scrollTop(savedState.scrollY || 0);
                        }
                    }
                    loadUntilOffset();
                } else {
                    // First visit
                    loadItems();
                }


                // Infinite scroll to load more as user scrolls near bottom
                $(window).on("scroll", function () {
                    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1000) {
                        loadItems();
                    }
                    // Save scroll position live (optional)
                    // localStorage.setItem("scrollPosition", window.scrollY);
                });
            });

            // Save scroll, offset, HTML and categories when user clicks an item
            // $(document).on("click", ".item-link", function () {
            //     localStorage.setItem("scrollPosition", window.scrollY);
            //     localStorage.setItem("itemOffset", offset);
            //     localStorage.setItem("itemHTML", document.getElementById("item-list").innerHTML);

            //     // Also save current selected categories
            //     let selectedCategories = [];
            //     $('input[name="categories[]"]').each(function () {
            //         if (this.checked) {
            //             selectedCategories.push(this.value);
            //         }
            //     });
            //     localStorage.setItem(keyName, JSON.stringify(selectedCategories));
            // });

            $(document).on("click", ".item-link", function () {
                const state = {
                    scrollY: window.scrollY,
                    offset: offset,
                    search: $("#searchBar").val(),
                    selectedCategories: $('input[name="categories[]"]:checked').map(function() {
                        return this.value;
                    }).get()
                };
                history.replaceState(state, document.title, window.location.href);
            });


            function updateCartCount() {
                fetch('../includes/getCartCount.php')
                .then(res => res.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    cartCount.textContent = data.count;
                })
                .catch(err => {
                    console.error("Failed to fetch cart count", err);
                });
            }

            updateCartCount();

            // Settings dropdown toggle logic
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

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('show');
            }
        </script>

    </body>
</html>