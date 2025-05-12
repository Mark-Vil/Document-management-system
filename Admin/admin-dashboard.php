<?php
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

  <title>ADMIN DASHBOARD</title>
  
  <script src="../static/js/sweetalert2.all.min.js"></script>
  <script src="../static/js/jquery.min.js"></script>

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
      <a href="admin-dashboard.php" class="logo d-flex align-items-center">
        <img src="../static/img/wmsu-crop.jpg" alt="">
        <span class="mr-4">ADMIN</span>
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
        <a class="nav-link " href="admin-dashboard.php">
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
        <a class="nav-link collapsed" href="admin-profile.php">
          <i class="bi bi-person"></i>
          <span>Account</span>
        </a>
      </li>
      
      <!-- End Profile Page Nav -->
    </ul>

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
                <option value="" selected disabled>Choose year...</option>
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
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Total colleges<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-building"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="totalColleges">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Total Departments<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-building"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="totalDepartments">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Total Documents<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="ps-3">
                    <h6 id="totalDocuments">0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            

           

          </div>
        </div>

       
        <!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">
          <!-- Recent Published -->
          
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">College with most Views<span></span></h5>
              <div class="activity">
                <div class="activity-item d-flex">
                  <div class="most-viewed-college">
                                
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="card">
            <div class="card-body">
              <h5 class="card-title">College with most Downloads<span></span></h5>
              <div class="activity">
                <div class="activity-item d-flex">
                <div class="most-downloaded-college">
                </div>
              </div>
            </div>
          </div>

          



        </div>
      </div>

      <div class="col-lg-12">
          <div class="row">

        <div class="card-body mb-4" style="width: 450px; height: 350px;">
              <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title">College With Highest Total <span></span><i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="This includes pending and on process documents"></i></h5>
              </div>
              <canvas id="college-total"></canvas>
            </div>

            <div class="card-body" style="width: 450px; height: 350px;">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">Department With Highest Total <span></span><i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="This includes pending and on process documents"></i></h5>
              </div>
              <canvas id="faculty-total"></canvas>
            </div>

            </div>
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

  <!-- Vendor JS Files -->

  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


  <!-- Template Main JS File -->
  <script src="static/js/admin-main.js"></script>
  <script src="static/js/admin-graph.js"></script>
  <script src="static/js/admin-dashboard.js"></script>
</body>

</html>