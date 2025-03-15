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
    try {
        $stmt = $conn->prepare("SELECT * FROM domains WHERE id = ? AND added_by = ?");
        $stmt->bind_param("ii", $domain_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $domain = $result->fetch_assoc();

        if (!$domain) {
            die("Domain not found or unauthorized access.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $domain_name = trim($_POST['domain_name']); // sanitize input
            
            if (empty($domain_name)) {
                echo "<div class='error-message'>Domain name cannot be empty.</div>";
            } else if (updateDomain($domain_id, $domain_name)) {
                echo "<div class='success-message'>Domain updated successfully!</div>";
            } else {
                echo "<div class='error-message'>Failed to update domain.</div>";
            }
        }
    } catch (Exception $e) {
        die("Database error occurred.");
    }
} else {
    die("Invalid domain ID.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Domain</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <form method="post">
            <h2>Edit Domain</h2>
            <input type="text" name="domain_name" placeholder="Domain Name" value="<?= htmlspecialchars($domain['domain_name']) ?>" required>
            <button type="submit">Update Domain</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>