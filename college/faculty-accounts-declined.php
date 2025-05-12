<?php
include 'php-functions/fetch-faculty-accounts.php';
include 'php-functions/dbconnection.php';
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['email']) || !isset($_SESSION['college_code'])) {
  header("Location: ../collegeadmin.php");
  exit();
}

$college_code = $_SESSION['college_code'];

// SQL query to fetch college name
$sql = "
    SELECT c.college_name, ca.image_path
    FROM college_account ca
    JOIN colleges c ON ca.college_code = c.college_code
    WHERE ca.college_code = ?
";

if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("i", $college_code);
  $stmt->execute();
  $stmt->bind_result($college_name, $image_path);
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

  <title>WMSU RMIS</title>



  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../static/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../static/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../static/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../static/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="static/css/dashboard-style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
      <a href="college-dashboard.php" class="logo d-flex align-items-center">
        <img src="static/img/wmsu-crop.jpg" alt="">
        <span class="d-none d-lg-block">COLLEGE RMIS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

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
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"></span>
          </a>
          <!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($college_name); ?></h6>
              <span>Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
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
        <a class="nav-link collapsed" href="college-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#department-information-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-file-text"></i><span>Department</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="department-information-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="faculty-information.php">
              <i class="bi bi-circle"></i><span>Department Code List</span>
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
        <a class="nav-link collapse" data-bs-target="#facultyaccount-nav" data-bs-toggle="collapse" href="">
          <i class="bi bi-people-fill"></i><span>Faculty Accounts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="facultyaccount-nav" class="nav-content collapsed" data-bs-parent="#sidebar-nav">
          <li>
            <a href="faculty-accounts-waiting.php">
              <i class="bi bi-circle" ></i><span>Waiting</span>
            <a href="faculty-accounts-accepted.php">
              <i class="bi bi-circle"></i><span>Verified</span>
            </a>
            <a href="faculty-accounts-declined.php"   class="active">
              <i class="bi bi-circle"></i><span>Declined</span>
            </a>
          </li>
        </ul>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed " href="college-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>


      <!-- End Profile Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="college-dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Faculty Accounts</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="table-responsive">
          <div class="form-group mb-4">
            <label for="departmentSelect">Select Faculty:</label>
            <select class="form-control" id="departmentSelect">
                <option value="">All Faculty</option>
                <?php
                if ($departments) {
                    foreach ($departments as $department) {
                        echo "<option value='" . htmlspecialchars($department['department_code']) . "'>" . htmlspecialchars($department['department_name']) . "</option>";
                    }
                }
                ?>
            </select>
            <input type="hidden" id="collegeCode" value="<?php echo htmlspecialchars($college_code); ?>">
        </div>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Email</th>
                  <th scope="col">Account Creation</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody id="adviserAccountsTableBodydeclined">
              </tbody>
            </table>

          </div>
        </div>
        <!-- End Left side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 500px;">
    <div class="copyright">
      <H2 style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

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
  <script src="static/js/faculty-accounts-declined.js"></script>

</body>

</html>