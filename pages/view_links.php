<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM links WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$links = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Links</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Your Links</h1>
        <table>
            <tr>
                <th>Shortlink</th>
                <th>Title</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($links as $link): ?>
            <tr>
                <td>
                    <a href="<?= htmlspecialchars($link['slug']) ?>" 
                       title="Go to: <?= htmlspecialchars($link['original_url']) ?>" 
                       class="shortlink">
                        <?= htmlspecialchars("https://yourdomain.com/" . $link['slug']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($link['title']) ?></td>
                <td><?= htmlspecialchars($link['type']) ?></td>
                <td>
                    <a href="edit_link.php?id=<?= $link['id'] ?>" class="btn">Edit</a>
                    <a href="delete_link.php?id=<?= $link['id'] ?>" class="btn btn-danger" onclick="return confirmDelete()">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>