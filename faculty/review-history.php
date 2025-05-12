<?php
include 'php-functions/review.php';
include 'php-functions/check_userprofile.php';
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
  // Redirect to the login page if the session is not set
  header("Location: ../index.php");
  exit();
}

$user_id = $_SESSION['faculty_id'];
// Fetch user data
$user_data = fetch_user_data($user_id);

if ($user_data) {
  $first_name = $user_data['first_name'];
  $last_name = $user_data['last_name'];
  $profile_path = $user_data['profile_path'];
  $is_verified = $user_data['is_verified'];
  $college_name = $user_data['college_name'];
  $department_name = $user_data['department_name'];
  $email = $user_data['email'];
  $adviser_code = $user_data['adviser_code'];
  $role = $user_data['role'];
} else {
  $first_name = '';
  $last_name = '';
  $college_name = '';
  $department_name = '';
  $profile_path = '';
  $is_verified = 0; // Default to 0 if user data is not found
  $email = $user_data['email'];
  $adviser_code = '';
  $role = '';
  
}
if ($is_verified == 0) {
  header("Location: dashboard.php");
  exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ADVISER RMIS</title>
  


  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../static/vendor/aos/aos.css" rel="stylesheet">
  <link href="../static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../static/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../static/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../static/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="static/css/dashboard-style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
      <a href="logged-in.php" class="logo d-flex align-items-center">
        <img src="static/img/wmsu-crop.jpg" alt="">
        <span class="d-none d-lg-block">WMSU RMIS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> -->
    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown">



        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo htmlspecialchars($profile_path); ?>" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
            <h6><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h6>
            <span><?php echo htmlspecialchars($role); ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="profile.php">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="php-functions/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  
  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="">
        <i class="bi bi-upload"></i><span>Upload</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="dashboard-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="submit.php">
                <i class="bi bi-circle"></i><span>Submit</span>
            </a>
        </li>
        <li>
            <a href="myupload.php" >
              <i class="bi bi-circle"></i><span>My Uploads</span>
            </a>
          </li>
    </ul>
</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#review-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-menu-button-wide"></i><span>Review</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="review-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="review.php">
              <i class="bi bi-circle"></i><span>Review Submissions</span>
            </a>
          </li>
          <li>
            <a href="account-verification.php">
              <i class="bi bi-circle"></i><span>Student Verification</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapse" data-bs-target="#history-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-clock-history"></i><span>History</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="history-nav" class="nav-content collapsed" data-bs-parent="#sidebar-nav">
          <li>
            <a href="review-history.php"  class="active">
              <i class="bi bi-circle"></i><span>My Archive</span>
            </a>
          </li>
          <li>
            <a href="verification-history.php">
              <i class="bi bi-circle"></i><span>Student Verification History</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- End Components Nav -->

      

      <li class="nav-heading">Account</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <nav>
      <ol class="breadcrumb d-flex">
          <div class="d-flex">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">My Archive</li>
          </div>
          <li class="breadcrumb-item ms-auto">
            <select id="yearFilterchart" class="form-select">
              <option selected>Choose year...</option>
            </select>
          </li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <style>
          .highlight {
    background-color: yellow;
}
.abstract-cell {
    cursor: pointer;
}
        </style>

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="table-responsive" style="height: 480px;">
          <input type="text" id="archivesearchInput" placeholder="Search for documents.." class="form-control mb-3">
          <table class="table doc-history table-striped">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Document Title</th>
                    <th scope="col">Abstract</th>
                    <th scope="col">View</th>
                    <th scope="col">Date Submitted</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="archiveTableBody">
          </tbody>
        </table>
      </div>
    </div>
  <!-- End Left side columns -->

  <div class="modal fade" id="viewhistoryModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Submission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Details will be populated here by jQuery -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Reject Comments -->
<div class="modal fade" id="rejectCommentsModal" tabindex="-1" aria-labelledby="rejectCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectCommentsModalLabel">Reject Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectCommentsForm">
                    <div class="mb-3">
                        <label for="rejectComments" class="form-label">Comments</label>
                        <textarea class="form-control" id="rejectComments" name="comments" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="rejectSubmissionId" name="submission_id">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Revise Comments -->
<div class="modal fade" id="reviseCommentsModal" tabindex="-1" aria-labelledby="reviseCommentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviseCommentsModalLabel">Revise Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviseCommentsForm">
                    <div class="mb-3">
                        <label for="reviseComments" class="form-label">Comments</label>
                        <textarea class="form-control" id="reviseComments" name="comments" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="reviseSubmissionId" name="submission_id">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Submission Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Metadata will be loaded here -->
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>
    </div>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 500px;">
    <div class="copyright">
      <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

      <!-- Vendor JS Files -->
      <script src="../static/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../static/vendor/aos/aos.js"></script>
  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../static/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../static/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../static/vendor/php-email-form/validate.js"></script>
  <!-- CDN JAVASCRIPT -->
  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>


  <!-- JS File -->
  <script src="static/js/main.js"></script>
  <script src="static/js/faculty-script.js"></script>
  <script src="static/js/history.js"></script>

</body>

</html>