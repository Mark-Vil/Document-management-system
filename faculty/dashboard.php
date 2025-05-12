<?php
include 'php-functions/dbconnection.php';
include 'php-functions/check_userprofile.php';
session_start();

// Check if the session variables are set
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
  // Redirect to the login page if the session is not set
  header("Location: ../index.php");
  exit();
}
// echo "Faculty ID: " . htmlspecialchars($_SESSION['faculty_id']);

$user_id = $_SESSION['faculty_id'];
// Fetch user data
$user_data = fetch_user_data($user_id);

if ($user_data) {
  $first_name = $user_data['first_name'];
  $middle_name = $user_data['middle_name'];
  $last_name = $user_data['last_name'];
  $id_number = $user_data['id_number'];
  $profile_path = $user_data['profile_path'];
  $is_verified = $user_data['is_verified'];
  $adviser_code = $user_data['adviser_code'];
  $role = $user_data['role'];
} else {
  $first_name = '';
  $middle_name = '';
  $last_name = '';
  $id_number = '';
  $profile_path = '';
  $is_verified = 0; 
  $adviser_code = '';
  $role = '';
}

// Check if any of the required fields are not set
$show_modal = empty($first_name) || empty($last_name) || empty($id_number);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WMSU RMIS</title>
  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="static/js/jquery.min.js"></script>
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
                <style>
        .new-notification {
          background-color: #dee2e6;
}
      </style>
            </ul>
          <!-- End Notification Dropdown Items -->


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
        <a class="nav-link " href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- End Dashboard Nav -->

      <?php if ($is_verified == 1): ?>
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
            <a href="myupload.php">
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
      <?php endif; ?>



      <li class="nav-heading">Account</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>

      <!-- End Profile Page Nav -->
    </ul>
    <!-- End Components Nav -->

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb d-flex">
          <div class="d-flex">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </div>
          <li class="breadcrumb-item ms-auto">
            <select id="yearFilterchart" class="form-select">
              <option selected>Choose year...</option>
            </select>
          </li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

          <div class="col-xxl-4 col-md-6">
    <div class="card info-card sales-card student-card">
        <div class="card-body">
            <h5 class="card-title">Student Verification<span></span></h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <div class="ps-3">
                    <h6 id="student-verification">0</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .student-card {
        cursor: pointer;
    }
    .submission-card {
        cursor: pointer;
    }
    .uploaded-card {
        cursor: pointer;
    }
</style>

  


            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card submission-card">
                <div class="card-body">
                  <h5 class="card-title">Submission Review<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="toreview-count">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card uploaded-card">
                <div class="card-body">
                  <h5 class="card-title">Uploaded<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-upload"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="uploaded-count">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <style>
            .chartjs-wrap {
    word-wrap: break-word;
    width: 100%;
    overflow: hidden;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 400px; /* Adjust as needed */
}

            </style>
            <div class="card">
  <div class="card-body">
    <h5 class="card-title">Research Metrics</h5>
    <!-- Add fixed height container -->
    <div style="height: 300px;"> 
    <canvas id="metricsChart" class="chartjs-wrap"></canvas>
    </div>
  </div>
</div>


            <!-- End dashboard card -->
          </div>
        </div>

        <!-- End Left side columns -->

        <!-- Profile modal Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Set Your Profile</h5>
              </div>
              <div class="modal-body">
                <form id="adviser-profile" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">


                  <div class="mb-3 text-center">
    <div class="profile-picture-wrapper">
        <img src="<?php echo !empty($profile_path) ? htmlspecialchars($profile_path) : 'static/img/placeholder.jpg'; ?>" 
             alt="Profile Picture"
             class="profile-picture"
             onerror="this.src='static/img/placeholder.jpg';">
    </div>
</div>

<style>
    .profile-picture-wrapper {
        display: inline-block;
        width: 100px;
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
        border: 2px solid #ddd;
    }
    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <div class="pt-2">
                        <input type="file" name="profile_image" id="profile_image">
                      </div>
                       <small class="form-text text-muted">Max size 2MB</small>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="firstName" type="text" class="form-control" id="firstName"
                      value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" name="middleName" type="text" class="form-control" id="middleName"
                      value="<?php echo htmlspecialchars($middle_name ?? ''); ?>">
                  </div>

                  <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="lastName" type="text" class="form-control" id="lastName"
                      value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="id_number" class="form-label">ID No.</label>
                    <input type="text" name="id_number" type="text" class="form-control" id="id_number"
                      value="<?php echo htmlspecialchars($id_number ?? ''); ?>" required>
                  </div>

      
               
  <div class="row mb-3">
    <label for="code" class="col-md-4 col-lg-3 col-form-label">Submission Code</label>
    <div class="col-md-8 col-lg-9">
      <input name="code" type="text" class="form-control" id="code"
        value="<?php echo htmlspecialchars($adviser_code ?? ''); ?>" maxlength="8" pattern="\d{1,8}"
        title="Please enter exactly 8 digits" placeholder="Set your submission code">
    </div>
  </div>


                  <button type="submit" class="btn btn-primary">Save Profile</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script>
          $(document).ready(function () {
            // Show the modal if any of the required fields are not set
            var showModal = <?php echo json_encode($show_modal); ?>;
            if (showModal) {
              $('#profileModal').modal({
                backdrop: 'static',
                keyboard: false
              });
              $('#profileModal').modal('show');
            }

          });
        </script>





        <!-- Right side columns -->
        <div class="col-lg-4">
          <!-- Recent Published -->
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Latest Uploads<span></span></h5>
              <div class="activity" id="latest-uploads">
                <!-- Latest uploads will be dynamically inserted here -->
              </div>
            </div>
          </div>

          <!-- <div class="card">
            <div class="card-body">
              <h5 class="card-title">Most engaging<span></span></h5>
              <div class="activity">
                <div class="activity-item d-flex">
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    <a href="../Chapt-1-2.pdf" target="_blank">Title 1</a>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
        <!-- End Recent Activity -->

      </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 600px;">
    <div class="copyright">
      <H2 style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>


      <script src="static/js/sweetalert2.all.min.js"></script>

  <!-- Vendor JS Files -->
  <script src="../static/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../static/vendor/aos/aos.js"></script>
  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../static/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../static/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="../static/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="static/js/main.js"></script>
  <script src="static/js/adviser-profile.js"></script>
  <script src="static/js/faculty-dashboard.js"></script>



</body>

</html>