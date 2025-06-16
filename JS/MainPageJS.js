const keyName = "selectedCategories_user_<?php echo hash('sha256', $_SESSION['user_id']); ?>";
const lastUserKey = "lastUserHash";
const currentUserKey = "<?php echo hash('sha256', $_SESSION['user_id']); ?>";

// Optional: Clear data if a different user logs in
const lastUserId = localStorage.getItem("lastUserHash");
if (lastUserId && lastUserId !== currentUserKey) {
    localStorage.removeItem(`selectedCategories_user_${lastUserId}`);
    localStorage.removeItem("itemOffset");
    localStorage.removeItem("scrollPosition");
}
localStorage.setItem("lastUserHash", currentUserKey);


let offset = 0;
const limit = 100;
let loading = false;

function loadItems(reset = false, callback = null) {
    console.log("loadItems called with reset =", reset);
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
    console.log("loadItems AJAX success, offset now:", offset);
}

function updateItems() {
    let selectedCategories = [];
    $('input[name="categories[]"]').each(function () {
        if (this.checked) {
            selectedCategories.push(this.value);
        }
    });

    // Store in localStorage under per-user key
    localStorage.setItem(keyName, JSON.stringify(selectedCategories));

    loadItems(true);
}

$(document).ready(function () {
    // Restore selected categories
    let storedCategories = localStorage.getItem(keyName);
    if (storedCategories) {
        let categories = JSON.parse(storedCategories);
        $('input[name="categories[]"]').each(function () {
            if (categories.includes(this.value)) {
                this.checked = true;
            }
        });
    }

    // Get scroll/offset data
    let storedOffset = parseInt(localStorage.getItem("itemOffset") || "0", 10);
    let scrollPos = parseInt(localStorage.getItem("scrollPosition") || "0", 10);

    function restoreScroll() {
        if (!isNaN(scrollPos)) {
            $(window).scrollTop(scrollPos);
        }
        // Clean up
        localStorage.removeItem("scrollPosition");
        localStorage.removeItem("itemOffset");
    }

    // Prefetch items to reach saved offset
    function loadInitialItems(count, doneCallback = null) {
        if (count <= 0) {
            loadItems(false, doneCallback);
            return;
        }

        loadItems(false, function () {
            count -= limit;
            setTimeout(function () {
                loadInitialItems(count, doneCallback);
            }, 0.1); // Minimal delay to allow DOM processing
        });
    }

    if (storedOffset > 0) {
        loadInitialItems(storedOffset, restoreScroll);
    } else {
        loadItems(false, restoreScroll);
    }

    // Infinite scroll
    $(window).on("scroll", function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadItems();
        }
    });

    function handleSearchClick() {
        console.log("Inline onclick triggered");
        loadItems(true);
    }


    // $("#searchBtn").on("click", function () {
    //     console.log("Search button clicked - before loadItems");
    //     loadItems(true);
    // });



    // let debounceTimer;
    // $("#searchBar").on("input", function () {
    //     clearTimeout(debounceTimer);
    //     debounceTimer = setTimeout(function () {
    //         loadItems(true);
    //     }, 300);
    // });
});

// Save scroll and offset when clicking into item
$(document).on("click", ".item-link", function () {
    localStorage.setItem("scrollPosition", window.scrollY);
    localStorage.setItem("itemOffset", offset);
});const keyName = "selectedCategories_user_<?php echo hash('sha256', $_SESSION['user_id']); ?>";
const lastUserKey = "lastUserHash";
const currentUserKey = "<?php echo hash('sha256', $_SESSION['user_id']); ?>";

// Optional: Clear data if a different user logs in
const lastUserId = localStorage.getItem("lastUserHash");
if (lastUserId && lastUserId !== currentUserKey) {
    localStorage.removeItem(`selectedCategories_user_${lastUserId}`);
    localStorage.removeItem("itemOffset");
    localStorage.removeItem("scrollPosition");
}
localStorage.setItem("lastUserHash", currentUserKey);


let offset = 0;
const limit = 100;
let loading = false;

function loadItems(reset = false, callback = null) {
    console.log("loadItems called with reset =", reset);
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
    console.log("loadItems AJAX success, offset now:", offset);
}

function updateItems() {
    let selectedCategories = [];
    $('input[name="categories[]"]').each(function () {
        if (this.checked) {
            selectedCategories.push(this.value);
        }
    });

    // Store in localStorage under per-user key
    localStorage.setItem(keyName, JSON.stringify(selectedCategories));

    loadItems(true);
}

$(document).ready(function () {
    // Restore selected categories
    let storedCategories = localStorage.getItem(keyName);
    if (storedCategories) {
        let categories = JSON.parse(storedCategories);
        $('input[name="categories[]"]').each(function () {
            if (categories.includes(this.value)) {
                this.checked = true;
            }
        });
    }

    // Get scroll/offset data
    let storedOffset = parseInt(localStorage.getItem("itemOffset") || "0", 10);
    let scrollPos = parseInt(localStorage.getItem("scrollPosition") || "0", 10);

    function restoreScroll() {
        if (!isNaN(scrollPos)) {
            $(window).scrollTop(scrollPos);
        }
        // Clean up
        localStorage.removeItem("scrollPosition");
        localStorage.removeItem("itemOffset");
    }

    // Prefetch items to reach saved offset
    function loadInitialItems(count, doneCallback = null) {
        if (count <= 0) {
            loadItems(false, doneCallback);
            return;
        }

        loadItems(false, function () {
            count -= limit;
            setTimeout(function () {
                loadInitialItems(count, doneCallback);
            }, 0.1); // Minimal delay to allow DOM processing
        });
    }

    if (storedOffset > 0) {
        loadInitialItems(storedOffset, restoreScroll);
    } else {
        loadItems(false, restoreScroll);
    }

    // Infinite scroll
    $(window).on("scroll", function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadItems();
        }
    });

    function handleSearchClick() {
        console.log("Inline onclick triggered");
        loadItems(true);
    }


    // $("#searchBtn").on("click", function () {
    //     console.log("Search button clicked - before loadItems");
    //     loadItems(true);
    // });



    // let debounceTimer;
    // $("#searchBar").on("input", function () {
    //     clearTimeout(debounceTimer);
    //     debounceTimer = setTimeout(function () {
    //         loadItems(true);
    //     }, 300);
    // });
});

// Save scroll and offset when clicking into item
$(document).on("click", ".item-link", function () {
    localStorage.setItem("scrollPosition", window.scrollY);
    localStorage.setItem("itemOffset", offset);
});