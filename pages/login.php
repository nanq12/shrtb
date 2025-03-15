<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // Cek apakah checkbox "Ingat Saya" dicentang

    if (login($username, $password, $remember)) {
        header('Location: ../pages/dashboard.php');
        exit;
    } else {
        echo "<div class='error-message'>Invalid credentials!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shortlink</title>
    <link rel="stylesheet" href="../assets/css/style-login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>shrtb.my.id</h1>
            <form method="post">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group">
                    <label>
                        <input type="checkbox" name="remember"> Ingat Saya
                    </label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="register-link">
                Don't have an account? <a href="https://wa.me/083899600457">Register here</a>
            </div>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>