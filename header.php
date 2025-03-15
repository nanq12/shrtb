<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Shortlink App'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/mobile-nav.css">
    <style>
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        .profile-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .profile-dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <header>
        <nav>
            <a href="../pages/dashboard.php" class="brand">ShortLink</a>
            <button type="button" class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul>
                <li><a href="../pages/dashboard.php">Dashboard</a></li>
                <?php if (isDeveloper()): ?>
                <li><a href="../pages/add_user.php">Add User</a></li>
                <?php endif; ?>
                <li><a href="../pages/create_link.php">Create Link</a></li>
                <li><a href="../pages/view_links.php">View Links</a></li>
                <li><a href="../pages/view_traffic.php">View Traffic</a></li>
                <?php if (isLoggedIn()): ?>
                <li><a href="../pages/add_domain.php">Add Domain</a></li>
                <li class="profile-dropdown">
                    <img src="../assets/images/dwd.jpg" alt="Profile Picture">
                    <div class="dropdown-content">
                        <a href="../pages/update_account.php">Update Profile</a>
                        <a href="../pages/logout.php">Logout</a>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="content">
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/mobile-nav.js"></script>
</body>
</html>