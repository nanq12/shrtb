<?php
require_once '../includes/auth.php'; // Gunakan require_once
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$traffic = getTraffic($user_id);
// Ambil data traffic untuk 7 hari terakhir
$stmt = $conn->prepare("
    SELECT DATE(accessed_at) AS access_date, COUNT(*) AS total_clicks
    FROM traffic
    WHERE link_id IN (SELECT id FROM links WHERE user_id = ?)
    AND accessed_at >= NOW() - INTERVAL 7 DAY
    GROUP BY access_date
    ORDER BY access_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$traffic_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Hapus data traffic yang sudah lewat 7 hari
$delete_stmt = $conn->prepare("
    DELETE FROM traffic
    WHERE link_id IN (SELECT id FROM links WHERE user_id = ?)
    AND accessed_at < NOW() - INTERVAL 7 DAY
");
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Traffic</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Traffic Data (Last 7 Days)</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Total Clicks</th>
            </tr>
            <?php foreach ($traffic_data as $traffic): ?>
            <tr>
                <td><?= htmlspecialchars($traffic['access_date']) ?></td>
                <td><?= htmlspecialchars($traffic['total_clicks']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
        <h1>Traffic Data</h1>
        <table>
            <tr>
                <th>IP Address</th>
                <th>Country</th>
                <th>ISP</th>
                <th>Device</th>
                <th>Accessed At</th>
            </tr>
            <?php foreach ($traffic as $visit): ?>
                <tr>
                    <td><?= htmlspecialchars($visit['ip_address']) ?></td>
                    <td><?= htmlspecialchars($visit['country']) ?></td>
                    <td><?= htmlspecialchars($visit['isp']) ?></td>
                    <td><?= htmlspecialchars($visit['device']) ?></td>
                    <td><?= htmlspecialchars($visit['accessed_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
    <?php include '../footer.php'; ?>
</body>
</html>