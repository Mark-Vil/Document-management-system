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

$user_id = $_SESSION['student_id'];
// Fetch user data
$user_data = fetch_user_data($user_id);

if ($user_data) {
    $first_name = $user_data['first_name'];
    $middle_name = $user_data['middle_name'];
    $last_name = $user_data['last_name'];
    $id_number = $user_data['id_number'];
    $profile_path = $user_data['profile_path'];
    $email = $user_data['email'];
    $status = $user_data['status'];
    $is_emailverified = $user_data['is_emailverified'];
    $is_verified = $user_data['is_verified'];
    $department = $user_data['department'];
    $college = $user_data['college'];
    $role = $user_data['role'];
} else {
    $first_name = '';
    $middle_name = '';
    $last_name = '';
    $id_number = '';
    $profile_path = '';
    $email = '';
    $status = '';
    $is_emailverified = '';
    $is_verified = 0;
    $department = '';
    $college = '';
    $role = '';
}

$departments = fetch_departments();
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

        </li><!-- End Messages Nav -->

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
      
      <!-- End Components Nav -->

      

      <li class="nav-heading">Account</li>

      <li class="nav-item">
        <a class="nav-link" href="student-profile.php" class="active">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->
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
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="<?php echo htmlspecialchars($profile_path); ?>" alt="Profile" class="rounded-circle">
              <h2><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h2>
              <h3><?php echo htmlspecialchars($role); ?></h3>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" >Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($first_name . ' ' . $middle_name . ' ' . $last_name); ?></div>
                  </div>


                  <div class="row">
    <div class="col-lg-3 col-md-4 label">ID No.</div>
    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($id_number ?? ''); ?></div>
</div>

                  <div class="row">
    <div class="col-lg-3 col-md-4 label">College</div>
    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($college ?? ''); ?></div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 label">Department</div>
    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($department ?? ''); ?></div>
</div>
    
<div class="row">
<div class="col-lg-3 col-md-4 label">Email</div>
<div class="col-lg-9 col-md-8 d-flex flex-column email-data" data-email="<?php echo htmlspecialchars($email ?? ''); ?>">
    <div class="d-flex align-items-center">
        <span><?php echo htmlspecialchars($email ?? ''); ?></span>
        <?php if ($is_emailverified == 1): ?>
            <span class="bi bi-check-lg" style="color: green; margin-left: 10px;"></span>
        <?php endif; ?>
    </div>
    <?php if ($is_emailverified == 0): ?>
        <div class="d-flex align-items-center" style="margin-top: 10px;">
            <button id="verify-email-btn" class="btn btn-warning" style="margin-right: 10px;">Verify Email</button>
            <button id="change-email-btn" class="btn btn-warning">Change Email</button>
        </div>
    <?php endif; ?>
</div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 label">Account Status</div>
    <div class="col-lg-9 col-md-8">
    <div class="d-flex flex-column align-items-start">
        <?php if ($is_verified == 1): ?>
          <span style="color: green;">Verified</span>
          <?php elseif ($status === "Waiting"): ?>
    Waiting
    <div class="mt-2">
        <button id="resubmit-account-btn" class="btn btn-warning">Resubmit</button>
    </div>
<?php elseif ($status === "Rejected"): ?>
    Rejected
    <div class="mt-2">
        <button id="resubmit-account-btn" class="btn btn-warning">Resubmit</button>
    </div>
<?php endif; ?>
    </div>
    <div class="d-flex align-items-center">
        <?php if ($is_verified == 0 && (is_null($status) || $status === '')): ?>
            <button id="verify-account-btn" class="btn btn-warning">Verify</button>
        <?php endif; ?>
    </div>
</div>
</div>

</div>

<!-- Advisor Code and File Upload Modal -->
<div class="modal fade" id="advisorCodeModal" tabindex="-1" aria-labelledby="advisorCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="advisorCodeModalLabel">Verify Your Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="advisorCodeForm">
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['student_id']; ?>">
          <div class="mb-3">
            <label for="advisorCode" class="form-label">Adviser Submission Code</label>
            <input type="text" class="form-control" id="advisorCode" name="advisorCode" placeholder="Enter your advisor code"  max="99999999"required>
          </div>
          <div class="mb-3">
            <label for="verificationFile" class="form-label">Upload your COR in PDF</label>
            <input type="file" class="form-control" id="verificationFile" name="verificationFile" required>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

                
                <!-- Change Email Modal -->
<div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeEmailModalLabel">Change Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="changeEmailForm">
          <div class="mb-3">
            <label for="newEmail" class="form-label">New Email</label>
            <input type="email" class="form-control" id="newEmail" name="newEmail" required>
          </div>
          <button type="submit" class="btn btn-primary">Change Email</button>
        </form>
      </div>
    </div>
  </div>
</div>

                    <!-- OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">Enter OTP Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="otpForm">
          <div class="mb-3">
            <label for="otpCode" class="form-label">OTP Code</label>
            <input type="text" class="form-control" id="otpCode" name="otpCode" required>
          </div>
          <button type="submit" class="btn btn-primary">Verify</button>
        </form>
      </div>
    </div>
  </div>
</div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form id="student-profile" enctype="multipart/form-data">
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="<?php echo htmlspecialchars($profile_path); ?>" alt="Profile">
                        <div class="pt-2">
                        <input type="file" name="profile_image" id="profile_image">
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="firstName" type="text" class="form-control" id="firstName" value="<?php echo htmlspecialchars($first_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="middleName" class="col-md-4 col-lg-3 col-form-label">Middle Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="middleName" type="text" class="form-control" id="middleName" value="<?php echo htmlspecialchars($middle_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lastName" type="text" class="form-control" id="lastName" value="<?php echo htmlspecialchars($last_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="idnumber" class="col-md-4 col-lg-3 col-form-label">ID No.</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="idnumber" type="text" class="form-control" id="idnumber" value="<?php echo htmlspecialchars($id_number ?? ''); ?>">
                      </div>
                    </div>

                    
                    <div class="row mb-3">
    <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
    <div class="col-md-8 col-lg-9">
        <select name="department" class="form-control" id="department">
            <option value="">Select a department</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?php echo htmlspecialchars($department['department_code']); ?>">
                    <?php echo htmlspecialchars($department['department_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>


                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                  
                  <!-- End Profile Edit Form -->

                </div>
                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form id="change-student-password">
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

  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>
  
  <!-- Vendor JS Files -->
  <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  

  <!-- Template Main JS File -->
  <script src="static/js/student-main.js"></>
  <script src="static/js/accounts.js"></script>
  <script src="static/js/studentprofile.js"></script>
  <script src="static/js/emailverification.js"></script>
   <script src="static/js/notification.js"></script>

</body>

</html>