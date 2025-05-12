  // SHOW PASSWORD
$(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
    input.attr("type", "text");
    } else {
    input.attr("type", "password");
    }
});
  // SHOW PASSWORD

$(document).ready(function() {
    $('#create-account-btn').on('click', function(event) {
        event.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        $.ajax({
            url: 'php-functions/create-admin.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    alert(jsonResponse.message);
                    // Optionally, you can clear the form or redirect the user
                    $('#create-admin-account')[0].reset();
                } else {
                    alert(jsonResponse.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});

$(document).ready(function() {
    $('#admin-account-login').on('submit', function(event) {
        event.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();


        Swal.fire({
            title: 'Logging in...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'Admin/php-functions/admin-login.php',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: jsonResponse.message,
                    }).then(() => {
                        window.location.href = 'Admin/admin-dashboard.php'; // Redirect to admin dashboard
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: jsonResponse.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: error,
                });
            }
        });
    });
});