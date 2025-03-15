<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Cek cookie "remember_me"
if (!isLoggedIn() && isset($_COOKIE['remember_me'])) {
    $cookie_value = base64_decode($_COOKIE['remember_me']);
    list($username, $hashed_password) = explode(':', $cookie_value);

    // Lakukan login otomatis
    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $role);
    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
    }
}

$slug = $_GET['slug'] ?? '';

if ($slug) {
    $stmt = $conn->prepare("SELECT * FROM links WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($link = $result->fetch_assoc()) {
        // Log traffic
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip_address}/json"));
        $country = $details->country ?? 'Unknown';
        $isp = $details->org ?? 'Unknown';
        $device = $_SERVER['HTTP_USER_AGENT'];

        $stmt = $conn->prepare("INSERT INTO traffic (link_id, ip_address, country, isp, device) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $link['id'], $ip_address, $country, $isp, $device);
        $stmt->execute();

        // Redirect to appropriate landing page based on type
        switch ($link['type']) {
            case 'website':
                header("Location: ../pages/landing_website.php?slug=$slug");
                break;
            case 'article':
                header("Location: ../pages/landing_article.php?slug=$slug");
                break;
            case 'video':
                header("Location: ../pages/landing_video.php?slug=$slug");
                break;
            default:
                die("Invalid link type.");
        }
        exit;
    } else {
        die("Link not found.");
    }
} else {
    header("Location: pages/login.php");
    exit;
}
?>