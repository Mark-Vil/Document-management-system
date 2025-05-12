<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || !isset($_SESSION['student_id'])) {
  // Redirect to the login page if the session is not set
  header("Location: index.php");
  exit();
}

// Echo the student_id if it is set
// if (isset($_SESSION['student_id'])) {
//   echo "Student ID: " . $_SESSION['student_id'];
// }

// Determine the dashboard link based on the role and specific session variables
$dashboardLink = '';
$logoutLink = '';
if (isset($_SESSION['student_id']) && $_SESSION['role'] === 'Student') {
  $dashboardLink = 'student-dashboard.php';
  $logoutLink = 'php-functions/logout.php';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
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
<script>
    var userId = <?php echo isset($_SESSION['faculty_id']) ? json_encode($_SESSION['faculty_id']) : (isset($_SESSION['student_id']) ? json_encode($_SESSION['student_id']) : 'null'); ?>;
</script>

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
                    <li><a class="nav-link scrollto" href="index.php">Home</a></li>
                    <li><a class="nav-link scrollto active" href="">Research Content</a></li>
                    <li><a class="nav-link scrollto" href="<?php echo $dashboardLink; ?>">Dashboard</a></li>
                    <li><a class="getstarted scrollto" href="<?php echo $logoutLink; ?>">Logout</a></li>
                </ul>
                <i class="bi bi-x mobile-nav-toggle close-nav" style="display: none"></i>
            </nav>
        </div>
    </div>
</div>

    <style>
     .search-results {
    position: absolute;
    top: 165px;
    width: 600px;
    max-height: 100px;
    overflow-y: auto;
    border-radius: 10px;
    background-color: #fff;
    z-index: 1000;
}
.spinner-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid #FF4081;
    border-radius: 15px;
    min-height: 100px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #ff4a17;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.no-results-message {
    text-align: center;
    width: 100%;
    display: block;
    padding: 20px;
    border: 2px solid #FF4081;
    border-radius: 15px;
    color: #FF4081;
    margin: 10px 0;
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
    <form id="search-form" method="GET" action="search.php">
      <input type="search"  type="search" name="search" id="search-input" class="search-input" style="width: 500px; border-radius: 10px; height: 40px; border: 0; outline: none;" placeholder="Search for title, abstract, author, date or keywords">
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
          <option selected disabled>All Departments</option>
        </select>
      </div>
      <div class="col-4 col-md-2">
        <select id="year-select" class="form-select" style="width: 100%; border-radius: 10px; height: 35px; border: 0; outline: none;">
            <option selected disabled>Year</option>
        </select>
    </div>
    </div>

  </header>
  <!-- End Header -->

  <main id="main">

  
  <?php
include 'php-functions/check-userinteractions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userId = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : (isset($_SESSION['faculty_id']) ? $_SESSION['faculty_id'] : '');

if ($userId && hasUserInteractions($userId)) {
?>
<section id="about" class="about about-recent" style="margin-top: 150px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7" id="recentlyVisited">
                <!-- Recently visited content loaded here -->
            </div>
            <div class="col-lg-4 col-md-4 recent-similar">
                <h1 class="ml-3 mb-4">You may like</h1>
                <div class="flex-column explore" style="max-height: 400px; overflow-y: auto;" id="recentSimilar">
                    <!-- Similar content loaded here -->
                </div>
            </div>
        </div>
    </div>
</section>
<?php
}
?>

    <!-- ======= Latest Section ======= -->
<section id="about" class="about about-latest" style="<?php echo $userId && hasUserInteractions($userId) ? '' :  'margin-top: 150px!important; max-height: 1200px'; ?>">
  <div class="container" data-aos="fade-down">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="ml-3 mb-4">Latest</h1>
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
    <div class="row latest"  style="min-height: 300px;">

    </div>
  </div>
</section>
  

  </main>
  <!-- End #main -->

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
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="static/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="static/vendor/aos/aos.js"></script>
  <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="static/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="static/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="static/vendor/php-email-form/validate.js"></script>
  
  <!-- Template Main JS File -->
  <script src="static/js/main.js"></script>
  <script src="static/js/search.js"></script>
</body>

</html>