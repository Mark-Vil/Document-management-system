$(document).ready(function() {
    $('.card').on('click', function() {
        var archiveId = $(this).data('archive-id');
        
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
});



$(document).ready(function() {
    // Get the current research ID from the data attribute
    var archiveId = $('.col-md-4').data('archive-id');
    console.log("Archive ID:", archiveId); // Log the ID for debugging

    // Send an AJAX request to fetch related research documents
    $.ajax({
        url: 'php-functions/recommend.php',
        method: 'GET',
        data: { id: archiveId },
        dataType: 'json',
        success: function(response) {
            console.log("AJAX Success:", response); // Log the response for debugging

            // Clear the existing content
            $('.recommendations').empty();
            
            // Check if there are related documents
            if (response.length > 0) {
                // Iterate through the related research documents and create cards
                $.each(response, function(index, research) {
                    // Encrypt the ID if necessary, using a placeholder function for encryption
                    var encryptedId = encrypt(research.id);

                    var card = `
                      <div class="col-12 mb-3">
    <div class="card document-card" 
         style="max-height: 250px; overflow: hidden; transition: all 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'" 
         onmouseout="this.style.transform='none'; this.style.boxShadow='none'"
         onclick="window.location.href='overview.php?id=${encryptedId}'">
        <div class="card-body" style="cursor: pointer;">
            <h5 class="text-center recom-doc-research-title">${research.research_title}</h5>
            <p class="card-text recom-abstract-text">
                <strong>Abstract:</strong> 
                <span>${research.abstract}</span>
            </p>
        </div>
    </div>
</div>
                    `;
                    $('.recommendations').append(card);
                });
            } else {
                $('.recommendations').append('<p>No related research found.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching related research:', error);
            console.log("AJAX Error:", xhr); // Log the xhr object for debugging
            $('.recommendations').append('<p>Error fetching related research.</p>');
        }
    });
});

// Placeholder function for encrypting the ID; replace with actual implementation
function encrypt(id) {
    return btoa(id); // Base64 encode
  }



document.addEventListener("DOMContentLoaded", function() {
    // Check if the document is not owned by the current user
    if (userID != archiveUserID) {
        setTimeout(function() {
            // Log the view after 10 seconds
            $.ajax({
                url: 'php-functions/log-views.php',
                type: 'POST',
                data: {
                    userID: userID,
                    researchID: researchID,
                    collegeCode: collegeCode,
                    departmentCode: departmentCode,
                    duration: 10 // Duration in seconds
                },
                success: function(response) {
                    console.log('Views logged successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error logging view:', error);
                }
            });
        }, 10000);
    }

    $('#downloadButton').on('click', function(event) {
        event.stopPropagation();
        $.ajax({
            url: 'php-functions/log-download.php',
            type: 'POST',
            data: {
                userID: userID,
                researchID: researchID,
                collegeCode: collegeCode,
                departmentCode: departmentCode
            },
            success: function(response) {
                console.log('Download logged successfully:', response);
                // Proceed with the download
                window.location.href = 'php-functions/download.php?file=' + filePath;
            },
            error: function(xhr, status, error) {
                console.error('Error logging download:', error);
            }
        });
    });

    // Add this after download button click handler
$('.cite-btn').on('click', function(event) {
    event.stopPropagation();
    
    // Log citation before showing modal
    $.ajax({
        url: 'php-functions/log-citation.php',
        type: 'POST',
        data: {
            userID: userID,
            researchID: researchID,
            collegeCode: collegeCode,
            departmentCode: departmentCode
        },
        success: function(response) {
            console.log('Citation logged successfully:', response);
            // Show citation modal after logging
            showCitationModal(author, coAuthors, researchTitle, dateAccepted);
        },
        error: function(xhr, status, error) {
            console.error('Error logging citation:', error);
            // Still show modal even if logging fails
            showCitationModal(author, coAuthors, researchTitle, dateAccepted);
        }
    });
});

});


