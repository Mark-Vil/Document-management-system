<?php

// Check if the session is already started

if (session_status() == PHP_SESSION_NONE) {

  session_start();

}



$sessionSet = isset($_SESSION['email']) && isset($_SESSION['role']) && (isset($_SESSION['student_id']) || isset($_SESSION['faculty_id']));







// Determine the dashboard link based on the role and specific session variables

$dashboardLink = '';
$logoutLink = '';
if (isset($_SESSION['student_id']) && $_SESSION['role'] === 'Student') {
  $dashboardLink = 'student-dashboard.php';
  $logoutLink = 'php-functions/logout.php';
} elseif (isset($_SESSION['faculty_id']) && $_SESSION['role'] === 'Faculty') {
  $dashboardLink = 'faculty/dashboard.php';
  $logoutLink = 'php-functions/logout.php';
}

?>



<!DOCTYPE html>

<html lang="en">

<script>

    var userId = <?php echo isset($_SESSION['faculty_id']) ? json_encode($_SESSION['faculty_id']) : (isset($_SESSION['student_id']) ? json_encode($_SESSION['student_id']) : 'null'); ?>;

</script>

<head>

  <meta charset="utf-8">

  <meta content="width=device-width, initial-scale=1.0" name="viewport">



  <title>WMSU - RMIS</title>

  

  <!-- Google Fonts -->

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

  <!-- Vendor CSS Files -->

  <link href="../static/vendor/aos/aos.css" rel="stylesheet">

  <link href="../static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="../static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <link href="../static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <link href="../static/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <link href="../static/vendor/remixicon/remixicon.css" rel="stylesheet">

  <link href="../static/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">



  <!-- Main CSS File -->

  <link href="../static/css/style.css" rel="stylesheet">



  

</head>



<body>



  <!-- ======= Header ======= -->

  <header id="header" class="fixed-top ">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
        <!-- Logo and Text Section -->
        <div class="d-flex align-items-center">
            <a href="" class="logo me-2">
                <img src="static/img/wmsu-crop.jpg" alt="">
            </a>
            <div class="wmsu-text">
                <h3 style="
                    color: white; 
                    font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
                    font-size: 22px; 
                    margin: 0; 
                    font-weight: 600; 
                    letter-spacing: 0.1px;">
                    WESTERN MINDANAO STATE UNIVERSITY
                </h3>
                <h5 style="
                    color: white; 
                    font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; 
                    font-size: 14px; 
                    margin: 0; 
                    font-weight: 600; 
                    letter-spacing: 1px;">
                    RESEARCH REPOSITORY
                </h5>
            </div>
        </div>

        <!-- Navigation Section -->
        <div class="list-menu" style="z-index: 1000; border-color: white;">
            <nav id="navbar" class="navbar justify-content-center">
            <ul>
                    <li><a class="nav-link scrollto" href="">Home</a></li>
                    <li><a class="nav-link scrollto active" href="content.php">Research Content</a></li>
                    <?php if ($sessionSet): ?>
                        <li><a class="nav-link scrollto" href="<?php echo $dashboardLink; ?>">Upload</a></li>
                        <li><a class="nav-link scrollto" href="<?php echo $dashboardLink; ?>">Dashboard</a></li>
                        <li><a class="getstarted scrollto" href="<?php echo $logoutLink; ?>">Logout</a></li>
                    <?php else: ?>
                        <li><a class="nav-link scrollto" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Upload</a></li>
                        <li><a class="nav-link scrollto" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Dashboard</a></li>
                        <li><a class="getstarted scrollto" data-bs-toggle="modal" data-bs-target="#loginModal" href="#"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
                <i class="bi bi-x mobile-nav-toggle close-nav" style="display: none"></i>
            </nav>
        </div>
    </div>
</div>

    <style>

     .search-results {

    position: absolute;

    top: 245px;

    width: 600px;

    max-height: 100px;

    overflow-y: auto;

    border-radius: 10px;

    background-color: #fff;

    z-index: 1000;

}



/* Media query for screens with a max width of 500px */

@media (max-width: 500px) {

  .search-results {

        top: 50%;

        width: 350px;

        max-height: 160px;

        left: 10px;

        right: 0;

        position: absolute;

    }

    

}

@media (max-width: 400px) {

    .search-result-item {

        top: 50%;

        position: relative; /* Ensure the position is set to relative or absolute */

    }

}



/* Hide scrollbar for Webkit browsers (Chrome, Safari) */

.search-results::-webkit-scrollbar {

    display: none;

}



.search-result-item {

    padding: 10px;

    border-bottom: 1px solid #eee;

    cursor: pointer;

}



.search-result-item:last-child {

    border-bottom: none;

}



.highlight {

    color: #FF4081;

}



