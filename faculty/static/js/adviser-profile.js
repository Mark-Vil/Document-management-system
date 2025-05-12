// UPDATE ADVISER PROFILE 
$(document).ready(function() {
    $('#adviser-profile').on('submit', function(event) {
        event.preventDefault(); 

        var formData = new FormData(this);

        Swal.fire({
            title: 'Updating...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/update-faculty-profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Parse the JSON response
                var jsonResponse = JSON.parse(response);

                if (jsonResponse.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Hide the modal
                            $('#profileModal').modal('hide');
                            // Reload the page
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle any errors
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred: ' + error,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hide the modal
                    }
                });
            }
        });
    });
});
// UPDATE ADVISER PROFILE