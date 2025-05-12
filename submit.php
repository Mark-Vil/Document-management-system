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

// Redirect based on is_verified status
if ($is_verified == 0) {
    header("Location: student-dashboard.php");
    exit();
}

$departments = fetch_departments();

// Check if any of the required fields are not set
$show_modal = empty($first_name) || empty($last_name) || empty($id_number) || empty($department);
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
  <link href="static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="static/vendor/remixicon/remixicon.css" rel="stylesheet">

  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>


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
        <a class="nav-link collapsed" href="student-dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapse" data-bs-target="#publish-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-upload"></i><span>Upload</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="publish-nav" class="nav-content collapsed" data-bs-parent="#sidebar-nav">
          <li>
            <a href="submit.php" class="active">
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

      <li class="nav-heading">Account</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="student-profile.php">
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
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Upload</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <!-- Account details card-->
          <div class="card mb-4">
            <div class="card-header">Submission Details</div>
            <div class="card-body">
              <form id="submission">
                <!-- Form Group Researc Title-->
                <div class="mb-3">
                  <label class="small mb-1" for="researchtitle">Research Title</label>
                  <input class="form-control" id="researchtitle" name="researchtitle" type="text"
                    placeholder="Enter Your Full Research Title" required>
                </div>
                <!-- Form Row-->
                <div class="row gx-3 mb-3">
                  <!-- Form Group (author)-->
                  <div class="col-md-6">
                    <label class="small mb-1" for="authorname">Author</label>
                    <input class="form-control" id="authorname" name="authorname" type="text" placeholder="Enter Your Full Name" required>
                  </div>
                  <!-- Form Group (co-author)-->
                  <div class="col-md-6">
                    <label class="small mb-1" for="coauthorname">Co-Author</label>
                    <div id="coauthor-container">
                      <input class="form-control mb-2" id="coauthorname" name="coauthorname[]" type="text"
                        placeholder="Enter Co-Author Full Name">
                    </div>
                    <button type="button" class="btn btn-primary" id="add-coauthor-btn" style=" background-color: #FF0033;
        border-color: #FF0033;">Add Co-Author</button>
                  </div>
                </div>
                <!-- Form Row        -->
                <div class="row gx-3 mb-3">
  <!-- Form Group (advisor code)-->
  <div class="col-md-6">
    <label class="small mb-1" for="advisorSelect">Adviser</label>
    <select class="form-control" id="advisorSelect" name="advisorSelect">
      <option value="">Research Adviser</option>
    </select>
  </div>
  <div class="col-md-6">
    <label class="small mb-1" for="advisorInput">
      Enter Adviser Submission Code
      <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Get your adviser submission code if you have a different adviser for this research." style="cursor: pointer; margin-left: 5px; color: #007bff;"></i>
    </label>
    <input type="text" class="form-control" id="advisorInput" name="advisorInput" placeholder="Enter Adviser Submission Code">
  </div>
</div>
                <!-- Form Group (research abstract)-->
                <div class="mb-3">
                  <label class="small mb-1" for="abstract">Research Abstract</label>
                  <textarea class="form-control" id="abstract" name="abstract" placeholder="Enter Your Research Abstract"
                    rows="4" required></textarea>
                </div>
                <!-- Form Row-->
                <div class="row gx-3 mb-3">
                  <!-- Form Group (keywords)-->
                
                  <div class="col-md-6">
                    <label class="small mb-1" for="addkeywords">Keywords</label>
                    <div id="addkeywords-container">
                      <input class="form-control mb-2" id="addkeywords" name="addkeywords[]" type="text"
                        placeholder="Enter Keywords" required>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-keywords-btn" style=" background-color: #FF0033;
        border-color: #FF0033;">Add keywords</button>
                  </div>


                  <!-- Form Group (file upload) -->
                  <div class="col-md-6">
    <label class="small mb-1" for="inputfile">Upload File(PDF Only)</label>
    <input class="form-control" id="inputfile" type="file" name="uploaded_file" accept=".pdf" required>
</div>
                </div>
                <!-- Save changes button-->
                <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" style="background-color: #FF0033; border-color: #FF0033; ">Submit</button>
    </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      </div>

        <!-- Bootstrap Modal -->
  <div class="modal fade" id="advisorModal" tabindex="-1" aria-labelledby="advisorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="advisorModalLabel">Confirm Submission</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="advisor-info"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="confirmSubmitButton">Submit</button>
        </div>
      </div>
    </div>
  </div>
      <!-- End Left side columns -->


      <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Set Your Profile</h5>
                </div>
                <div class="modal-body">
                    <form id="student-profile" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" name="firstName" type="text" class="form-control" id="firstName" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
</div>
<div class="mb-3">
    <label for="middle_name" class="form-label">Middle Name</label>
    <input type="text" name="middleName" type="text" class="form-control" id="middleName" value="<?php echo htmlspecialchars($middle_name ?? ''); ?>" required>
</div>
<div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" name="lastName" type="text" class="form-control" id="lastName" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
</div>
<div class="mb-3">
    <label for="idnumber" class="form-label">ID No.</label>
    <input type="number" name="idnumber" class="form-control" id="idnumber" value="<?php echo htmlspecialchars($id_number ?? ''); ?>" required>
</div>
<div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <div class="pt-2">
                        <input type="file" name="profile_image" id="profile_image">
                        </div>
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
  <footer id="footer" class="footer" style="margin-top: 350px;">
    <div class="copyright">
      <H2 style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

        <!-- Vendor JS Files -->
        <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  

  <!-- JS File -->
  <script src="static/js/main.js"></script>
  <script src="static/js/student-main.js"></script>
  <script src="static/js/student-script.js"></script>
  <script src="static/js/student-dashboard.js"></script>
  <script src="static/js/notification.js"></script>
</body>

</html>