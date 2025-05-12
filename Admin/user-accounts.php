<?php
include 'php-functions/dbconnection.php';
include 'php-functions/fetch-colleges-code.php';
include 'php-functions/fetch-college-account.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_email']) || !isset($_SESSION['admin_id'])) {
    header("Location: ../adminlogin.php");
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
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

    <script src="../static/js/sweetalert2.all.min.js"></script>
  <script src="../static/js/jquery.min.js"></script>

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
      <a href="admin-dashboard.php" class="logo d-flex align-items-center">
        <img src="../static/img/wmsu-crop.jpg" alt="">
        <span class="d-none d-lg-block">ADMIN</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">


        <li class="nav-item dropdown">

          


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
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="admin-profile.php">
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
        <a class="nav-link collapsed" href="admin-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#colleges" data-bs-toggle="collapse" href="#">
          <i class="bi bi-building"></i><span>College Documents</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="colleges" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="department-faculty.php">
              <i class="bi bi-circle"></i><span>College & Faculty</span>
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



      <li class="nav-heading">Accounts</li>
      <li class="nav-item">
        <a class="nav-link  collapsed" href="college-accounts.php" class="active">
          <i class="bi bi-people-fill"></i>
          <span>College Accounts</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user-accounts.php">
          <i class="bi bi-people"></i>
          <span>User Accounts</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="admin-profile.php">
          <i class="bi bi-person"></i>
          <span>Account</span>
        </a>
      </li>
    </ul>

  </aside>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">User Accounts</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <div class="row">
      <style>
    .highlight {
    background-color: yellow;
    font-weight: bold;
}
</style>

        <!-- Left side columns start-->
        <div class="col-lg-8">
            <!-- Search input start-->
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="usersearchField" placeholder="Search...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                </div>
            </div>
            <!-- Search input end-->

            <!-- User table start-->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">Email</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be appended here by jQuery -->
                    </tbody>
                </table>
            </div>
            <!-- User table end-->
            <div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- User info and research data will be injected here by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
            
        </div>
        <!-- Left side columns end-->



        <!-- Right side columns start-->
        <div class="col-lg-4">


        </div>
        <!-- Right side columns end-->

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

  <!-- Vendor JS Files -->

  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  

  <!-- Template Main JS File -->
  <script src="static/js/admin-main.js"></script>
  <script src="static/js/search-account.js"></script>

</body>

</html>