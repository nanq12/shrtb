<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$link_id = $_GET['id'] ?? '';

if ($link_id) {
    $stmt = $conn->prepare("DELETE FROM links WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $link_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header('Location: ../pages/view_links.php');
        exit;
    } else {
        echo "<div class='error-message'>Failed to delete link.</div>";
    }
} else {
    die("Invalid link ID.");
}
?>