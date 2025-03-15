<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Hanya panggil session_start() jika session belum aktif
}

require_once 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isDeveloper() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'developer';
}

function login($username, $password, $remember = false) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        // Jika "Ingat Saya" dicentang, set cookie
        if ($remember) {
            $cookie_value = base64_encode("$username:$hashed_password");
            setcookie('remember_me', $cookie_value, time() + (7 * 24 * 60 * 60), "/"); // Cookie berlaku selama 1 minggu
        }

        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    setcookie('remember_me', '', time() - 3600, "/"); // Hapus cookie
    header('Location: ../pages/login.php');
    exit;
}
?>