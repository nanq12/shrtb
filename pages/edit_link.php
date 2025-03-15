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
    $stmt = $conn->prepare("SELECT * FROM links WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $link_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $link = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $original_url = $_POST['original_url'];
        $slug = $_POST['slug'];
        $title = $_POST['title'];
        $image_url = $_POST['image_url'];
        $meta_description = $_POST['meta_description'];
        $type = $_POST['type'];

        if (updateLink($link_id, $original_url, $slug, $title, $image_url, $meta_description, $type)) {
            echo "<div class='success-message'>Link updated successfully!</div>";
        } else {
            echo "<div class='error-message'>Failed to update link.</div>";
        }
    }
} else {
    die("Invalid link ID.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Link</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <form method="post">
            <h2>Edit Link</h2>
            <input type="url" name="original_url" placeholder="Original URL" value="<?= htmlspecialchars($link['original_url']) ?>" required>
            <input type="text" name="slug" placeholder="Custom Slug" value="<?= htmlspecialchars($link['slug']) ?>" required>
            <input type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($link['title']) ?>" required>
            <input type="url" name="image_url" placeholder="Image URL" value="<?= htmlspecialchars($link['image_url']) ?>">
            <textarea name="meta_description" placeholder="Meta Description"><?= htmlspecialchars($link['meta_description']) ?></textarea>
            <select name="type">
                <option value="website" <?= $link['type'] == 'website' ? 'selected' : '' ?>>Website</option>
                <option value="article" <?= $link['type'] == 'article' ? 'selected' : '' ?>>Article</option>
                <option value="video" <?= $link['type'] == 'video' ? 'selected' : '' ?>>Video</option>
            </select>
            <button type="submit">Update Link</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>