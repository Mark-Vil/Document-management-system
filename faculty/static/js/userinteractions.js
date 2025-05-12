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