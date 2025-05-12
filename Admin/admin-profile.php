<?php
include 'php-functions/dbconnection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_email']) || !isset($_SESSION['admin_id'])) {
    header("Location: ../adminlogin.php");
    exit();
}

// Fetch email from admin table based on session admin_id
$admin_id = $_SESSION['admin_id'];
$email = '';

$sql = "SELECT email FROM admin WHERE admin_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WMSU - RMIS</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Template Main CSS File -->
<link href="static/css/dashboard-style.css" rel="stylesheet">


</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="adviser-dashboard.php" class="logo d-flex align-items-center">
      <img src="../static/img/wmsu-crop.jpg" alt="">
      <span class="d-none d-lg-block">ADMIN</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">


        <li class="nav-item dropdown">

      

        </li><!-- End Notification Nav -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="static/img/user-profile-icon-free-vector_3.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>ADMIN</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="admin-profile.php" class="active">
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

          </ul>
        </li>
        <!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="admin-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#colleges" data-bs-toggle="collapse" href="#">
          <i class="bi bi-building"></i><span>College Documents</span><i class="bi bi-chevron-down ms-auto"></i>
        </a> 
        <ul id="colleges" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="department-faculty.php">
              <i class="bi bi-circle"></i><span>Colleges & Faculty</span>
            </a>
          </li>

        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#archive" data-bs-toggle="collapse" href="#">
          <i class="bi bi-file-earmark"></i><span>Archive</span><i class="bi bi-chevron-down ms-auto"></i>
        </a> 
        <ul id="archive" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="documents.php">
            <i class="bi bi-circle"></i><span>Find Documents</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- End Components Nav -->

      

      <li class="nav-heading">Accounts</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="college-accounts.php">
          <i class="bi bi-people-fill"></i>
          <span>College Accounts</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="user-accounts.php">
        <i class="bi bi-people"></i>
          <span>User Accounts</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapse" href="admin-profile.php">
          <i class="bi bi-person"></i>
          <span>Account</span>
        </a>
      </li>
      


    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Account</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-l-4 col-xl-5">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="static/img/user-profile-icon-free-vector_3.jpg" alt="Profile" class="rounded-circle">

        <!-- Hidden Image Upload Form -->
        <form id="uploadProfileImageForm" enctype="multipart/form-data" style="display: none;">
            <div class="pt-2 d-flex justify-content-center">
                <input type="file" name="profile_image" id="profile_image" class="form-control">
            </div>
        </form>
              <h2></h2>
              <h3>Admin</h3>
            </div>
          </div>

        </div>

        <div class="col-xl-7">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

  
              

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" >Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

    

                <div class="tab-pane fade show active pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form id="change-admin-password">
                  <input type="hidden" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="confirmPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="confirmPassword" type="password" class="form-control" id="confirmPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn" style="background-color: rgb(255,0,51); color: white">Change Password</button>
                    </div>
                  </form>
                  <!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 350px;">
    <div class="copyright">
      <H2  style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <script src="../static/js/sweetalert2.all.min.js"></script>
  <script src="../static/js/jquery.min.js"></script>
  <!-- Vendor JS Files -->

  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- Template Main JS File -->
  <script src="static/js/college-main.js"></script>
  <script src="static/js/admin-profile.js"></script>

</body>

</html>