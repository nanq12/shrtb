<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

// Ambil daftar domain yang sudah ditambahkan
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM domains WHERE added_by = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$domains = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original_url = $_POST['original_url'];
    $slug = $_POST['slug'];
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $meta_description = $_POST['meta_description'];
    $type = $_POST['type'];
    $domain_id = $_POST['domain_id']; // Ambil domain yang dipilih
    $user_id = $_SESSION['user_id'];

    // Validasi domain_id
    if (empty($domain_id)) {
        echo "<div class='error-message'>Please select a domain.</div>";
    } else {
        // Ambil domain name dari database
        $stmt = $conn->prepare("SELECT domain_name FROM domains WHERE id = ?");
        $stmt->bind_param("i", $domain_id);
        $stmt->execute();
        $stmt->bind_result($domain_name);
        $stmt->fetch();
        $stmt->close();

        // Gabungkan domain name dengan slug
        $full_slug = "https://" . $domain_name . "/" . $slug;

        // Simpan link ke database
        if (createLink($user_id, $original_url, $full_slug, $title, $image_url, $meta_description, $type)) {
            echo "<div class='success-message'>Link created successfully!</div>";
        } else {
            echo "<div class='error-message'>Failed to create link.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Link</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h2>Create Link</h2>
        <form method="post">
            <select name="domain_id" required>
                <option value="">Select Domain</option>
                <?php foreach ($domains as $domain): ?>
                <option value="<?= $domain['id'] ?>"><?= htmlspecialchars($domain['domain_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="url" name="original_url" placeholder="Original URL" required>
            <input type="text" name="slug" placeholder="Custom Slug" required>
            <input type="text" name="title" placeholder="Title" required>
            <input type="url" name="image_url" placeholder="Image URL">
            <textarea name="meta_description" placeholder="Meta Description"></textarea>
            <select name="type">
                <option value="website">Website</option>
                <option value="article">Article</option>
                <option value="video">Video</option>
            </select>
            <button type="submit">Create Link</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>