//COLLEGE CHANGE PASSWORD 
$(document).ready(function() {
    $('#change-admin-password').on('submit', function(event) {
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
            url: 'php-functions/change-password.php',
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
