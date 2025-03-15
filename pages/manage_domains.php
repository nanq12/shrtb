<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $domain_name = $_POST['domain_name'];
    $user_id = $_SESSION['user_id'];
    if (addDomain($domain_name, $user_id)) {
        echo "Domain added successfully!";
    } else {
        echo "Failed to add domain.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Domains</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <form method="post">
        <input type="text" name="domain_name" placeholder="Domain Name" required>
        <button type="submit">Add Domain</button>
    </form>
</body>
</html>