<?php
require_once 'db.php'; 

function createLink($user_id, $original_url, $slug, $title, $image_url, $meta_description, $type) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO links (user_id, original_url, slug, title, image_url, meta_description, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $original_url, $slug, $title, $image_url, $meta_description, $type);
    return $stmt->execute();
}

function getTraffic($linkId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM traffic WHERE link_id = ?");
    $stmt->bind_param("i", $linkId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addDomain($domain_name, $user_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO domains (domain_name, added_by) VALUES (?, ?)");
    $stmt->bind_param("si", $domain_name, $user_id);
    return $stmt->execute();
}

function updateLink($link_id, $original_url, $slug, $title, $image_url, $meta_description, $type) {
    global $conn;
    $stmt = $conn->prepare("UPDATE links SET original_url = ?, slug = ?, title = ?, image_url = ?, meta_description = ?, type = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $original_url, $slug, $title, $image_url, $meta_description, $type, $link_id);
    return $stmt->execute();
}

function updateDomain($domain_id, $domain_name) {
    global $conn;
    $stmt = $conn->prepare("UPDATE domains SET domain_name = ? WHERE id = ?");
    $stmt->bind_param("si", $domain_name, $domain_id);
    return $stmt->execute();
}

/**
 * Get total links count for a user
 */
function getUserLinksCount($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM links WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'];
}

/**
 * Get total unique domains count for a user
 */
function getUserDomainsCount($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT correct_column_name) as count FROM links WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['count'];
}

/**
 * Get user's last activity timestamp
 */
function getUserLastActivity($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT active_until FROM users WHERE id = ? ORDER BY active_until DESC LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['active_until'];
}

function getUsername($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['username'] ?? 'Guest';
}

function updateUser($userId, $newUsername, $newPassword) {
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt->bind_param("ssi", $newUsername, $hashedPassword, $userId);
    return $stmt->execute();
}

function getUserInfo($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT username, created_at, active_until FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>