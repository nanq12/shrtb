<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$userInfo = getUserInfo($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];

    if (updateUser($userId, $newUsername, $newPassword)) {
        $_SESSION['username'] = $newUsername; // Update session username
        header('Location: ../pages/dashboard.php?update=success');
    } else {
        header('Location: ../pages/dashboard.php?update=failure');
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .profile-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .account-info {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        .profile-container form {
            display: flex;
            flex-direction: column;
        }
        .profile-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .profile-container input {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .profile-container .password-container {
            position: relative;
        }
        .profile-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: #666;
        }
        .profile-container button {
            width: 100%;
            padding: 10px;
            background-color: #0474ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .profile-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <div class="account-info">
            <p>Account created on: <?php echo date('d M Y', strtotime($userInfo['created_at'])); ?></p>
            <p>Expire until: <?php echo $userInfo['active_until'] ? date('d M Y', strtotime($userInfo['active_until'])) : 'No expiration'; ?></p>
            <p>Account name: <?php echo htmlspecialchars($userInfo['username']); ?></p>
        </div>
        <form method="POST" action="../pages/update_account.php">
            <label for="username">New Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userInfo['username']); ?>" required>
            
            <div class="password-container">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
            </div>
            
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
