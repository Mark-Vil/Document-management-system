<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


// Check if the user is logged in
// Check if the user is logged in
if (isset($_SESSION['email']) && isset($_SESSION['college_code'])) {
  header("Location: college/college-dashboard.php");
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

<section class=" mt-4" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5 ">
              <div class="container">
                <div class="create-account row align-items-center">
                  <div class="col-auto">
                    <a href="" class="create-account-logo">
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
  
                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4"  style="color:rgb(44, 43, 43); font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-weight: 600;">COLLEGE ADMIN</p>

                  <form id="college-account-login" class="mx-1 mx-md-4">
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

    <div class="d-flex justify-content-center mx-4 mt-3 mb-lg-4" >
      <button type="submit" id="college-account-login-btn" class="btn btn-lg" style="background-color: rgb(255,0,51); color: white;">Log In</button>
    </div>
  </form>

    
  
  
  


  
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
<script src="college/static/js/main.js"></script>
<script src="college/static/js/college-account.js"></script>
</body>

</html>

<body>