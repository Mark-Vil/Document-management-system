<?php
include 'php-functions/dbconnection.php';
include 'php-functions/fetch-submission-status.php';
include 'php-functions/check_userprofile.php';
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if the session variables are set
if (!isset($_SESSION['student_id']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    // Redirect to the login page if the session is not set
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['student_id'];
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
  header("Location: student-dashboard.php");
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
        <a class="nav-link collapsed" href="student-dashboard.php">
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
    </ul>
</li>

<li class="nav-item">
    <a class="nav-link collapse" data-bs-target="#submissions-nav" data-bs-toggle="collapse" href="">
        <i class="bi bi-check-circle"></i><span>Submissions</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="submissions-nav" class="nav-content collapsed" data-bs-parent="#sidebar-nav">
        <li>
        <a href="mysubmission.php">
                <i class="bi bi-circle" ></i><span>Accepted</span>
            </a>
            <a href="rejected.php">
                <i class="bi bi-circle"></i><span>Rejected</span>
            </a>
            <a href="status.php"  class="active">
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
          <li class="breadcrumb-item"><a href="student-dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Status</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
<div class="col-lg-12">
    <div class="table-responsive" style="height: 280px;">
      <table class="table table-striped">
      <thead>
    <tr>
        <th scope="col"></th>
        <th scope="col">Title</th>
        <th scope="col">View</th>
        <th scope="col">Upload Date</th>
        <th scope="col">Date Accepted</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    if ($submissions) {
        foreach ($submissions as $submission) {
            echo "<tr>";
            echo "<th scope='row'></th>";
            echo "<td>" . htmlspecialchars($submission['research_title']) . "</td>";
            echo "<td><button type='button' class='btn view-btn' data-bs-toggle='modal' data-bs-target='#viewModal' data-id='" . htmlspecialchars($submission['submission_id']) . "'><i class='bi bi-eye'></i></button></td>";
            echo "<td>" . htmlspecialchars($submission['dateofsubmission']) . "</td>";
            // Handle null for date_accepted
            $date_accepted = $submission['date_accepted'] ?? '';
            echo "<td>" . htmlspecialchars($date_accepted) . "</td>";
            echo "<td style='color: " . ($submission['status'] == 'Approved' ? 'green' : 'black') . ";'>" . htmlspecialchars($submission['status']) . "</td>";
            echo "<td>";
if ($submission['status'] == 'Revise') {
    echo "<div class='dropdown'>";
    echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>";
    echo "Actions";
    echo "</button>";
    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
    echo "<li><button class='dropdown-item btn btn-warning edit-btn' data-id='" . htmlspecialchars($submission['submission_id']) . "'>Edit</button></li>";
    echo "</ul>";
    echo "</div>";
} elseif ($submission['status'] == 'Updated') {
    echo "<div class='dropdown'>";
    echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>";
    echo "Actions";
    echo "</button>";
    echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
    echo "<li><button class='dropdown-item btn btn-info history-btn' data-id='" . htmlspecialchars($submission['id']) . "'>See history</button></li>";
    echo "</ul>";
    echo "</div>";
}
echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No Ongoing Submissions</td></tr>";
    }
    ?>
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
                    <div class="mb-3">
                        <label for="editCoAuthors" class="form-label">Co-Authors</label>
                        <input type="text" class="form-control" id="editCoAuthors" name="co_authors">
                    </div>
                    <div class="mb-3">
                        <label for="editAbstract" class="form-label">Abstract</label>
                        <textarea class="form-control" id="editAbstract" name="abstract" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editKeywords" class="form-label">Keywords</label>
                        <input type="text" class="form-control" id="editKeywords" name="keywords">
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
  <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- JS File -->
  <script src="static/js/main.js"></script>
  <script src="static/js/student-main.js"></script>
  <script src="static/js/student-script.js"></script>
  <script src="static/js/notification.js"></script>

</body>

</html>