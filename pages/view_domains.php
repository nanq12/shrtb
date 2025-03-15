<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM domains WHERE added_by = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$domains = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Domains</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Your Domains</h1>
        <table>
            <tr>
                <th>Domain Name</th>
                <th>Added At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($domains as $domain): ?>
            <tr>
                <td><?= htmlspecialchars($domain['domain_name']) ?></td>
                <td><?= htmlspecialchars($domain['added_at']) ?></td>
                <td>
                    <a href="edit_domain.php?id=<?= $domain['id'] ?>" class="btn">Edit</a>
                    <a href="delete_domain.php?id=<?= $domain['id'] ?>" class="btn btn-danger" onclick="return confirmDelete()">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>