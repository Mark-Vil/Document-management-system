<?php
include 'dbconnection.php';

function hasUserInteractions($userId) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM userinteractions WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}
?>