<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variables are set
if (isset($_SESSION['student_id']) || isset($_SESSION['faculty_id'])) {
    // Redirect to the login page if the session is not set
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WMSU - RMIS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="static/vendor/aos/aos.css" rel="stylesheet">
  <link href="static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="static/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="static/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="static/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="static/css/style.css" rel="stylesheet">
</head>
  
</head>

<section class=" mt-4" style="background-color: #f5f5f5;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5 ">
              <div class="container">
                <div class="create-account row align-items-center">
                  <div class="col-auto">
                    <a href="index.php" class="create-account-logo">
                      <img src="static/img/wmsu-crop.jpg" style="height: 90px;" alt="">
                    </a>
                  </div>
                  <div class="create-account-text col-auto ml-3">
                    <h3 style="
    color: white; 
    font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
    font-size: 26px; 
    color: #FF0000;
    margin: 0; 
    font-weight: 600; 
    letter-spacing: 0.1px;">WESTERN MINDANAO STATE UNIVERSITY</h3>
                    <h5 style="color: #FF0000; font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 20px; margin: 0; font-weight: 600; letter-spacing: 1px;">RESEARCH REPOSITORY</h5>
                  </div>
                  
                </div>
              </div>


              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
  
                  <p class="text-center h1 mb-5 mx-1 mx-md-4 mt-5" style="color:rgb(44, 43, 43); font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-weight: 600;">Create Account</p>

                  <form id="create-account" class="mx-1 mx-md-4">

                  <div class="form-group">
    <label for="email"></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-envelope"></i>
            </span>
        </div>
        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
        style="font-family: 'Poppins', sans-serif !important; ::placeholder { font-family: 'Poppins', sans-serif !important; }">
    </div>
</div>

<div class="form-group">
    <label for="password"></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-lock"></i>
            </span>
        </div>
        <div style="position: relative; flex-grow: 1;">
            <input type="password" name="password" class="form-control" id="password" placeholder="Password"
            style="font-family: 'Poppins', sans-serif !important; ::placeholder { font-family: 'Poppins', sans-serif !important; }">
            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="confirm_password"></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-key"></i>
            </span>
        </div>
        <div style="position: relative; flex-grow: 1;">
            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password"
            style="font-family: 'Poppins', sans-serif !important; ::placeholder { font-family: 'Poppins', sans-serif !important; }">
            <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
        </div>
    </div>
</div>

    <div class="d-flex justify-content-center mx-4 mt-3 mb-lg-4" >
      <button type="button" id="create-account-btn" class="btn btn-lg" style="background-color: rgb(255,0,51); color: white;">Create</button>
    </div>
  </form>


  <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="font-family: 'Poppins', sans-serif;">
          <div class="modal-header">
            <h5 class="modal-title" id="roleModalLabel" style="font-family: 'Poppins', sans-serif;">Select Role</h5>
          </div>
          <div class="modal-body">
            <p style="font-family: 'Poppins', sans-serif;">Please select your role</p>
            <div class="card-deck">
              <div class="card" id="student-account" style="cursor: pointer;">
                <div class="card-body text-center">
                  <h5 class="card-title" style="font-family: 'Poppins', sans-serif;">Student</h5>
                </div>
              </div>
              <div class="card" id="faculty-account" style="cursor: pointer;">
                <div class="card-body text-center">
                  <h5 class="card-title" style="font-family: 'Poppins', sans-serif;">Adviser</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div class="modal fade" id="departmentcode" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="font-family: 'Poppins', sans-serif;">
          <div class="modal-header">
            <h5 class="modal-title" id="roleModalLabel" style="font-family: 'Poppins', sans-serif; color: #444444">Enter Your Department Code</h5>
          </div>
          <div class="modal-body">
            <p style="font-family: 'Poppins', sans-serif;">Please enter your department code</p>
            <form id="departmentcodeform">
              <div class="mb-3 form-floating">
                <input type="text" 
                       class="form-control" 
                       id="departmentCode" 
                       name="departmentCode" 
                       maxlength="8" 
                       placeholder="Department Code" 
                       style="height: 45px !important; font-family: 'Poppins', sans-serif;" 
                       required>
                <label for="departmentCode" style="font-family: 'Poppins', sans-serif;">Department Code</label>
              </div>
              <div class="text-end">
                <button type="Submit" class="btn btn-primary" style="font-family: 'Poppins', sans-serif;">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Add this modal for OTP verification -->
    <div class="modal fade" id="otpModal" tabindex="-1"  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content" style="font-family: 'Poppins', sans-serif;">
      <div class="modal-header">
        <h5 class="modal-title" id="roleModalLabel" style="font-family: 'Poppins', sans-serif; color: #444444">Email Verification</h5>
      </div>
      <div class="modal-body">
        <p style="font-family: 'Poppins', sans-serif;">Please enter the verification code sent to your email.</p>
        <div class="d-flex justify-content-center gap-2">
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
          <input type="text" class="form-control text-center otp-input" maxlength="1" style="width: 45px; height: 45px; font-size: 24px; font-family: 'Poppins', sans-serif;" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="verifyOtp">Verify</button>
      </div>
    </div>
  </div>
</div>



  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <div id="preloader"></div>


<!-- Other HTML content -->

<!-- Vendor JS Files -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="static/js/jquery.min.js"></script>
<script src="static/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="static/vendor/aos/aos.js"></script>
<script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="static/vendor/glightbox/js/glightbox.min.js"></script>
<script src="static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="static/vendor/swiper/swiper-bundle.min.js"></script>
<script src="static/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="static/js/main.js"></script>
<script src="static/js/accounts.js"></script>
</body>

</html>

<body>