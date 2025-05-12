<?php
if (isset($_GET['file'])) {
    $file_path = urldecode($_GET['file']);
    $full_path = realpath($file_path);

    // Check if the file exists
    if (file_exists($full_path)) {
        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>