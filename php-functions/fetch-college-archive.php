<?php
include 'dbconnection.php';

function decrypt($data) {
    return base64_decode($data);
}

function encrypt($data) {
    return urlencode(base64_encode($data));
}

$college_name = '';
$archives = [];
$encrypted_college_code = '';

if (isset($_GET['code'])) {
    $college_code = decrypt($_GET['code']);
    $decrypted_college_code = $college_code;

    // SQL query to fetch archive rows connected to the college_code and where submission_status is "Accepted"
    $archive_sql = "
        SELECT 
            a.*, c.college_name, s.date_accepted
        FROM 
            archive a
        JOIN 
            departments d ON a.faculty_code = d.department_code
        JOIN 
            colleges c ON d.college_code = c.college_code
        JOIN 
            submission_status s ON a.id = s.submission_id
        WHERE 
            c.college_code = ? AND (s.status = 'Accepted' OR s.status = 'Locked')
    ";

    $archive_stmt = $conn->prepare($archive_sql);
    $archive_stmt->bind_param("s", $college_code);
    $archive_stmt->execute();
    $archive_result = $archive_stmt->get_result();
    $archives = $archive_result->fetch_all(MYSQLI_ASSOC);

    if (!empty($archives)) {
        $college_name = $archives[0]['college_name'];
    }

    $archive_stmt->close();
    $conn->close();
}
?>

<script>
    var decryptedCollegeCode = "<?php echo $decrypted_college_code; ?>";
</script>

    <div class="container" data-aos="fade-down">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="college-view-card-title ml-3 mb-4">From <?php echo htmlspecialchars($college_name); ?></h1>
            </div>
        </div>
        <div class="container" data-aos="fade-down">
            <style>
                .card { cursor: pointer; }
            </style>
            <div class="row">
                <?php if (!empty($archives)): ?>
                    <?php foreach ($archives as $archive): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
    <div class="card college-card" onclick="window.location.href='overview.php?id=<?php echo encrypt($archive['id']); ?>'">
        <div class="card-body">
            <a href="#" target="_blank" style="text-decoration: none;">
                <h5 class="research-title text-center"><?php echo htmlspecialchars($archive['research_title']); ?></h5>
            </a>
            <div class="info-section">
                <p class="card-text mb-2">
                    <strong>Author:</strong> 
                    <span><?php echo htmlspecialchars($archive['author']); ?></span>
                </p>
                <p class="card-text mb-2">
                    <strong>Co-authors:</strong> 
                    <span><?php echo htmlspecialchars($archive['co_authors']); ?></span>
                </p>
            </div>
            <p class="card-text abstract-text" style="height: 100px; overflow: hidden;">
                <strong>Abstract:</strong> 
                <span><?php echo htmlspecialchars($archive['abstract']); ?></span>
            </p>
            <a href="#" class="toggle-abstract" data-expanded="false">Read more...</a>
            <hr style="margin: 1rem 0; opacity: 0.1;">
            <div class="meta-info">
                <p class="card-text mb-1">
                    <strong>Keywords:</strong> 
                    <span><?php echo htmlspecialchars($archive['keywords']); ?></span>
                </p>
                <p class="card-text mb-1">
                    <strong>Date:</strong> 
                    <span><?php echo htmlspecialchars($archive['date_accepted']); ?></span>
                </p>
                <p class="card-text mb-0">
                    <strong>From:</strong> 
                    <span><?php echo htmlspecialchars($archive['college_name']); ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No archives found for this college.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>