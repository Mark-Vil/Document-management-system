$(document).ready(function () {function extractMatchingContext(text, searchTerm, contextLength = 30) {
    if (!text) return '';
    const matches = [...text.matchAll(new RegExp(searchTerm, 'gi'))];
    if (!matches.length) return text.substring(0, 100) + '...';

    let contexts = [];
    matches.forEach(match => {
        const start = Math.max(0, match.index - contextLength);
        const end = Math.min(text.length, match.index + match.length + contextLength);
        contexts.push({
            text: text.substring(start, end),
            index: start
        });
    });

    // Merge overlapping contexts
    contexts = contexts.sort((a, b) => a.index - b.index);
    let mergedContexts = [];
    let current = contexts[0];

    for (let i = 1; i < contexts.length; i++) {
        if (contexts[i].index <= current.text.length + current.index) {
            current.text = text.substring(current.index, contexts[i].index + contexts[i].text.length);
        } else {
            mergedContexts.push(current);
            current = contexts[i];
        }
    }
    mergedContexts.push(current);

    return mergedContexts.map(context => {
        return (context.index > 0 ? '...' : '') + 
               context.text + 
               (context.index + context.text.length < text.length ? '...' : '');
    }).join(' ');
}


$("#search-input").on("input", function () {
    var searchTerm = $(this).val();
    var resultsContainer = $("#search-results");
    // Get filter values
    var collegeCode = decryptedCollegeCode;

    if (searchTerm.length > 0) {
        $.ajax({
            url: "php-functions/search-college.php",
            method: "GET",
            data: { 
                search: searchTerm,
                college_code: collegeCode
            },
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
                        var highlightedauthor = highlightText(
                            article.author,
                            searchTerm
                        );
                        
                        // Extract and highlight matching context from abstract
                        var abstractContext = extractMatchingContext(article.abstract, searchTerm);
                        var highlightedabstract = highlightText(abstractContext, searchTerm);

                        // Format date to 12-hour format
                        var date = new Date(article.dateofsubmission);
                        var formattedDate = date.toLocaleString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit', // Changed from '12' to '2-digit'
                        minute: '2-digit',
                        hour12: true
                        });
                        var highlighteddate = highlightText(formattedDate, searchTerm);
                        
                        var encryptedId = encrypt(article.id);
                
                        var resultItem = `
                            <div class="search-result-item"data-id="${encryptedId}">
                                <h5>${highlightedTitle}</h5>
                                <p><strong>Author:</strong> ${highlightedauthor}</p>
                                <p><strong>Abstract:</strong> ${highlightedabstract}</p>
                                <p><strong>Date:</strong> ${highlighteddate}</p>
                                <p><strong>Keywords:</strong> ${highlightedKeywords}</p>
                            </div>
                        `;
                        resultsContainer.append(resultItem);
                    });

                    // Override height based on results
                    resultsContainer.css({
                        "max-height": data.length > 3 ? "400px" : "300px",
                        "transition": "max-height 0.3s ease-in-out"
                    });
                    resultsContainer.addClass("search-results-border");
                } else {

                    resultsContainer.append("<p>No results found</p>");
                    resultsContainer.css("max-height", "150px");

                    // Remove border color class
                    resultsContainer.removeClass("search-results-border");
                }
            },
        });
    } else {
        resultsContainer.empty();
        resultsContainer.css("max-height", "150px");

        // Remove border color class
        resultsContainer.removeClass("search-results-border");
    }
});

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

  $(document).on("click", ".search-result-item", function () {
    var encryptedId = $(this).data("id");
    window.location.href = "overview.php?id=" + encryptedId;
  });
});