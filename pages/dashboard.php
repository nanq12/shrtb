<?php
session_start(); // Start the session
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php';   // Gunakan require_once
require_once '../includes/functions.php'; // Include the functions file

if (!isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

// Ensure the username is set in the session
if (!isset($_SESSION['username'])) {
    // Handle the case where the username is not set
    $_SESSION['username'] = 'Guest'; // Or redirect to login
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Welcome to Dashboard</h1>
            <p class="user-info">
                <i class="fas fa-user"></i> 
                Logged in as: <?php echo htmlspecialchars(getUsername($_SESSION['user_id'])); ?>
                <br>
                expired time: <?php echo htmlspecialchars(getUserLastActivity($_SESSION['user_id'])); ?>
            </p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <i class="fas fa-link"></i>
                <h3>Total Links</h3>
                <p><?php echo getUserLinksCount($_SESSION['user_id']); ?></p>
            </div>
            
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>Traffic Statistics</h3>
                <?php
                $trafficData = getTraffic($_SESSION['user_id']);
                $trafficCount = count($trafficData);
                ?>
                <p><?php echo $trafficCount; ?></p>
            </div>

            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <h3>Last Activity</h3>
                <p>
                    <a href="../pages/update_account.php">
                        <?php 
                        $lastActivity = getUserLastActivity($_SESSION['user_id']); 
                        echo $lastActivity ? date('d M Y', strtotime($lastActivity)) : 'No activity'; 
                        ?>
                    </a>
                </p>
            </div>
        </div>

        <div class="dashboard-actions">
            <?php if (isDeveloper()): ?>
            <a href="../pages/add_user.php" class="btn"><i class="fas fa-user-plus"></i> Add User</a>
            <?php endif; ?>
            <a href="../pages/create_link.php" class="btn"><i class="fas fa-plus-circle"></i> Create New Link</a>
            <a href="../pages/view_links.php" class="btn"><i class="fas fa-list"></i> View Links</a>
            <a href="../pages/view_domains.php" class="btn"><i class="fas fa-globe"></i> View Domains</a>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>