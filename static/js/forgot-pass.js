// FORGOT PASSWORD SCRIPT //
$(document).ready(function() {
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

    $('#forgot-password-form').on('submit', function(e) {
        e.preventDefault();

        Swal.showLoading();

        $.ajax({
            type: 'POST',
            url: 'php-functions/forgot-password.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Log the response
                Swal.close();
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#otpVerificationModal').modal('show'); // Show the OTP verification modal
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.close();
                console.log(textStatus, errorThrown);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while sending the OTP.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
  // FORGOT PASSWORD SCRIPT //
  
  // OTP VERIFICATION SCRIPT //
  $('#otp-verification-form').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: 'php-functions/otp-verification.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'reset-password.php'; // Redirect to reset password page
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while verifying the OTP.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
});
  // OTP VERIFICATION SCRIPT //
  
// RESET PASSWORD SCRIPT //
$(document).ready(function() {
    $('#reset-password-form').on('submit', function(e) {
        e.preventDefault();

        var newPassword = $('#new-password').val();
        var confirmPassword = $('#confirm-password').val();

        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords do not match'
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'php-functions/reset-password.php',
            data: $(this).serialize(),
            dataType: 'json', // Expect JSON response
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while resetting the password.'
                });
            }
        });
    });
});