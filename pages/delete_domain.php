<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$domain_id = $_GET['id'] ?? '';

if ($domain_id) {
    $stmt = $conn->prepare("DELETE FROM domains WHERE id = ? AND added_by = ?");
    $stmt->bind_param("ii", $domain_id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header('Location: ../pages/view_domains.php');
        exit;
    } else {
        echo "<div class='error-message'>Failed to delete domain.</div>";
    }
} else {
    die("Invalid domain ID.");
}
?>