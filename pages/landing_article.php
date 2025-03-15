<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

$slug = $_GET['slug'] ?? '';

if ($slug) {
    $stmt = $conn->prepare("SELECT * FROM links WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($link = $result->fetch_assoc()) {
        $title = $link['title'];
        $image_url = $link['image_url'];
        $meta_description = $link['meta_description'];
        $original_url = $link['original_url'];

        // Log traffic
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip_address}/json"));
        $country = $details->country ?? 'Unknown';
        $isp = $details->org ?? 'Unknown';
        $device = $_SERVER['HTTP_USER_AGENT'];

        $stmt = $conn->prepare("INSERT INTO traffic (link_id, ip_address, country, isp, device) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $link['id'], $ip_address, $country, $isp, $device);
        $stmt->execute();

        // Display landing page
        echo "<!DOCTYPE html>
        <html lang='en' dir='ltr'>
        <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <title>$title | $meta_description</title>
          <base href='$original_url'>
          <meta name='description' content='$meta_description'>
          <meta property='og:title' content='$title'>
          <meta property='og:description' content='$meta_description'>
          <meta property='og:image' content='$image_url'>
          <meta property='og:type' content='article'>
          <meta property='twitter:card' content='summary_large_image'>
          <meta property='twitter:title' content='$title'>
          <meta property='twitter:description' content='$meta_description'>
          <meta property='twitter:image' content='$image_url'>
          <link href='$image_url' rel='shortcut icon'>
          <style>
            body, html {
              font-family: 'Helvetica Neue', Arial, sans-serif;
              margin: 0;
              padding: 0;
              height: 100%;
              width: 100%;
              background-color: #f8f9fa;
            }
            .container {
              max-width: 800px;
              margin: auto;
              padding: 40px 20px;
              text-align: center;
              background-color: #fff;
              box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
              border-radius: 10px;
              animation: 1s ease-in-out fadeIn;
            }
            h1 {
              font-size: 36px;
              color: #333;
              margin-bottom: 20px;
            }
            p {
              font-size: 18px;
              color: #555;
              margin-bottom: 30px;
            }
            .article-image {
              width: 100%;
              height: 300px;
              object-fit: cover;
              border-radius: 10px;
              margin-bottom: 20px;
            }
            .read-more-btn {
              display: inline-block;
              padding: 12px 30px;
              font-size: 18px;
              font-weight: 700;
              color: #fff;
              background-color: #0474ff;
              text-decoration: none;
              border-radius: 30px;
              transition: background-color 0.3s, transform 0.3s;
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            }
            .read-more-btn:hover {
              background-color: #0056b3;
              transform: translateY(-2px);
            }
            @media (max-width: 600px) {
              h1 { font-size: 28px; }
              p { font-size: 16px; }
              .article-image { height: 200px; }
              .read-more-btn { font-size: 16px; padding: 10px 25px; }
            }
            @keyframes fadeIn {
              from { opacity: 0; transform: translateY(20px); }
              to { opacity: 1; transform: translateY(0); }
            }
          </style>
        </head>
        <body>
          <div class='container'>
            <h1>$title</h1>
            <img src='$image_url' class='article-image' alt='Article Image'>
            <p>$meta_description</p>
            <a href='$original_url' class='read-more-btn'>Baca Selengkapnya</a>
          </div>
        </body>
        </html>";
    } else {
        die("Link not found.");
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>