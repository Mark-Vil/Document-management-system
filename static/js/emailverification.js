$(document).ready(function() {
    $('#verify-email-btn').click(function() {
        var email = $('.email-data').data('email');

        Swal.fire({
            title: 'Sending code...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #AA0022;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/send-otp.php',
            type: 'POST',
            data: { email: email },
            dataType: 'json', // Ensure the response is parsed as JSON
            success: function(response) {
                Swal.close(); // Close the Swal spinner
                if (response.status === 'success') {
                    $('#otpModal').modal('show'); // Show the Bootstrap modal
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close(); // Close the Swal spinner in case of error
                console.error(xhr.responseText);
            }
        });
    });

    $('#otpForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var otpCode = $('#otpCode').val();
        var email = $('.email-data').data('email');

        Swal.fire({
            title: 'Verifying...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #AA0022;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/verify-otp.php',
            type: 'POST',
            data: { email: email, otpCode: otpCode },
            dataType: 'json', // Ensure the response is parsed as JSON
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'OTP verified successfully!',
                        timer: 2000, // 5-second delay
                        showConfirmButton: false
                    }).then(() => {
                        $('#otpModal').modal('hide'); // Hide the modal on success
                        location.reload(); // Reload the page after the alert is closed
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        timer: 5000, // 5-second delay
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while verifying the OTP. Please try again.',
                    timer: 2000, // 5-second delay
                    showConfirmButton: false
                });
                console.error(xhr.responseText);
            }
        });
    });

      // Handle Change Email button click
      $('#change-email-btn').click(function() {
        $('#changeEmailModal').modal('show'); // Show the Change Email modal
    });

    // Handle Change Email form submission
    $('#changeEmailForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var newEmail = $('#newEmail').val();
        var currentEmail = $('.email-data').data('email');

        Swal.fire({
            title: 'Sending code...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #AA0022;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/change-email.php',
            type: 'POST',
            data: { currentEmail: currentEmail, newEmail: newEmail },
            dataType: 'json', // Ensure the response is parsed as JSON
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'OTP sent to new email. Please verify.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#changeEmailModal').modal('hide'); // Hide the Change Email modal
                            $('#otpModal').modal('show'); // Show the OTP modal for new email verification
                            // Update the email data attribute to the new email
                            $('.email-data').data('email', newEmail);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while changing the email. Please try again.',
                });
                console.error(xhr.responseText);
            }
        });
    });

    $('#verify-account-btn').click(function() {
        $('#advisorCodeModal').modal('show'); // Show the Advisor Code modal
    });

    $('#resubmit-account-btn').click(function() {
        $('#advisorCodeModal').modal('show'); // Show the Advisor Code modal for resubmission
    });

    // Handle Advisor Code form submission
    $('#advisorCodeForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        Swal.fire({
            title: 'Submitting...',
            html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #AA0022;"><span class="sr-only"></span></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'php-functions/verify-account.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json', // Ensure the response is parsed as JSON
            success: function(response) {
                Swal.close(); // Close the Swal spinner
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Account verification request successful!',
                        timer: 2500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // Reload the page after the alert is closed
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while verifying the account. Please try again.',
                    timer: 3000,
                    showConfirmButton: false
                });
                console.error(xhr.responseText);
            }
        });
    });


});

