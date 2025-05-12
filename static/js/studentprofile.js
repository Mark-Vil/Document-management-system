$(document).ready(function() {
    $('#student-profile').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        Swal.fire({
            title: 'Updating...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });


        $.ajax({
            url: 'php-functions/update-student-profile.php', // The PHP script that handles the form submission
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Parse the JSON response
                var jsonResponse = JSON.parse(response);

                // Handle success response
                if (jsonResponse.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message
                    }).then(function() {
                        // Close the modal
                        $('#profileModal').modal('hide');
                        // Reload the page
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message
                    });
                }
            },
            error: function(xhr, status, error) {
                // Handle error response
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the profile.'
                });
                console.log(xhr.responseText);
            }
        });
    });
});