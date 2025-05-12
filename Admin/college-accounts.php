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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

  <!-- Vendor CSS Files -->
  <link href="../static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="../static/js/sweetalert2.all.min.js"></script>
  <script src="../static/js/jquery.min.js"></script>
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
              <a class="dropdown-item d-flex align-items-center" href="#">
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
            <a href="department-faculty.html">
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
      

      <li class="nav-heading">Accounts</li>
      <li class="nav-item">
        <a class="nav-link" href="college-accounts.php" class="active">
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
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">College Accounts</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

          <div class="col-lg-12 mb-4">
              <div class="faculty mb-4" style="padding: 10px; border: 1px solid #943f3f; border-radius: 10px;">
              <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="mb-0">COLLEGE LIST</h2>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCodeModal">Add</button>
              </div>
                <div class="table-responsive" style="max-height: 300px;">
                <table class="table table-striped">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">COLLEGE NAME</th>
            <th scope="col">Code</th>
            <th scope="col">Creation Date</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th scope='row'></th>";
                echo "<td>" . htmlspecialchars($row['college_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['college_code']) . "</td>";
                echo "<td>" . htmlspecialchars($row['creation_date']) . "</td>";
                echo "<td style='color: " . ($row['status'] == 'Active' ? 'green' : 'red') . ";'>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>";
                echo "<div class='dropdown'>";
                echo "<button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>Actions</button>";
                echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                echo "<li><button class='dropdown-item edit-btn' data-id='" . $row['college_code'] . "' data-name='" . $row['college_name'] . "'>Edit</button></li>";
                if ($row['status'] == 'No Account') {
                    echo "<li><button class='dropdown-item delete-btn' data-id='" . $row['college_code'] . "'>Delete</button></li>";
                }
                echo "</ul>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No colleges found</td></tr>";
        }
        ?>
    </tbody>
</table>
              </div>
            </div>
            </div>

            <div class="col-lg-12 mb-4">
    <div class="faculty mb-4" style="padding: 10px; border: 1px solid #943f3f; border-radius: 10px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>COLLEGE ACCOUNTS</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accountModal">Create</button>
        </div>
        <div class="table-responsive" style="height: 250px;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">College</th>
                        <th scope="col">Creation Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
if ($collegeAccounts) {
    // Fetch all rows and display them in the table
    foreach ($collegeAccounts as $row) {
        echo "<tr>";
        echo "<th scope='row'></th>";
        echo "<td>" . htmlspecialchars($row['college_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['creation_date']) . "</td>";

        // Determine the color based on the status
        $status = htmlspecialchars($row['status']);
        $color = '';
        if ($status == 'Active') {
            $color = 'green';
        } elseif ($status == 'Deactivated') {
            $color = 'red';
        }

        // Display the status with the appropriate color
        echo "<td class='status' style='color: $color;'>" . $status . "</td>";

        echo "<td>";
echo "<div class='dropdown'>";
echo "<button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>";
echo "Actions";
echo "</button>";
echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";

echo "<li><button class='dropdown-item btn btn-info details-btn' data-college-code='" . htmlspecialchars($row['college_code']) . "'>Details</button></li>";

if ($row['status'] == 'Waiting') {
    echo "<li><button class='dropdown-item btn btn-success approve-btn' data-college-code='" . htmlspecialchars($row['college_code']) . "'>Approve</button></li>";
    echo "<li><button class='dropdown-item btn btn-danger reject-btn' data-college-code='" . htmlspecialchars($row['college_code']) . "'>Reject</button></li>";
} elseif ($row['status'] == 'Active') {
    echo "<li><button class='dropdown-item btn btn-danger deactivate-btn' data-college-code='" . htmlspecialchars($row['college_code']) . "'>Deactivate</button></li>";
} elseif ($row['status'] == 'Deactivated') {
    echo "<li><button class='dropdown-item btn btn-success approve-btn' data-college-code='" . htmlspecialchars($row['college_code']) . "'>Activate</button></li>";
} else {
    echo "<li><button class='dropdown-item btn btn-danger'>Unknown Status</button></li>";
}

echo "</ul>";
echo "</div>";
echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

          </div>
        </div>

        <!-- Edit College Modal -->
<div class="modal fade" id="editCollegeModal" tabindex="-1" aria-labelledby="editCollegeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCollegeModalLabel">Edit College</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCollegeForm">
                    <div class="mb-3">
                        <label for="editCollegeName" class="form-label">College Name</label>
                        <input type="text" class="form-control" id="editCollegeName" name="college_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCollegeCode" class="form-label">College Code</label>
                        <input type="text" class="form-control" id="editCollegeCode" name="college_code" required maxlength="8">
                    </div>
                    <input type="hidden" id="originalCollegeCode" name="original_college_code">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
        
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">College Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>College Name:</strong> <span id="collegeName"></span></p>
        <p><strong>Email:</strong> <span id="collegeEmail"></span></p>
        <p><strong>Status:</strong> <span id="collegeStatus"></span></p>
        <p><strong>Faculty:</strong></p>
        <ul id="departmentList"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Details Modal -->


        <!-- End Left side columns -->

  
        
        <!-- Modal -->
  <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accountModalLabel">Create College Admin Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="create-admin-account">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" id="email" class="form-control" required>
            </div>
          </div>
          <div class="mb-3" style="position: relative;">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" id="password" class="form-control" required>
              <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>
          </div>
          <div class="mb-3" style="position: relative;">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-key"></i></span>
              <input type="password" id="confirm_password" class="form-control" required>
              <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            </div>
          </div>
          <div class="mb-3">
            <label for="collegeCode" class="form-label">College account for</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-university"></i></span>
              <select class="form-control" id="confirmcollegeCode" name="confirmcollegeCode" required>
                <option value="">Select College</option>
              </select>
            </div>
          </div>
          <div class="d-grid gap-2">
            <button type="button" id="create-account-btn" class="btn btn-primary">Create Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



        <!-- ======= ADD COLLEGE CODE MODAL ======= -->
        <div class="modal fade" id="addCodeModal" tabindex="-1" aria-labelledby="addCodeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCodeModalLabel">Add College Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addCodeForm">
              <div class="mb-3">
                <label for="collegeName" class="form-label">College Name</label>
                <input type="text" class="form-control" id="collegeName" name="collegeName" required>
              </div>
              <div class="text-end">
            <button type="submit" class="btn btn-primary">Done</button>
          </div>
            </form>
          </div>
        </div>
      </div>
    </div>
        <!-- ======= ADD COLLEGE CODE MODAL ======= -->

        <style>
        .chart-container {
            position: relative;
            width: 350px; /* Set the desired width */
            height: 350px; /* Set the desired height */
        }
    </style>
        <!-- Right side columns -->
        <div class="col-lg-4">
                <div class="chart-container">
                    <canvas id="usersPerCollegeChart"></canvas>
                </div>

                <div class="chart-container">
                    <canvas id="facultyPerCollegeChart"></canvas>
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

   <!-- Vendor JS Files -->

  <script src="../static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <!-- Template Main JS File -->
  <script src="static/js/admin-main.js"></script>
  <script src="static/js/college-accounts.js"></script>


</body>

</html>