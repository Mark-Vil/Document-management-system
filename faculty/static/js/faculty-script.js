$(document).ready(function() {
    // Add new co-author input field
    $('#add-coauthor-btn').click(function() {
        var newInput = $('<input class="form-control mb-2" name="coauthorname[]" type="text" placeholder="Enter Co-Author Full Name">');
        $('#coauthor-container').append(newInput);
    });

     // Add new keyword input field
    $('#add-keywords-btn').click(function() {
        var newkeyInput = $('<input class="form-control mb-2" name="addkeywords[]" type="text" placeholder="Enter Keywords">');
        $('#addkeywords-container').append(newkeyInput);
      });

    // Validate file input
    $('#inputfile').change(function() {
        var file = this.files[0];
        if (file && file.type !== 'application/pdf') {
            Swal.fire({
                title: 'Error!',
                text: 'Only PDF files are allowed',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            this.value = ''; // Clear the input
        }
    });


    // Handle form submission
    $('#submission').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        Swal.fire({
            title: 'Uploading...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: "php-functions/submit-research.php",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Parse the JSON response
                var jsonResponse = JSON.parse(response);

                if (jsonResponse.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: jsonResponse.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // location.reload(); // Reload the page
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: jsonResponse.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred: ' + error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                console.log(xhr.responseText);
            }
        });
    });
});