$(document).ready(function () {
  // Fetch and populate the year select options on page load
  fetchYears();

  $("#college-select").on("change", function () {
    var collegeCode = $(this).val();
    $.ajax({
        url: "php-functions/select-college-department.php",
        method: "GET",
        data: { college_code: collegeCode },
        dataType: "json",
        success: function (data) {
            var departmentSelect = $("#department-select");
            departmentSelect.empty();
            // Add "All Departments" option first
            departmentSelect.append(new Option("All Departments", ""));
            // Add rest of departments
            data.forEach(function (department) {
                departmentSelect.append(
                    new Option(department.department_name, department.department_name)
                );
            });
        },
    });
    fetchArchives();
  });

  $("#department-select").on("change", function () {
    fetchArchives();
  });

  $("#year-select").on("change", function () {
    fetchArchives();
  });



  function fetchArchives(page = 1) {
    var collegeCode = $("#college-select").val();
    var departmentName = $("#department-select").val();
    var year = $("#year-select").val();

    $.ajax({
        url: "php-functions/fetch-archive.php",
        method: "GET",
        data: {
            college_code: collegeCode,
            department_name: departmentName,
            year: year,
            page: page 
        },
        success: function (data) {
            var response = JSON.parse(data);
            var archives = response.archives;
            var total = response.total;
            $(".latest").html('');

            if (archives.length > 0) {
                archives.forEach(function (archive) {
                    var row = `
                  <div class="col-lg-4 col-md-6 mb-4">
    <div class="card latest-card" style="height: 400px;"  data-archive-id="${archive.encrypted_id}" onclick="window.location.href='overview.php?id=${encodeURIComponent(archive.encrypted_id)}'">
        <div class="card-body">
            <a href="path/to/pdf/${archive.research_title}.pdf" target="_blank" style="text-decoration: none;">
                <h5 class="research-title">${archive.research_title}</h5>
            </a>
            <div class="info-section">
                <p class="card-text mb-2">
                    <strong>Author:</strong> 
                    <span>${archive.author}</span>
                </p>
                <p class="card-text mb-2">
                    <strong>Co-authors:</strong> 
                    <span>${archive.co_authors}</span>
                </p>
            </div>
            <p class="card-text abstract-text">
                <strong>Abstract:</strong> 
                <span>${archive.abstract}</span>
            </p>
            <a href="#" class="toggle-abstract" data-expanded="false">Read more...</a>
            <hr style="margin: 1rem 0; opacity: 0.1;">
            <div class="meta-info">
                <p class="card-text mb-1">
                    <strong>Date:</strong> 
                    <span>${archive.date_accepted}</span>
                </p>
                <p class="card-text mb-0">
                    <strong>From:</strong> 
                    <span>${archive.college_name}</span>
                </p>
            </div>
        </div>
    </div>
</div>`;
                    $(".latest").append(row);
                });

                // Pagination
                var totalPages = Math.ceil(total / 6); // Adjusted for 1 card per page
                var pagination = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
                for (var i = 1; i <= totalPages; i++) {
                    pagination += `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                pagination += '</ul></nav>';
                $(".latest").append(pagination);

                // Add click event for pagination links
                $(".pagination .page-link").on("click", function(e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    fetchArchives(page);
                });
            } else {
                $(".latest").append("<div class='col-12 text-center'>No results found</div>");
            }

            // Set up the click event handler for the cards
            $('.card').on('click', function() {
                var archiveId = $(this).data('archive-id');

                // Log the archiveId to the console for debugging
                console.log("Archive ID:", archiveId);
                
                if (userId) {
                    // Send AJAX request to insert interaction
                    $.ajax({
                        url: 'php-functions/userinteractions.php',
                        method: 'POST',
                        data: {
                            user_id: userId,
                            archive_id: archiveId
                        },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status === 'success') {
                                // Redirect to overview page after successful insertion
                                window.location.href = 'overview.php?id=' + encodeURIComponent(archiveId);
                            } else {
                                alert('Error: ' + res.message);
                            }
                        },
                        error: function() {
                            alert('Failed to insert interaction.');
                        }
                    });
                } else {
                    // Redirect to overview page directly if no user ID in session
                    window.location.href = 'overview.php?id=' + encodeURIComponent(archiveId);
                }
            });
        },
    });
}

// Initial fetch
$(document).ready(function() {
    fetchArchives();
});

  function fetchYears() {
    $.ajax({
        url: "php-functions/select-year.php",
        method: "GET",
        dataType: "json",
        success: function (data) {
            var yearSelect = $("#year-select");
            yearSelect.empty();
            
            // Add default options
            yearSelect.append(new Option("All Years", ""));
            
            // Sort years in descending order and append
            data.sort((a, b) => b - a).forEach(function (year) {
                yearSelect.append(new Option(year, year));
            });
        }
    });
}

  // Initial fetch on page load
  fetchArchives();

  $("#search-form").on("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var searchTerm = $("#search-input").val();
    // var collegeCode = $("#college-select").val();
    // var departmentName = $("#department-select").val();
    // var year = $("#year-select").val();

    // Redirect to search-document.php with the search term and filters
    var queryParams = $.param({
        search: searchTerm,
        // college_code: collegeCode,
        // department_name: departmentName,
        // year: year
    });

    window.location.href = "search.php?" + queryParams;
});

 $("#search-form").on("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    var searchTerm = $("#search-input").val();
    var collegeCode = $("#college-select").val();
    var departmentName = $("#department-select").val();
    var year = $("#year-select").val();

    // Redirect to search-document.php with the search term and filters
    var queryParams = $.param({
        search: searchTerm,
        college_code: collegeCode,
        department_name: departmentName,
        year: year
    });

    window.location.href = "search.php?" + queryParams;
});

function extractMatchingContext(text, searchTerm, contextLength = 30) {
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


 let searchTimeout;

  $("#search-input").on("input", function (e) {
    var searchTerm = $(this).val();
    var resultsContainer = $("#search-results");
    // Get filter values
    var collegeCode = $("#college-select").val() || '';
    var departmentName = $("#department-select").val() || '';
    var year = $("#year-select").val() || '';

    // Clear previous timeout
    clearTimeout(searchTimeout);

    // Handle empty input or backspace/delete
    if (searchTerm.length === 0 || e.key === "Backspace" || e.key === "Delete") {
        resultsContainer.empty();
        resultsContainer.css("max-height", "150px");
        resultsContainer.removeClass("search-results-border");
        return;
    }

    searchTimeout = setTimeout(function() {
    if (searchTerm.length > 0) {
           // Show spinner
          resultsContainer.html('<div class="spinner-container"><div class="spinner"></div></div>');

        $.ajax({
            url: "php-functions/search.php",
            method: "GET",
            data: { 
                search: searchTerm,
                college_code: collegeCode,
                department_name: departmentName,
                year: year
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

                    // Add delay before highlighting
                    setTimeout(function() {
                        $('.search-result-item').each(function() {
                            var item = $(this);
                            item.find('h5').html(highlightText(item.find('h5').text(), searchTerm));
                            item.find('p').each(function() {
                                var strong = $(this).find('strong').clone();
                                var text = $(this).text().replace($(this).find('strong').text(), '');
                                $(this).html(strong).append(highlightText(text, searchTerm));
                            });
                        });
                    }, 2000);

                    // Override height based on results
                    resultsContainer.css({
                        "max-height": data.length > 3 ? "400px" : "300px",
                        "transition": "max-height 0.3s ease-in-out"
                    });
                    resultsContainer.addClass("search-results-border");
                } else {
                    resultsContainer.append("<p class='no-results-message'>No results found</p>");
                    resultsContainer.css("max-height", "150px");
                    resultsContainer.removeClass("search-results-border");
                }
            }
        });
    } else {
        resultsContainer.empty();
        resultsContainer.css("max-height", "150px");
        resultsContainer.removeClass("search-results-border");
    }
}, 300);
});

// Add blur event to handle clicking outside
$(document).on("click", function(e) {
    if (!$(e.target).closest("#search-input, #search-results").length) {
        $("#search-results").empty();
        $("#search-results").css("max-height", "150px");
        $("#search-results").removeClass("search-results-border");
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

  // Event delegation to handle click on dynamically added elements
  $(document).on("click", ".search-result-item", function () {
    var encryptedId = $(this).data("id");
    window.location.href = "overview.php?id=" + encryptedId;
  });
});


$(document).ready(function() {
    fetchRecommendations();
    function fetchRecommendations() {
        $.ajax({
            url: 'php-functions/fetch-latest-research-id.php',
            method: 'GET',
            success: function(response) {
                console.log("Fetch Latest Research ID Response:", response); // Log the response to the console for debugging
                var data = JSON.parse(response);
                if (data.success) {
                    var researchId = data.research_id;
                    getRecommendations(researchId);
                } else {
                    console.error(data.message);
                    $('.recent-similar').hide(); // Hide the column if no research ID is found
                }
            },
            error: function() {
                $('.recent-similar').hide(); // Hide the column if there is an error
            }
        });
    }

    $(document).ready(function() {
        // Load recently visited
        $.ajax({
            url: 'php-functions/recent.php',
            type: 'GET',
            success: function(response) {
                if (response.recentArchives && response.recentArchives.length > 0) {
                    let html = `
                        <h1 class="ml-3 mb-4">Recently Visited</h1>
                        <div class="row recent">`;
                    
                    response.recentArchives.forEach(archive => {
                         html += `
                        <div class="col-lg-6 col-md-6">
                            <div class="card recent-card" data-archive-id="${archive.encrypted_id}" 
                                 onclick="window.location.href='overview.php?id=${archive.encrypted_id}'">
                                <div class="card-body">
    <h5 class="research-title text-center">${archive.research_title}</h5>
    <div class="info-section">
        <p class="card-text">
            <strong>Author:</strong> 
            <span>${archive.author}</span>
        </p>
        <p class="card-text">
            <strong>Co-authors:</strong> 
            <span>${archive.co_authors}</span>
        </p>
    </div>
    <p class="card-text">
        <strong>Abstract:</strong> 
        <span>${archive.abstract}</span>
    </p>
    <div class="meta-info">
        <p class="card-text">
            <strong>Date:</strong> 
            <span>${archive.date_accepted}</span>
        </p>
        <p class="card-text">
            <strong>From:</strong> 
            <span>${archive.college_name}</span>
        </p>
    </div>
</div>

                            </div>
                        </div>`;
                    });
                    
                    html += '</div>';
                    $('#recentlyVisited').html(html);
                }
            }
        });
    });

    function getRecommendations(researchId) {
        $.ajax({
            url: 'php-functions/explore.php',
            method: 'GET',
            data: { research_id: researchId },
            success: function(response) {
                var recommendations = response;
                var recommendationsContainer = $('.explore');
                recommendationsContainer.empty();
    
                // Get all recently visited article IDs
                var recentIds = [];
                $('.recent-card').each(function() {
                    recentIds.push($(this).data('archive-id'));
                });
    
                // Filter out recommendations that match recent articles
                var filteredRecommendations = recommendations.filter(function(article) {
                    var encryptedId = btoa(article.id); // Base64 encode ID
                    return !recentIds.includes(encryptedId);
                });
    
                if (filteredRecommendations.length > 0) {
                    filteredRecommendations.forEach(function(article) {
                        var encryptedId = btoa(article.id);
                        var card = `
                            <div class="card mb-3" style="cursor: pointer; max-height: 350px; overflow: hidden;" 
                                 onclick="window.location.href='overview.php?id=${encryptedId}'">
                                <div class="card-body">
                                    <h5 class="research-title text-center">
                                        ${article.research_title}
                                    </h5>
                                    <p class="card-text similar-abstract-text">
                                        <strong>Abstract:</strong> 
                                        <span>${article.abstract}</span>
                                    </p>
                                </div>
                            </div>
                        `;
                        recommendationsContainer.append(card);
                    });
                } else {
                    $('.recent-similar').hide();
                }
            },
            error: function() {
                console.error('Failed to fetch recommendations.');
                $('.recent-similar').hide();
            }
        });
    }
   

    // Placeholder function for encrypting the ID; replace with actual implementation
    function encrypt(id) {
        return btoa(id); // Base64 encode
    }
});

