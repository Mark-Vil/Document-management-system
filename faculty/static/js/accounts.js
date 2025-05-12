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

// Create Account 
$(document).ready(function() {
  var selectedRole = '';

  $('#create-account-btn').click(function() {
    // Validate if all fields are filled
    var email = $('#email').val();
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();

    if (!email || !password || !confirm_password) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Please fill in all fields before proceeding!'
      });
      return;
    }

    // Show the role selection modal
    $('#roleModal').modal('show');
  });

  $('#student-account').click(function() {
    selectedRole = 'Student';
    $('#roleModal').modal('hide');
    createAccount();
  });

  $('#adviser-account').click(function() {
    selectedRole = 'Adviser';
    $('#roleModal').modal('hide');
    createAccount(); 
  });

  function createAccount() {
    var email = $('#email').val();
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();

    if (password !== confirm_password) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Passwords do not match!'
      });
      return;
    }

    Swal.fire({
      title: 'Creating...',
      html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
      showConfirmButton: false,
      allowOutsideClick: false
    });

    $.ajax({
      url: "../../../php-functions/create-account.php",
      type: 'POST',
      data: {
        'email': email,
        'password': password,
        'role': selectedRole
      },
      success: function(response) {
        // Parse the response if it's not already an object
        if (typeof response === 'string') {
          response = JSON.parse(response);
        }
    
        if (response.status === "error") {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: response.message
          });
        } else if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.message
          }).then((result) => {
            if (result.isConfirmed) {
              console.log('Redirecting');
              window.location.href = 'logged-in.php';  // Redirect to the logged-in page
            }
          });
        }
      },
      error: function(xhr, status, error) {
        console.log('Error response:', xhr, status, error);
        Swal.fire({
          icon: 'error',
          title: 'An error occurred',
          text: xhr.responseJSON.message || 'An unexpected error occurred'
        });
      }
    });
  }
});
// Create Account 

//Login Account

$(document).ready(function() {
  $('#loginForm').on('submit', function(event) {
      event.preventDefault();

      Swal.fire({
        title: 'Logging in...',
        html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
        showConfirmButton: false,
        allowOutsideClick: false
      });

      $.ajax({
          type: 'POST',
          url: '../../wmsurmis/php-functions/login.php',
          data: $(this).serialize(),
          success: function(response) {
              Swal.close(); // Close the Swal.fire modal
              try {
                  var jsonResponse = JSON.parse(response);
                  if (jsonResponse.success) {
                      window.location.href = jsonResponse.redirect_url;
                  } else {
                      $('#container-msg').html('<div class="alert alert-danger">' + jsonResponse.message + '</div>');
                  }
              } catch (e) {
                  $('#container-msg').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
              }
          },
          error: function(xhr, status, error) {
              Swal.close(); // Close the Swal.fire modal
              $('#container-msg').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
          }
      });
  });
});
//Login Account

// CHANGE PASSWORD //
$(document).ready(function() {
  $('#change-faculty-password').on('submit', function(event) {
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
          url: '../../../php-functions/change-password.php',
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
// CHANGE PASSWORD //