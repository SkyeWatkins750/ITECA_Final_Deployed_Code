let offset = 0;
        const limit = 100;
        let loading = false;
        
        function loadUserItems() {
            if (loading) return;
            loading = true;

            const formData = new FormData();
            formData.append("offset", offset);
            formData.append("limit", limit);

            $.ajax({
                url: "../includes/MyListingsPageFormHandler.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-Requested_With": "XMLHttpRequest"
                },
                success: function(response) {
                    if (response.trim() != "") {
                        $("#item-list").append(response);
                        offset += limit;
                    }
                    loading = false;
                },
                error: function() {
                    alert("Failed to load items.");
                    loading = false;
                }
            });
        }

        $(document).ready(function() {
            loadUserItems();

            $(window).on("scroll", function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    loadUserItems();
                }
            });
        });