.search-results-border {

    border: 2px solid #FF4081; /* Change this to your desired border color */

}

    </style>

  <div class="search-field d-flex justify-content-center align-items-center mb-2">

    <form id="search-form" method="GET" action="javascript:void(0);">

      <input type="search"  type="search" name="search" id="search-input" class="search-input" style="width: 500px; border-radius: 10px; height: 40px; border: 0; outline: none;" placeholder="Search for title, author or keywords">

    </form>

      <i class="bi bi-list mobile-nav-toggle-loggedin" ></i>

      <div id="search-results" class="search-results"></div>

    </div>





    <div class="row selections gx-2 justify-content-center">

      <div class="col-4 col-md-2">

      <select id="college-select" class="form-select" style="width: 100%; border-radius: 10px; height: 35px; border: 0; outline: none;">

                      <option value="">All Colleges</option>

                    <?php

                    require 'php-functions/dbconnection.php';



                    // Fetch colleges

                    $collegesQuery = "SELECT college_code, college_name FROM colleges";

                    $collegesResult = $conn->query($collegesQuery);



                    if ($collegesResult->num_rows > 0) {

                        while ($row = $collegesResult->fetch_assoc()) {

                            echo '<option value="' . $row['college_code'] . '">' . $row['college_name'] . '</option>';

                        }

                    }

                    ?>

                </select>

      </div>

      <div class="col-4 col-md-2">

      <select id="department-select" class="form-select" style="width: 100%; border-radius: 10px; height: 35px; border: 0; outline: none;">

          <option selected disabled>Department</option>

        </select>

      </div>

      <div class="col-4 col-md-2">

        <select id="year-select" class="form-select" style="width: 100%; border-radius: 10px; height: 35px; border: 0; outline: none;">

            <option selected disabled>Year</option>

        </select>

    </div>

    </div>



    </div>

  </header>

  <!-- End Header -->



  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"

    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title color-purple" id="loginModalLabel">Please Login</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>

        <div class="modal-body">

          <form id="loginForm" method="post">

            <input type="hidden" name="next" id="next" value="">

            <div class="form-group">

              <label for="acc_name"></label>

              <div class="input-group">

                <div class="input-group-prepend">

                  <span class="input-group-text" id="inputGroupPrepend">

                    <i class="fas fa-user"></i>

                  </span>

                </div>

                <input type="email" name="email" class="form-control" id="email" placeholder="email"

                  style="font-family: Heebo, Arial, sans-serif!important;">

              </div>

            </div>

            <div class="form-group">

              <label for="acc_password"></label>

              <div class="input-group">

                <div class="input-group-prepend">

                  <span class="input-group-text" id="inputGroupPrepend">

                    <i class="fas fa-key"></i>

                  </span>

                </div>

                <div style="position: relative; flex-grow: 1;">

                  <input type="password" name="password" class="form-control" id="acc_password" placeholder="Password"

                    style="font-family: Heebo, Arial, sans-serif!important;">

                  <span toggle="#acc_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

                </div>

              </div>

            </div>



          

            <div class="form-group text-center">

              <button type="submit" class="btn color-white mt-2" style="background-color: red; color: white;">LOGIN</button>

            </div>

            <div class="text-center mt-2">

              <a href="#" class="btn btn-outline-secondary">

                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png" alt="Google logo" width="20" height="20">

                Sign in with Google

              </a>

            </div>

            <div class="text-center mt-2">

              <a href="" style="color: black;">Forgot Password?</a>

              <a href="create-account.html" style="color: black;">Create Your Account</a>

            </div>

            <div class="text-center" id="container-msg"></div>

          </form>

        </div>

      </div>

    </div>

  </div>







  <main id="main">
 



    <!-- ======= Latest Section ======= -->

    <?php 
$styleClass = ($userId && hasUserInteractions($userId)) ? '' : 'margin-top-extra';
?>

<section id="about" class="about about-latest <?php echo $styleClass; ?>">

<style>
.margin-top-extra {
    margin-top: 120px !important;
    max-height: 1200px;
}

.card {
    cursor: pointer;
}
</style>
  <div class="container" data-aos="fade-down">

    <div class="row">

      <div class="col-lg-12">

        <h1 class="ml-3 mb-4">Find Document</h1>

      </div>

    </div>

  </div>

  

  <div class="container" data-aos="fade-down">

    <style>

    .card {

        cursor: pointer;

    }

    .pagination .page-link {

        color: #AA0022;

    }

    .pagination .page-item.active .page-link {

        background-color: #AA0022;

        border-color: #AA0022;

        color: white; /* Set text color to white for active page link */

    }

    .pagination .page-link:hover {

        color: white; /* Set text color to white when hovered */

        background-color: #AA0022; /* Ensure background color remains the same when hovered */

        border-color: #AA0022; /* Ensure border color remains the same when hovered */

    }

    </style>

    <div class="row latest"  style="min-height: 500px;">

    </div>

  </div>

</section>



  </main>

  <!-- End #main -->



  <!-- ======= Footer ======= -->

  <footer id="footer">

    <div class="container">

      <div class="copyright">

        <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.

        </H2>

      </div>

      <div class="credits">

      </div>

    </div>

  </footer>

  <!-- End Footer -->



  <div id="preloader"></div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>





  <!-- Vendor JS Files -->

  <script src="../static/js/sweetalert2.all.min.js"></script>

  <script src="../static/js/jquery.min.js"></script>

  <script src="../static/vendor/purecounter/purecounter_vanilla.js"></script>

  <script src="../static/vendor/aos/aos.js"></script>

  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../static/vendor/glightbox/js/glightbox.min.js"></script>

  <script src="../static/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <script src="../static/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="../static/vendor/php-email-form/validate.js"></script>

  

  <script src="../static/js/main.js"></script>

  <script src="../static/js/script.js"></script>

  <script src="../static/js/accounts.js"></script>

  <script src="static/js/search-document.js"></script>

</body>



</html>