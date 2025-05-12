<?php
include 'php-functions/dbconnection.php';
include 'php-functions/check_userprofile.php';
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Redirect to the login page if the session is not set
    header("Location: index.php");
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
    $role = $user_data['role'];
} else {
    $first_name = '';
    $last_name = '';
    $profile_path = '';
    $is_verified = 0;
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

  <title>WMSU RMIS</title>
  


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
    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" id="notificationDropdown">
        <i class="bi bi-bell"></i>
        <span class="badge bg-danger badge-number" id="notificationBadge">0</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" id="notificationList">
        <li class="dropdown-header">
            You have <span id="notificationCount">0</span> new notifications
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2"></span></a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <!-- Notifications will be inserted here by JavaScript -->
    </ul>
</li>

<style>
    .new-notification {
        background-color: #dee2e6;
    }

    .notifications {
        max-height: 300px; /* Set the maximum height for the dropdown */
        overflow-y: auto; /* Enable vertical scrolling */
    }
</style>
              <!-- End Notification Dropdown Items -->


        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo htmlspecialchars($profile_path); ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Mark Villiones</h6>
              <span>Student</span>
            </li>
            <li>
              <hr class="dropdown-divider">
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
        <a class="nav-link collapse" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-upload"></i><span>Upload</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="dashboard-nav" class="nav-content collapsed" data-bs-parent="#sidebar-nav">
          <li>
            <a href="submit.php">
              <i class="bi bi-circle"></i><span>Submit</span>
            </a>
          </li>
          <li>
            <a href="myupload.php" class="active">
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
        <a class="nav-link collapsed" data-bs-target="#history-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-clock-history"></i><span>History</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="history-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="review-history.php">
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

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Review</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content" data-bs-parent="#sidebar-nav">
          <li>
            <a href="review.html">
              <i class="bi bi-circle"></i><span>Review</span>
            </a>
          </li>

        </ul>
      </li> -->
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
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">My Submissions</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
        <div class="table-responsive" style="height: 480px;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Title</th>
                <th scope="col">View</th>
                <th scope="col">Upload Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="submissionTableBody">
            <!-- Data will be inserted here by JavaScript -->
        </tbody>
    </table>
</div>

  </div>
  <!-- End Left side columns -->

      </div>
    </section>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                <input type="hidden" id="editSubmissionId" name="submission_id">
                    <div class="mb-3">
                        <label for="editResearchTitle" class="form-label">Research Title</label>
                        <input type="text" class="form-control" id="editResearchTitle" name="research_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAuthor" class="form-label">Author</label>
                        <input type="text" class="form-control" id="editAuthor" name="author" required>
                    </div>
                    <div class="col-md-6">
                    <label class="small mb-1" for="coauthorname">Co-Author</label>
                    <div id="coauthor-container">
                      <input class="form-control mb-2" id="editCoAuthors" name="coauthorname[]" type="text"
                        placeholder="Enter Co-Author Full Name">
                    </div>
                    <button type="button" class="btn btn-primary" id="add-coauthor-btn" style=" background-color: #FF0033;
        border-color: #FF0033;">Add Co-Author</button>
                  </div>
                    <div class="mb-3">
                        <label for="editAbstract" class="form-label">Abstract</label>
                        <textarea class="form-control" id="editAbstract" name="abstract" rows="3" required></textarea>
                    </div>
                    <div class="col-md-6">
                    <label class="small mb-1" for="addkeywords">Keywords</label>
                    <div id="addkeywords-container">
                      <input class="form-control mb-2" id="editKeywords" name="addkeywords[]" type="text"
                        placeholder="Enter Keywords">
                    </div>
                    <button type="button" class="btn btn-primary" id="add-keywords-btn" style=" background-color: #FF0033;
        border-color: #FF0033;">Add keywords</button>
                  </div>
                    <div class="mb-3">
                        <label for="editFile" class="form-label">Upload New PDF</label>
                        <input type="file" class="form-control" id="editFile" name="file">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


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

    <!-- Modal -->
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
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 500px;">
    <div class="copyright">
      <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>


  <!-- Vendor JS Files -->
  <script src="../static/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../static/vendor/aos/aos.js"></script>
  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../static/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../static/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../static/vendor/php-email-form/validate.js"></script>

    <!-- JS File -->
 <script src="static/js/main.js"></script>
  <script src="static/js/adviser-profile.js"></script>
  <script src="static/js/submission.js"></script>
</body>

</html>