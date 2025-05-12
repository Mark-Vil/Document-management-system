<?php
include 'php-functions/dbconnection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['college_code'])) {
    header("Location: ../collegeadmin.php");
    exit();
}

$college_code = $_SESSION['college_code'];

// SQL query to fetch college name, college code, and email
$sql = "
    SELECT c.college_name, ca.college_code, ca.email, ca.image_path
    FROM college_account ca
    JOIN colleges c ON ca.college_code = c.college_code
    WHERE ca.college_code = ?
";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $college_code);
    $stmt->execute();
    $stmt->bind_result($college_name, $college_code, $email,  $image_path);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Failed to prepare statement: " . $conn->error;
    exit();
}

if ($image_path && strpos($image_path, '../') === 0) {
  $image_path = substr($image_path, 3);
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
  <link href="static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="static/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="static/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="static/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="static/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="static/css/dashboard-style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="adviser-dashboard.php" class="logo d-flex align-items-center">
        <img src="static/img/wmsu-crop.jpg" alt="">
        <span class="d-none d-lg-block">COLLEGE RMIS</span>
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
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($college_name); ?></h6>
              <span>Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="college-profile.php" class="active">
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
        <a class="nav-link collapsed" href="college-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#department-information-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-file-text"></i><span>Department</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="department-information-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="faculty-information.php">
              <i class="bi bi-circle"></i><span>Department Code list</span>
            </a>
            
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#dashboard-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-info-circle"></i><span>Department Analytics</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="dashboard-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="faculty-documents.php" >
              <i class="bi bi-circle"></i><span>Documents</span>
            </a>
          </li>
        <li>
            <a href="analytics.php" >
              <i class="bi bi-circle"></i><span>Data Analytics</span>
            </a>
          </li>
        </ul>
      </li>
      
      

      <li class="nav-heading">Account</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#facultyaccount-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-people-fill"></i><span>Faculty Accounts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="facultyaccount-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="faculty-accounts-waiting.php">
              <i class="bi bi-circle" ></i><span>Waiting</span>
            <a href="faculty-accounts-accepted.php">
              <i class="bi bi-circle"></i><span>Verified</span>
            </a>
            <a href="faculty-accounts-declined.php">
              <i class="bi bi-circle"></i><span>Declined</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="college-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="student-profile.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-l-4 col-xl-5">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile" class="rounded-circle">
              <h3 id="changeProfileText" style="cursor: pointer; color: blue;">Change Profile</h3>

        <!-- Hidden Image Upload Form -->
        <form id="uploadProfileImageForm" enctype="multipart/form-data" style="display: none;">
            <div class="pt-2 d-flex justify-content-center">
                <input type="file" name="profile_image" id="profile_image" class="form-control">
            </div>
        </form>
              <h2><?php echo htmlspecialchars($college_name); ?></h2>
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
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>
                

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" >Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">
    <h5 class="card-title">Profile Details</h5>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">College</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($college_name ?? ''); ?></div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-4 label">College Code</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($college_code ?? ''); ?></div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-4 label">Email</div>
        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($email ?? ''); ?></div>
    </div>
</div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form id="change-college-password">
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


  <script src="../static/js/jquery.min.js"></script>
  <script src="../static/js/sweetalert2.all.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="../static/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../static/vendor/chart.js/chart.umd.js"></script>
  <script src="../static/vendor/echarts/echarts.min.js"></script>
  <script src="../static/vendor/quill/quill.js"></script>
  <script src="../static/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../static/vendor/tinymce/tinymce.min.js"></script>
  <script src="../static/vendor/php-email-form/validate.js"></script>
  

  <!-- Template Main JS File -->
  <script src="static/js/college-main.js"></script>
  <script src="static/js/college-profile.js"></script>

</body>

</html>