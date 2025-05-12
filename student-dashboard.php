<?php
include 'php-functions/dbconnection.php';
include 'php-functions/check_userprofile.php';
include 'php-functions/fetch_departments.php';
session_start();

// Check if the session variables are set
if (!isset($_SESSION['student_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Redirect to the login page if the session is not set
    header("Location: index.php");
    exit();

}
// echo "Student ID: " . htmlspecialchars($_SESSION['student_id']);

$user_id = $_SESSION['student_id'];
// Fetch user data
$user_data = fetch_user_data($user_id);

if ($user_data) {
  $first_name = $user_data['first_name'];
  $middle_name = $user_data['middle_name'];
  $last_name = $user_data['last_name'];
  $id_number = $user_data['id_number'];
  $profile_path = $user_data['profile_path'];
  $is_verified = $user_data['is_verified'];
  $email = $user_data['email'];
  $department = $user_data['department'];
  $college = $user_data['college'];
  $role = $user_data['role'];
} else {
  $first_name = '';
  $middle_name = '';
  $last_name = '';
  $id_number = '';
  $profile_path = '';
  $is_verified = 0; // Default to 0 if user data is not found
  $email = '';
  $department = '';
  $college = '';
  $role = '';
}
$departments = fetch_departments();

// Check if any of the required fields are not set
$show_modal = empty($first_name) || empty($last_name) || empty($id_number) || empty($profile_path) || empty($department);
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="static/js/jquery.min.js"></script>

    <!-- Vendor CSS Files -->
    <link href="static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="static/vendor/remixicon/remixicon.css" rel="stylesheet">
  
    <!-- Template Main CSS File -->
    <link href="static/css/dashboard-style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="content.php" class="logo d-flex align-items-center">

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
            </a>
            <!-- End Profile Iamge Icon -->
  
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h6>
              <span><?php echo htmlspecialchars($role); ?></span>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
  
              <li>
                <a class="dropdown-item d-flex align-items-center" href="student-profile.php">
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
                <a class="dropdown-item d-flex align-items-center"  href="php-functions/logout.php">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Sign Out</span>
                </a>
              </li>
  
            </ul>
            <!-- End Profile Dropdown Items -->
          </li>
          
          <!-- End Profile Nav -->
  
        </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="student-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
    

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
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#submissions-nav" data-bs-toggle="collapse" href="">
            <i class="bi bi-check-circle"></i><span>Submissions</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="submissions-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="mysubmission.php">
                    <i class="bi bi-circle"></i><span>Accepted</span>
                </a>
                <a href="rejected.php">
                    <i class="bi bi-circle"></i><span>Rejected</span>
                </a>
                <a href="status.php">
                    <i class="bi bi-circle"></i><span>Pending</span>
                </a>
            </li>
        </ul>
    </li>
    <?php endif; ?>
    
      <li class="nav-heading">Account</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="student-profile.php">
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

<style>
    .submitted-card {
        cursor: pointer;
    }
    .accepted-card {
        cursor: pointer;
    }
    .pending-card {
        cursor: pointer;
    }
</style>
    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">
          
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card submitted-card">
                <div class="card-body">
                  <h5 class="card-title">Submitted<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-upload"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="submitted-count">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card accepted-card">
                <div class="card-body">
                  <h5 class="card-title">Accepted<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="accepted-count">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card pending-card">
                <div class="card-body">
                  <h5 class="card-title">Pending<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="pending-count">0</h6>
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


        <!-- Right side columns -->
        <div class="col-lg-4">
          <!-- Recent Published -->
          <div class="card mb-3">
        
            <div class="card-body">
              <h5 class="card-title">Latest Upload<span></span></h5>
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

        </div>
        <!-- End Right side columns -->


        <!-- Profile modal Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Update Your Profile</h5>
                </div>
                <div class="modal-body">
                    <form id="student-profile" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" name="firstName" type="text" class="form-control" id="firstName" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
</div>
<div class="mb-3">
    <label for="middle_name" class="form-label">Middle Name</label>
    <input type="text" name="middleName" type="text" class="form-control" id="middleName" value="<?php echo htmlspecialchars($middle_name ?? ''); ?>">
</div>
<div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" name="lastName" type="text" class="form-control" id="lastName" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
</div>
<div class="mb-3">
    <label for="idnumber" class="form-label">ID No.</label>
    <input type="number" name="idnumber" class="form-control" id="idnumber" value="<?php echo htmlspecialchars($id_number ?? ''); ?>" required>
</div>

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
    <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
        <select name="department" class="form-control" id="department">
            <option value="">Select a department</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?php echo htmlspecialchars($department['department_code']); ?>">
                    <?php echo htmlspecialchars($department['department_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
            </div>


                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
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

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 600px;">
    <div class="copyright">
      <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="static/js/sweetalert2.all.min.js"></script>


  <!-- Vendor JS Files -->
  <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  
  <!-- Template Main JS File -->
  <script src="static/js/student-main.js"></script>
  <script src="static/js/studentprofile.js"></script>
  <script src="static/js/student-dashboard.js"></script>
  <script src="static/js/notification.js"></script>

</body>

</html>