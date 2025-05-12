<?php
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60)) {
   
    unset($_SESSION['verified']); 
} 

$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    header('Location: forgot-password.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>WMSU - RMIS</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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

<body>

<section class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h3 class="card-title text-center color-purple" >Reset Password</h3>
              <form id="reset-password-form">
              <div class="form-group">
  <label for="new-password">New Password</label>
  <div class="input-group">
    <div style="position: relative; flex-grow: 1;">
      <input type="password" class="form-control" id="new-password" name="new-password" required style="text-align: center; font-family: Heebo, Arial, sans-serif!important;">
      <span toggle="#new-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>
  </div>
</div>
<div class="form-group">
  <label for="confirm-password">Confirm Password</label>
  <div class="input-group">
    <div style="position: relative; flex-grow: 1;">
      <input type="password" class="form-control" id="confirm-password" name="confirm-password" required style="text-align: center; font-family: Heebo, Arial, sans-serif!important;">
      <span toggle="#confirm-password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
    </div>
  </div>
</div>

<style>
.field-icon {
  position: absolute;
  right: 10px;
  top: 10px;
  cursor: pointer;
}
</style>
                <div id="message" class="msg"></div>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary bg-purple mt-2">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- jQuery library -->
  <script src="static/js/sweetalert2.all.min.js"></script>
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
  <script src="static/js/script.js"></script>
 
  <script src="static/js/forgot-pass.js"></script>

</body>
</html>