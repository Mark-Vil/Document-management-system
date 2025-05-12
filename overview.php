<?php
include 'php-functions/dbconnection.php';
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$sessionSet = isset($_SESSION['email']) && isset($_SESSION['role']) && (isset($_SESSION['student_id']) || isset($_SESSION['faculty_id']));

// DASHBOARD LINKS //
$dashboardLink = '';
$logoutLink = '';
if (isset($_SESSION['student_id']) && $_SESSION['role'] === 'Student') {
  $dashboardLink = 'student-dashboard.php';
  $logoutLink = 'php-functions/logout.php';
} elseif (isset($_SESSION['faculty_id']) && $_SESSION['role'] === 'Faculty') {
  $dashboardLink = 'faculty/dashboard.php';
  $logoutLink = 'faculty/php-functions/logout.php';
}
function encrypt($data) {
  return base64_encode($data);
}
function decrypt($data) {
  return base64_decode($data);
}
// Redirect if no id parameter is present in the URL
if (!isset($_GET['id'])) {
  header("Location: content.php");
  exit;
}
// DASHBOARD LINKS //

// VERIFICATION CHECKING //
$isVerified = false;
if ($sessionSet && isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $sql = "SELECT is_verified FROM useraccount WHERE UserID = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($is_verified);
        $stmt->fetch();
        $stmt->close();
        $isVerified = ($is_verified == 1);
    }
}
// VERIFICATION CHECKING //

$id = decrypt($_GET['id']);

try {
  $sql = "
      SELECT 
          a.id,
          a.research_title,
          a.author,
          a.co_authors,
          a.abstract,
          a.keywords,
          a.file_path,
          a.UserID,
          a.faculty_code AS department_code,
          ss.date_accepted,
          ss.status,
          CONCAT(d.department_name, ' - ', c.college_name) AS department_college
      FROM 
          archive a
      JOIN 
          submission_status ss ON a.id = ss.submission_id
      JOIN 
          departments d ON a.faculty_code = d.department_code
      JOIN 
          colleges c ON d.college_code = c.college_code
      WHERE 
          a.id = ?
  ";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $archive = $result->fetch_assoc();

  if (!$archive) {
      echo '<p>Error: No archive found with the provided ID.</p>';
      exit;
  }

} catch (mysqli_sql_exception $e) {
  echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
  exit;
}

