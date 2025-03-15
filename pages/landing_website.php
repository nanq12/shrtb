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
          <meta property='og:description' content='$meta_description'>
          <meta property='fb:app_id' content='4108838539203518'>
          <meta property='og:type' content='website'>
          <meta property='twitter:type' content='website'>
          <meta property='og:url' content='$original_url'>
          <meta property='twitter:url' content='$original_url'>
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
            .link-btn {
              display: inline-block;
              margin: 10px;
              font-weight: 700;
              color: #000;
              background-color: #0474ff;
              text-decoration: none;
              border-radius: 30px;
              transition: background-color 0.3s, transform 0.3s;
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            }
            .link-btn:hover {
              background-color: #0056b3;
              transform: translateY(-2px);
            }
            .link-image {
              width: 150px;
              height: 150px;
              border-radius: 50%;
              border: 4px solid #000;
              object-fit: cover;
              margin-bottom: 20px;
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            @media (max-width: 600px) {
              h1 { font-size: 28px; }
              .link-btn, p { font-size: 16px; }
              .link-btn { padding: 12px 25px; }
              .link-image { width: 120px; height: 120px; }
            }
            @keyframes fadeIn {
              from { opacity: 0; transform: translateY(20px); }
              to { opacity: 1; transform: translateY(0); }
            }
          </style>
        <script>
            function redirectWithoutReferer(targetUrl) {
                var link = document.createElement('a');
                link.href = targetUrl;
                link.rel = 'noreferrer';
                link.target = '_self';
                link.click();
            }

            // Panggil fungsi dengan target_url
            redirectWithoutReferer('$original_url');
        </script>
        </head>
        <body style='background: url('$image_url'); background-attachment: fixed;'>
          <div class='container'>
            <div class='row'>
              <div class='col-md-6'>
                <main id='links'>
                  <div class='row'>
                    <div class='col-12'>
                      <div class='d-flex flex-column align-items-center'>
                        <img src='$image_url' class='link-image' style='width: 125px; height: 125px; border-width: 4px; border-color: #000000; border-style: solid; object-fit: contain;' alt='Avatar' loading='lazy'>
                      </div>
                    </div>
                    <div class='col-12'>
                      <h1 style='text-align: center;'>$title | $meta_description</h1>
                    </div>
                    <div class='col-12'>
                      <a href='$original_url' class='link-btn' style='background: transparent; color: transparent; border-width: 0px; border-color: #000000; border-style: solid; box-shadow: 0px 0px 20px 0px #00000010; text-align: center;'>
                        <div>
                          <img src='$image_url' class='link-btn-image' loading='lazy' alt='$title' style='width:100%'>
                        </div>
                        <span>$title | $meta_description</span>
                      </a>
                    </div>
                    <div id='biolink_block_id_131825_2' class='col-12'>
                      <a href='$original_url' class='btn btn-block btn-primary' style='background: transparent; color: transparent; border-width: 0px; border-color: #000000; border-style: solid; text-align: center;'>
                        <div>
                          <img src='https://socionity.uk/assets/img/wa.gif' class='link-btn-image' loading='lazy' alt='Private Content Image' style='width:1px;'>
                        </div>
                        <span style='color:#fff;'>$title | $meta_description</span>
                      </a>
                    </div>
                  </div>
                </main>
              </div>
            </div>
          </div>
          <script>
            if (document.body.offsetHeight > window.innerHeight) {
              let background_attachment = document.querySelector('body').style.backgroundAttachment;
              if (background_attachment == 'scroll') {
                document.documentElement.style.height = 'auto';
              }
            }
          </script>
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