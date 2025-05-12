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
                    <a href="{% url 'studentrmis:home' %}" class="create-account-logo">
                      <img src="{% static 'img/wmsu-crop.jpg' %}" style="height: 90px;" alt="">
                    </a>
                  </div>
                  <div class="create-account-text col-auto ml-3">
                    <h3 style="color: red; font-family: Bitter; font-weight: 600; font-size: 26px;">WESTERN MINDANAO STATE UNIVERSITY</h3>
                    <h5 style="color: red; font-family: Bitter; font-weight: 600; font-size: 16px;">RESEARCH REPOSITORY</h5>
                  </div>
                  
                </div>
              </div>


              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
  
                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Create Account</p>

                  <form id="create-college-account" class="mx-1 mx-md-4">
    <div class="d-flex flex-row align-items-center mb-4">
      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
      <div data-mdb-input-init class="form-outline flex-fill mb-0">
        <input type="email" id="email" class="form-control" />
        <label class="form-label" for="email">Email</label>
      </div>
    </div>

    <div class="d-flex flex-row align-items-center mb-4"  style="position: relative; flex-grow: 1;">
      <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
      <div data-mdb-input-init class="form-outline flex-fill mb-0">
        <input type="password" id="password" class="form-control" />
        <label class="form-label" for="password">Password</label>
        <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
      </div>
    </div>

    <div class="d-flex flex-row align-items-center mb-4"  style="position: relative; flex-grow: 1;">
      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
      <div data-mdb-input-init class="form-outline flex-fill mb-0">
        <input type="password" id="confirm_password" class="form-control" />
        <label class="form-label" for="confirm_password">Confirm Password</label>
        <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
      </div>
    </div>

    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4" >
      <button type="button" id="create-account-btn" class="btn btn-lg" style="background-color: rgb(255,0,51); color: white;">Create</button>
    </div>
  </form>

    <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="roleModalLabel">Enter Your 8 Digit College Code</h5>
          </div>
          <div class="modal-body">
            <p>Please enter your 8-digit college code:</p>
            <form id="collegecodeform">
              <div class="mb-3">
                <label for="collegeCode" class="form-label">College Code</label>
                <input type="text" class="form-control" id="collegeCode" name="collegeCode" maxlength="8" required>
              </div>
              <div class="text-end">
                <button type="Submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
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



  <!-- ======= Footer ======= -->
  <footer id="footer">
    <!-- <div class="footer-top">
      <div class="container">
        <div class="row"> -->

          <!-- <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <p>
                A108 Adam Street <br>
                NY 535022, USA<br><br>
                <strong>Phone:</strong> +1 5589 55488 55<br>
                <strong>Email:</strong> info@example.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div> -->

          <!-- <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Research Content</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Publish</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About</a></li>
              
            </ul>
          </div>
        </div>
      </div>
    </div> -->

    <div class="container">
      <div class="copyright">
        <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
        </H2>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/dewi-free-multi-purpose-html-template/ -->
       
      </div>
    </div>
  </footer>
  <!-- End Footer -->

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
<script src="college/static/js/main.js"></script>
<script src="college/static/js/college-account.js"></script>
</body>

</html>

<body>