// Fetch college name and image path based on the archive ID
try {
  $college_sql = "
      SELECT 
          c.college_name,
          c.college_code,
          ca.image_path
      FROM 
          archive a
      JOIN 
          departments d ON a.faculty_code = d.department_code
      JOIN 
          colleges c ON d.college_code = c.college_code
      JOIN 
          college_account ca ON c.college_code = ca.college_code
      WHERE 
          a.id = ?
  ";
  
  $college_stmt = $conn->prepare($college_sql);
  $college_stmt->bind_param("i", $id);
  $college_stmt->execute();
  $college_result = $college_stmt->get_result();
  $colleges = $college_result->fetch_all(MYSQLI_ASSOC);

} catch (mysqli_sql_exception $e) {
  echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WMSU - RMIS</title>

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="static/vendor/aos/aos.css" rel="stylesheet">
  <link href="static/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="static/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="static/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="static/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="static/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="static/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="static/css/style.css" rel="stylesheet">

  <script type="text/javascript">
    var userID = '<?php echo $_SESSION['student_id'] ?? $_SESSION['faculty_id']; ?>';
    var researchID = '<?php echo $archive['id']; ?>';
    var archiveUserID = '<?php echo $archive['UserID']; ?>';
    var collegeCode = '<?php echo $colleges[0]['college_code']; ?>';
    var departmentCode = '<?php echo $archive['department_code']; ?>';
    var filePath = '<?php echo urlencode($archive['file_path']); ?>';
  </script>

</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <!-- Logo and Text Section -->
      <div class="d-flex align-items-center">
        <a href="content.php" class="logo me-2">
          <img src="static/img/wmsu-crop.jpg" alt="">
        </a>
        <div class="wmsu-text">
          <h3 style="
              color: white; 
              font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
              font-size: 22px; 
              margin: 0; 
              font-weight: 600; 
              letter-spacing: 0.1px;">
            WESTERN MINDANAO STATE UNIVERSITY
          </h3>
          <h5 style="
              color: white; 
              font-family: __esbuild_b38aaf, __esbuild_Fallback_b38aaf, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; 
              font-size: 14px; 
              margin: 0; 
              font-weight: 600; 
              letter-spacing: 1px;">
            RESEARCH REPOSITORY
          </h5>
        </div>
      </div>

      <!-- Navigation Section -->
      <div class="list-menu" style="z-index: 1000; border-color: white;">
        <i class="bi bi-list mobile-nav-toggle d-lg-none"></i>
        <nav id="navbar" class="navbar justify-content-center">
          <ul>
            <li><a class="nav-link scrollto" href="content.php">Home</a></li>
            <li><a class="nav-link scrollto active" href="content.php">Research Content</a></li>
            <?php if ($sessionSet): ?>
              <li><a class="nav-link scrollto" href="<?php echo $dashboardLink; ?>">Dashboard</a></li>
              <li><a class="getstarted scrollto" href="<?php echo $logoutLink; ?>">Logout</a></li>
            <?php else: ?>
              <li><a class="nav-link scrollto" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Dashboard</a></li>
              <li><a class="getstarted scrollto" data-bs-toggle="modal" data-bs-target="#loginModal" href="#">Login</a></li>
            <?php endif; ?>
          </ul>
          <i class="bi bi-x mobile-nav-toggle close-nav" style="display: none"></i>
        </nav>
      </div>
    </div>
  </div>
</header>
  <!-- End Header -->

    <!-- LOGIN MODAL -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title color-purple" id="loginModalLabel">Please Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="loginForm" method="post">
            <input type="hidden" name="next" id="next" value="">
            <div class="form-group">
              <label for="acc_name"></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend">
                    <i class="fas fa-user"></i>
                  </span>
                </div>
                <input type="email" name="email" class="form-control" id="email" placeholder="email"
                  style="font-family: Heebo, Arial, sans-serif!important;">
              </div>
            </div>
            <div class="form-group">
              <label for="acc_password"></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend">
                    <i class="fas fa-key"></i>
                  </span>
                </div>
                <div style="position: relative; flex-grow: 1;">
                  <input type="password" name="password" class="form-control" id="acc_password" placeholder="Password"
                    style="font-family: Heebo, Arial, sans-serif!important;">
                  <span toggle="#acc_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
              </div>
            </div>


            <div class="form-group text-center">
              <button type="submit" class="btn color-white mt-2"
                style="background-color: red; color: white;">LOGIN</button>
            </div>
            <div class="text-center mt-2">
              <a href="#" class="btn btn-outline-secondary">
                <img
                  src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png"
                  alt="Google logo" width="20" height="20">
                Sign in with Google
              </a>
            </div>
            <div class="text-center mt-2">
              <a href="forgot-password.php" style="color: black;">Forgot Password?</a>
              <a href="create-account.html" style="color: black;">Create Your Account</a>
            </div>
            <div class="text-center" id="container-msg"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
   <!-- LOGIN MODAL -->

  <main id="main">
    <!-- ======= About Section ======= -->
    <section id="about" class="about-section" style="margin-top: 50px; background-color: #f5f5f5;">
      <div class="custom-container">

        <style>
          .custom-container {
            max-width: 1400px;
            /* Adjust the max-width as needed */
            margin: 0 auto;
            /* Center the container */
            /* border: 1px solid black; */
          }

          .custom-left-column {
            width: 75%;
            /* Adjust the width as needed */
            float: left;
          }

          .custom-right-column {
            width: 25%;
            /* Adjust the width as needed */
            float: left;
          }

          .pdf-page {
            border: 1px solid black;
            /* Change the color and width as needed */
            margin-bottom: 10px;
            /* Optional: Add some space between pages */
          }
          .move-right {
            margin-left: 30px; /* Move the element to the right by 30px */
        }
        </style>
        <!-- Left side columns -->
        <div class="row">
          <div class="col-lg-8">
          <div class="card document-card">
  <div class="card-body document">
    <?php if ($archive['status'] === 'Locked'): ?>
      <i class="bi bi-lock" style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 1.5rem; color: #444444;" data-bs-toggle="tooltip" data-bs-placement="top" title="This document is locked by the author"></i>
    <?php endif; ?>
    <h2 class="text-center doc-research-title"><?php echo htmlspecialchars($archive['research_title']); ?></h2>
    
      <p class="card-text">
        <strong>Abstract:</strong> 
        <span><?php echo htmlspecialchars($archive['abstract']); ?></span>
      </p>
      <p class="card-text">
        <strong>Keywords:</strong> 
        <span><?php echo htmlspecialchars($archive['keywords']); ?></span>
      </p>

      <p class="card-text">
        <strong>Date:</strong> 
        <span><?php echo htmlspecialchars($archive['date_accepted']); ?></span>
      </p>
    
   
    <div class="d-flex justify-content-end">
      <?php if ($sessionSet && $isVerified && ($archive['status'] !== 'Locked' || $archive['UserID'] == $student_id)): ?>
        <button class="btn btn-primary me-2" onclick="event.stopPropagation(); window.location.href='pdf.html?file=<?php echo urlencode(encrypt($archive['file_path'])); ?>'">View PDF</button>
        <?php if ($archive['UserID'] != $student_id): ?>
          <button class="btn btn-secondary cite-btn me-2" onclick="event.stopPropagation(); showCitationModal('<?php echo htmlspecialchars($archive['author']); ?>', '<?php echo htmlspecialchars($archive['co_authors']); ?>', '<?php echo htmlspecialchars($archive['research_title']); ?>', '<?php echo htmlspecialchars($archive['date_accepted']); ?>')">Cite</button>
        <?php endif; ?>
        <button id="downloadButton" class="btn btn-success">Download</button>
      <?php endif; ?>
    </div>
  </div>
</div>

</div>


<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1" aria-labelledby="citationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="citationModalLabel">Citation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>APA</strong>
                <p id="apaCitation"></p>
                <strong>MLA</strong>
                <p id="mlaCitation"></p>
                <strong>Chicago</strong>
                <p id="chicagoCitation"></p>
            </div>
        </div>
    </div>
</div>

<script>
    function showCitationModal(author, coAuthors, title, date, pageNumber = 'p.') {
        const apaCitation = generateApaCitation(author, coAuthors, date, pageNumber);
        const mlaCitation = generateMlaCitation(author, coAuthors, pageNumber);
        const chicagoCitation = generateChicagoCitation(author, coAuthors, title, date);

        document.getElementById('apaCitation').innerText = apaCitation;
        document.getElementById('mlaCitation').innerText = mlaCitation;
        document.getElementById('chicagoCitation').innerText = chicagoCitation;

        const citationModal = new bootstrap.Modal(document.getElementById('citationModal'));
        citationModal.show();
    }

    function generateApaCitation(author, coAuthors, date, pageNumber) {
        const authors = formatAuthors(author, coAuthors, 'APA');
        return `${authors} (${new Date(date).getFullYear()}). ${pageNumber}`;
    }

    function generateMlaCitation(author, coAuthors, pageNumber) {
        const authors = formatAuthors(author, coAuthors, 'MLA');
        return `${authors}. ${pageNumber}`;
    }

    function generateChicagoCitation(author, coAuthors, title, date) {
        const authors = formatAuthors(author, coAuthors, 'Chicago');
        return `${authors}. "${title}," ${new Date(date).getFullYear()}.`;
    }

    function formatAuthors(author, coAuthors, style) {
        const authorLastName = author.split(' ').pop();
        const coAuthorsArray = coAuthors.split(',').map(name => name.trim().split(' ').pop());

        if (style === 'APA' || style === 'MLA') {
            return [authorLastName, ...coAuthorsArray].join(', ');
        } else if (style === 'Chicago') {
            const authorFullName = author;
            const coAuthorsFullNames = coAuthors.split(',').map(name => name.trim());
            return [authorFullName, ...coAuthorsFullNames].join(', ');
        }
    }
</script>

<!-- AUTHOR CARD -->
<div class="col-lg-4">
    <div class="card  document-card">
        <div class="card-body">
            <h4 class="card-title text-center mb-3" style="color: #AA0022;">Additional Details</h4>
            <p class="card-text">
                <strong>Author:</strong> 
                <span><?php echo htmlspecialchars($archive['author']); ?></span>
            </p>
            <p class="card-text">
                <strong>Co-authors:</strong>
                <span><?php echo htmlspecialchars($archive['co_authors']); ?></span>
            </p>
            <p class="card-text">
                <strong>Date Accepted:</strong>
                <span><?php echo htmlspecialchars($archive['date_accepted']); ?></span>
            </p>
            <p class="card-text">
                <strong>College & Faculty:</strong>
                <span><?php echo htmlspecialchars($archive['department_college']); ?></span>
            </p>
        </div>
    </div>
</div>
<!-- AUTHOR CARD -->

        </div>
      </div>
      <div class="custom-container mt-2">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="card-title mb-4" style="margin-left: 10px;">You may like to visit</h4>
            <style>
      .circle-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
}
.visit-college{
  width: 250px;
  margin-left: 10px;
  display: flex;
    flex-direction: column;
    align-items: center; /* Center the image horizontally */
    justify-content: center; /* Center the content vertically if needed */
    text-align: center;
}
    </style>
   <div class="row">
    <?php foreach ($colleges as $college): ?>
      <div class="col-lg-3 mb-3">
    <div class="visit-college d-flex justify-content-center">
        <?php 
        // Adjust the image path with null check
        $adjusted_image_path = $college['image_path'] ? str_replace('../', '', $college['image_path']) : '';
        // Encrypt the college code
        $encrypted_college_code = encrypt($college['college_code']);
        ?>
        <a href="college-view.php?code=<?php echo $encrypted_college_code; ?>">
            <img src="<?php echo !empty($adjusted_image_path) ? htmlspecialchars($adjusted_image_path) : 'static/img/wmsu-crop.jpg'; ?>" 
                 class="card-img-top circle-image" 
                 alt="<?php echo htmlspecialchars($college['college_name']); ?>">
            <div class="card-body">
                <h6 class="card-title text-center"><?php echo htmlspecialchars($college['college_name']); ?></h6>
            </div>
        </a>
    </div>
</div>
    <?php endforeach; ?>
</div>
<style>
    .card {
        cursor: pointer;
    }
    </style>
        </div>
        <div class="col-md-4 col-md-3" data-archive-id="<?php echo $archive['id']; ?>">
    <h4 class="card-title" style="margin-left: 10px; margin-bottom: 10px;">You might also like</h4>
    <div class="row flex-column recommendations">
    </div>
</div>

   
</div>
    </section>

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" style="margin-top: 150px;">
    <div class="container">
      <div class="copyright">
        <H2 style="font-size: 20px;">Copyright © 2024 Western Mindanao State University. All rights reserved.
        </H2>
      </div>
      <div class="credits">
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <script src="static/js/sweetalert2.all.min.js"></script>
  <script src="static/js/jquery.min.js"></script>
  <script src="static/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="static/vendor/aos/aos.js"></script>
  <script src="static/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="static/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="static/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="static/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="static/vendor/php-email-form/validate.js"></script>

  <script src="static/js/main.js"></script>
  <script src="static/js/script.js"></script>
  <script src="static/js/accounts.js"></script>
  <script src="static/js/userinteractions.js"></script>

</body>

</html>