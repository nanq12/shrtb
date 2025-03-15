<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 

if (!isLoggedIn() || $_SESSION['role'] !== 'developer') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $active_until = $_POST['active_until'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, active_until, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $active_until, $role);
    if ($stmt->execute()) {
        echo "<div class='success-message'>User added successfully!</div>";
    } else {
        echo "<div class='error-message'>Failed to add user.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <form method="post">
            <h2>Add User</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="datetime-local" name="active_until" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="developer">Developer</option>
            </select>
            <button type="submit">Add User</button>
        </form>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>