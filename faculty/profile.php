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
  $college_name = $user_data['college_name'];
  $department_name = $user_data['department_name'];
  $email = $user_data['email'];
  $adviser_code = $user_data['adviser_code'];
  $is_emailverified = $user_data['is_emailverified'];
  $role = $user_data['role'];
} else {
  $first_name = '';
  $middle_name = '';
  $last_name = '';
  $id_number = '';
  $college_name = '';
  $department_name = '';
  $profile_path = '';
  $is_verified = 0; 
  $email = $user_data['email'];
  $adviser_code = '';
  $is_emailverified = '';
  $role = '';

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WMSU - RMIS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

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
              <a class="dropdown-item d-flex align-items-center" href="profile.php" class="active">
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
        <a class="nav-link collapsed" href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
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
            <a href="myupload.php" >
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
        <a class="nav-link" href="profile.php" class="active">
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
          <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
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
                  <button class="nav-link active" data-bs-toggle="tab"
                    data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>



                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change
                    Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?php
                    echo htmlspecialchars(
                      ($first_name ?? '') . ' ' .
                      ($middle_name ?? '') . ' ' .
                      ($last_name ?? '')
                    );
                    ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">ID NO.</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($id_number ?? ''); ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">College</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($college_name ?? ''); ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Department</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($department_name ?? ''); ?></div>
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
                    <div class="col-lg-3 col-md-4 label">Submission Code</div>
                    <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($adviser_code ?? ''); ?></div>
                  </div>
                  <div class="row">
    <div class="col-lg-3 col-md-4 label">Account Status</div>
    <div class="col-lg-9 col-md-8">
            <?php if ($is_verified == 1): ?>
                <span style="color: green;">Verified</span>
            <?php else: ?>
                <span style="color: red;">Not verified</span>
            <?php endif; ?>
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
                  <form id="adviser-profile" enctype="multipart/form-data">
                    <input type="hidden" name="faculty_id" value="<?php echo $_SESSION['faculty_id']; ?>">
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
                      <label for="firstName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="firstName" type="text" class="form-control" id="firstName"
                          value="<?php echo htmlspecialchars($first_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="middleName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="middleName" type="text" class="form-control" id="middleName"
                          value="<?php echo htmlspecialchars($middle_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="lastName" type="text" class="form-control" id="lastName"
                          value="<?php echo htmlspecialchars($last_name ?? ''); ?>">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="id_number" class="col-md-4 col-lg-3 col-form-label">ID NO.</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="id_number" type="text" class="form-control" id="id_number"
                          value="<?php echo htmlspecialchars($id_number ?? ''); ?>">
                      </div>
                    </div>

                      <div class="row mb-3">
                        <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Submission Code</label>
                        <div class="col-md-8 col-lg-9">
                          <input name="code" type="text" class="form-control" id="code"
                            value="<?php echo htmlspecialchars($adviser_code ?? ''); ?>">
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
                  <form id="change-faculty-password">
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
                      <label for="confirmPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New
                        Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="confirmPassword" type="password" class="form-control" id="confirmPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn" style="background-color: rgb(255,0,51); color: white">Change
                        Password</button>
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
      <H2 style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>


  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>

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
  <script src="static/js/accounts.js"></script>
  <script src="static/js/adviser-profile.js"></script>
  <script src="static/js/emailverification.js"></script>

</body>

</html>