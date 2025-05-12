$(document).ready(function() {
    $("#search-input").on("input", function () {
        var searchTerm = $(this).val();
        var resultsContainer = $("#search-results");
    
        if (searchTerm.length > 0) {
            // Start searching after 1 character
            $.ajax({
                url: "php-functions/search.php",
                method: "GET",
                data: { search: searchTerm },
                success: function (data) {
                    resultsContainer.empty();
                    if (data.length > 0) {
                        data.forEach(function (article) {
                            var highlightedTitle = highlightText(
                                article.research_title,
                                searchTerm
                            );
                            var highlightedKeywords = highlightText(
                                article.keywords,
                                searchTerm
                            );
                            var encryptedId = encrypt(article.id);
    
                            var resultItem = `
                                <div class="search-result-item" data-id="${encryptedId}">
                                    <h5>${highlightedTitle}</h5>
                                    <p><strong>Keywords:</strong> ${highlightedKeywords}</p>
                                </div>
                            `;
                            resultsContainer.append(resultItem);
                        });
    
                        // Adjust the height of the results container
                        var itemHeight = $(".search-result-item").outerHeight();
                        var containerHeight = itemHeight * data.length;
                        resultsContainer.css("max-height", containerHeight + "px");
    
                        // Add border color class
                        resultsContainer.addClass("search-results-border");
                    } else {
                        resultsContainer.append("<p>No results found</p>");
                        resultsContainer.css("max-height", "150px"); // Default height if no results
    
                        // Remove border color class
                        resultsContainer.removeClass("search-results-border");
                    }
                },
            });
        } else {
            resultsContainer.empty();
            resultsContainer.css("max-height", "150px"); // Default height if input is empty
    
            // Remove border color class
            resultsContainer.removeClass("search-results-border");
        }
    
        function highlightText(text, searchTerm) {
            var regex = new RegExp(
                "\\b(" + searchTerm.split(" ").join("|") + ")\\b",
                "gi"
            );
            return text.replace(regex, '<span class="highlight">$1</span>');
        }
    
        function encrypt(data) {
            return btoa(data); // Base64 encode
        }
    
        // Event delegation to handle click on dynamically added elements
        $(document).on("click", ".search-result-item", function () {
            var encryptedId = $(this).data("id");
            window.location.href = "overview.php?id=" + encryptedId;
        });
    });
});