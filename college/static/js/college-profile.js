//COLLEGE CHANGE PASSWORD 
$(document).ready(function() {
    $('#change-college-password').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Gather form data
        var formData = {
            email: $('#email').val(),
            password: $('#currentPassword').val(),
            newpassword: $('#newPassword').val(),
            confirmpassword: $('#confirmPassword').val()
        };

        // Perform AJAX request
        $.ajax({
            url: '../college/php-functions/change-password.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while changing the password.'
                });
            }
        });
    });
});
//COLLEGE CHANGE PASSWORD 

//CHANGE PROFILE PHOTO //
$(document).ready(function() {
    // Trigger file input click when "Change Profile" text is clicked
    $('#changeProfileText').on('click', function() {
        $('#profile_image').click();
    });

    // Handle file input change event
    $('#profile_image').on('change', function() {
        var formData = new FormData($('#uploadProfileImageForm')[0]);

        $.ajax({
            url: '../college/php-functions/change-profile-photo.php', // The PHP script that handles the form submission
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
                    });
                    // Update the profile image
                    $('#profileImage').attr('src', jsonResponse.profile_path);
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
                    text: 'An error occurred while uploading the profile image.'
                });
                console.log(xhr.responseText);
            }
        });
    });
});
// CHANGE PROFILE PHOTO //