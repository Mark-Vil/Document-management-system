$(document).ready(function () {
  // Get URL parameters first
  var searchTerm = getUrlParameter("search");
  var collegeCode = getUrlParameter("college_code");
  var departmentName = getUrlParameter("department_name");
  var year = getUrlParameter("year");

  // Set initial values
  $("#search-input").val(searchTerm);
  $("#college-select").val(collegeCode);

  // Initialize department select with "All Departments"
  var departmentSelect = $("#department-select");
  departmentSelect.html('<option value="">All Departments</option>');

  // Load departments if college is selected
  if (collegeCode) {
    $.ajax({
        url: "../php-functions/select-college-department.php",
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
            // Set department value after options are loaded
            if (departmentName) {
                $("#department-select").val(departmentName);
            }
        }
    });
}

$("#search-input").on("input", function() {
    var currentSearchTerm = $(this).val().trim();
    // Keep existing filter values
    var collegeCode = $("#college-select").val();
    var departmentName = $("#department-select").val();
    var year = $("#year-select").val();
    
    // Perform search with current filters
    performSearch(currentSearchTerm, collegeCode, departmentName, year);
});



  $("#college-select").on("change", function () {
    var collegeCode = $(this).val();
    $.ajax({
      url: "../php-functions/select-college-department.php",
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
           // Perform search with current search term
           performSearch(currentSearchTerm, collegeCode, "", $("#year-select").val());
      },
    });
  });

  $("#department-select").on("change", function () {
    var currentSearchTerm = $("#search-input").val().trim();
    var collegeCode = $("#college-select").val();
    var departmentName = $(this).val();
    var year = $("#year-select").val();
    
    performSearch(currentSearchTerm, collegeCode, departmentName, year);
});

$("#year-select").on("change", function () {
    var currentSearchTerm = $("#search-input").val().trim();
    var collegeCode = $("#college-select").val();
    var departmentName = $("#department-select").val();
    var year = $(this).val();
    
    performSearch(currentSearchTerm, collegeCode, departmentName, year);
});

  // Modify fetchYears to set year value after loading
  function fetchYears() {
    $.ajax({
      url: "../php-functions/select-year.php",
      method: "GET",
      dataType: "json",
      success: function (data) {
        var yearSelect = $("#year-select");
        yearSelect.empty();
        yearSelect.append(new Option("All Years", ""));
        data.sort((a, b) => b - a)
            .forEach(function (year) {
                yearSelect.append(new Option(year, year));
            });
        $("#year-select").val(year);
        
        // Perform search after loading years
        var searchTerm = $("#search-input").val().trim();
        performSearch(
            searchTerm, 
            $("#college-select").val(),
            $("#department-select").val(),
            year
        );
    }
    });
  }



  fetchYears();

  // Function to get URL parameters

  function getUrlParameter(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");

    var results = regex.exec(location.search);

    return results === null
      ? ""
      : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  // Set the form fields with the URL parameters

  $("#search-input").val(searchTerm);

  $("#college-select").val(collegeCode);

  $("#department-select").val(departmentName);

  $("#year-select").val(year);

  // Perform search if search term is present
  if (searchTerm) {
    performSearch(searchTerm, collegeCode, departmentName, year);
  }

  // Handle input event for live search

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Improve highlight function
function highlightText(text, searchTerm) {
    if (!text || !searchTerm) return text;
    
    // Normalize text for matching
    const normalizedText = text.toLowerCase();
    const normalizedSearch = searchTerm.toLowerCase();
    
    // Create word boundary aware regex
    const searchWords = normalizedSearch.split(/\s+/).map(word => 
        word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    );
    
    const regex = new RegExp(`(${searchWords.join('|')})`, 'gi');
    
    return text.replace(regex, '<span style="background-color: yellow;">$1</span>');
}


  // Add extractMatchingContext function
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

  function performSearch(searchTerm, collegeCode, departmentName, year) {
    var resultsContainer = $(".latest");

    $.ajax({
      url: "../php-functions/search.php",

      method: "GET",

      data: {
        search: searchTerm,

        college_code: collegeCode,

        department_name: departmentName,

        year: year,
      },

      dataType: "json",

      success: function (data) {
        resultsContainer.empty();

        if (data.length > 0) {
         // Update card generation code
data.forEach(function (article) {
    var abstractContext = extractMatchingContext(article.abstract, searchTerm);
    
    var highlightedTitle = highlightText(article.research_title, searchTerm);
    var highlightedAuthor = highlightText(article.author, searchTerm);
    var highlightedCoAuthors = highlightText(article.co_authors, searchTerm);
    var highlightedAbstract = highlightText(abstractContext, searchTerm);
    var highlightedKeywords = highlightText(article.keywords, searchTerm);

    // Format date
    var date = new Date(article.dateofsubmission);
    var formattedDate = date.toLocaleString("en-US", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
    var highlightedDate = highlightText(formattedDate, searchTerm);

    var encryptedId = encrypt(article.id);

    var resultItem = `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card latest-card" style="height: 350px;" data-archive-id="${encryptedId}" onclick="window.location.href='overview.php?id=${encryptedId}'">
                <div class="card-body" style="overflow-y: scroll; scrollbar-width: none; -ms-overflow-style: none; &::-webkit-scrollbar { display: none; }">
                    <a href="#" style="text-decoration: none;">
                        <h5 class="research-title text-center">${highlightedTitle}</h5>
                    </a>
                    
                    <div class="info-section">
                        <p class="card-text mb-2">
                            <strong>Author:</strong> 
                            <span>${highlightedAuthor}</span>
                        </p>
                        <p class="card-text mb-2">
                            <strong>Co-authors:</strong> 
                            <span>${highlightedCoAuthors}</span>
                        </p>
                    </div>
                    
                    <p class="card-text abstract-text">
                        <strong>Abstract:</strong> 
                        <span>${highlightedAbstract}</span>
                    </p>
                   
                    <a href="#" class="toggle-abstract" data-expanded="false">Read more...</a>
                    
                    <hr style="margin: 1rem 0; opacity: 0.1;">
                    <p class="card-text mb-2">
                        <strong>Keywords:</strong> 
                        <span>${highlightedKeywords}</span>
                    </p>
                    <p class="card-text mb-2">
                        <strong>Date:</strong> 
                        <span>${highlightedDate}</span>
                    </p>
                </div>
            </div>
        </div>
    `;
    resultsContainer.append(resultItem);
});
        } else {
          resultsContainer.append(
            "<p class='text-center'>No results found</p>"
          );
        }
      },

      error: function (xhr, status, error) {
        console.error("AJAX Error: " + status + error);
      },
    });
  }


  function encrypt(data) {
    return btoa(data); // Base64 encode
  }


  // Initial fetch

//   $(document).ready(function () {
//     fetchArchives();
//   });

  // Initial fetch on page load

//   fetchArchives();

  $(document).ready(function () {});

  // Event delegation to handle click on dynamically added elements

  $(document).on("click", ".search-result-item", function () {
    var encryptedId = $(this).data("id");

    window.location.href = "overview.php?id=" + encryptedId;
  });
});
