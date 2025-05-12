<?php
include 'dbconnection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    echo '<p>Invalid archive ID.</p>';
    exit;
}

try {
    $sql = "
        SELECT 
            a.research_title,
            a.author,
            a.co_authors,
            a.abstract,
            a.keywords,
            ss.date_accepted,
            d.department_name,
            c.college_name
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
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $archive = $result->fetch_assoc();

    if (!$archive) {
        echo '<p>Archive not found.</p>';
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}
?>

<div class="card">
    <div class="card-body">
        <h2 style="color: rgb(255,0,51);" class="card-title text-center"><?php echo htmlspecialchars($archive['research_title']); ?></h2>
        <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($archive['author']); ?></p>
        <p class="card-text"><strong>Co-authors:</strong> <?php echo htmlspecialchars($archive['co_authors']); ?></p>
        <p class="card-text"><strong>Abstract:</strong> <?php echo htmlspecialchars($archive['abstract']); ?></p>
        <p class="card-text"><strong>Keywords:</strong> <?php echo htmlspecialchars($archive['keywords']); ?></p>
        <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($archive['date_accepted']); ?></p>
        <p class="card-text"><strong>From:</strong> <?php echo htmlspecialchars($archive['college_name']); ?></p>
        <p class="card-text"><strong>Department:</strong> <?php echo htmlspecialchars($archive['department_name']); ?></p>
    </div>
</div>