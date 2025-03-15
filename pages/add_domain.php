<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Hanya pengguna yang login yang boleh mengakses
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $domain_name = $_POST['domain_name'];
    $user_id = $_SESSION['user_id'];

    // Validasi domain name
    if (empty($domain_name)) {
        echo "<div class='error-message'>Domain name cannot be empty.</div>";
    } else {
        // Tambahkan domain ke database
        if (addDomain($domain_name, $user_id)) {
            echo "<div class='success-message'>Domain added successfully!</div>";
        } else {
            echo "<div class='error-message'>Failed to add domain. It may already exist.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Domain</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h2>Add Domain</h2>
        <div class="instructions">
            <h3>PETUNJUK MENAMBAHKAN DOMAIN SENDIRI:</h3>
            <ol>
                <li><strong>IP SERVER:</strong> 173.249.7.88</li>
                <li><strong>BUAT DNS RECORD DI CLOUDFLARE:</strong>
                    <ul>
                        <li><strong>A - @ - IP:</strong> Arahkan root domain ke IP server.</li>
                        <li><strong>A - * - IP:</strong> Arahkan semua subdomain ke IP server.</li>
                    </ul>
                </li>
                <li>Setelah DNS record dibuat, tambahkan domain di form di bawah ini.</li>
            </ol>
        </div>
        <form method="post">
            <input type="text" name="domain_name" placeholder="Domain Name (e.g., example.com)" required>
            <button type="submit">Add Domain</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>