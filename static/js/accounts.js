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
  // Check for unverified email on page load
  var unverifiedEmail = localStorage.getItem('unverifiedEmail');
  if (unverifiedEmail) {
      deleteUnverifiedAccount(unverifiedEmail);
      localStorage.removeItem('unverifiedEmail');
  }

  

  var selectedRole = '';
  var departmentCode = '';

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

  $('#faculty-account').click(function() {
    selectedRole = 'Faculty';
    $('#roleModal').modal('hide');
    $('#departmentcode').modal('show');
  });

  $('#departmentcodeform').submit(function(event) {
    event.preventDefault();
    departmentCode = $('#departmentCode').val();
    $('#departmentcode').modal('hide');
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

    var data = {
      'email': email,
      'password': password,
      'role': selectedRole
    };

    if (selectedRole === 'Faculty') {
      data.departmentCode = departmentCode;
    }

    $.ajax({
      url: 'php-functions/create-account.php',
      type: 'POST',
      data: data,
      success: function(response) {
        if (typeof response === 'string') {
          response = JSON.parse(response);
        }
  
        if (response.status === "success") {
          localStorage.setItem('unverifiedEmail', email);
          // Show OTP verification modal
          Swal.fire({
            title: 'Account Created!',
            text: 'Please check your email for verification code. Account will be deleted if not verified.',
            icon: 'success'
          }).then(() => {
            $('#otpModal').modal('show');
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error!', 
            text: response.message
          });
        }
      }
    });
  }

 // Add OTP input handlers
 const inputs = document.querySelectorAll('.otp-input');
    
 inputs.forEach((input, index) => {
     // Allow only numbers
     $(input).on('input', function(e) {
         this.value = this.value.replace(/[^0-9]/g, '');
         
         if (this.value && index < inputs.length - 1) {
             inputs[index + 1].focus();
         }
     });

     // Handle backspace
     $(input).on('keydown', function(e) {
         if (e.key === 'Backspace' && !this.value && index > 0) {
             inputs[index - 1].focus();
         }
     });
 });

 // Modify verify click handler
 $('#verifyOtp').click(function() {
  var email = $('#email').val();
  // Combine OTP inputs
  var otp = Array.from(inputs).map(input => input.value).join('');
  
  if(otp.length !== 6) {
      Swal.fire({
          icon: 'error',
          title: 'Invalid OTP',
          text: 'Please enter all 6 digits'
      });
      return;
  }

  $.ajax({
      url: 'php-functions/verify-initial-otp.php',
      type: 'POST',
      data: {
          email: email,
          otp: otp
      },
      success: function(response) {
        if (typeof response === 'string') {
          response = JSON.parse(response);
        }
  
        if (response.status === "success") {
          // Remove beforeunload handler
          $(window).off('beforeunload');
          // Clear localStorage
          localStorage.removeItem('unverifiedEmail');
          
          $('#otpModal').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Email Verified!',
            text: 'Your account has been activated.'
          }).then(() => {
            window.location.href = response.redirect_url;
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: response.message
          });
        }
      }
    });
  });

  // Handle modal close
  $('#otpModal').on('hidden.bs.modal', function() {
    var email = $('#email').val();
    deleteUnverifiedAccount(email);
  });

   // Modified beforeunload handler
   $(window).on('beforeunload', function(e) {
    var email = $('#email').val() || localStorage.getItem('unverifiedEmail');
    if (email) {
        deleteUnverifiedAccount(email);
        e.preventDefault();
        return ''; // Forces dialog in most browsers
    }
});

function deleteUnverifiedAccount(email) {
  $.ajax({
      url: 'php-functions/cleanup-unverified.php',
      type: 'POST',
      data: { email: email },
      async: false,
      cache: false
  });
}
});
// Create Account 

//Login Account
$(document).ready(function() {

  $('#loginForm').on('submit', function(event) {
      event.preventDefault();

      $(window).off('beforeunload');
      
      Swal.fire({
        title: 'Logging in...',
        html: '<div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color:  #AA0022  ;"><span class="sr-only"></span></div>',
        showConfirmButton: false,
        allowOutsideClick: false
      });

      $.ajax({
          type: 'POST',
          url: 'php-functions/login.php',
          data: $(this).serialize(),
          success: function(response) {
              Swal.close();
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
  $('#change-student-password').on('submit', function(event) {
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
// CHANGE PASSWORD //