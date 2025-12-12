<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn()) {
    echo json_encode(array('success' => false, 'message' => '未登录'));
    exit;
}

$conn = getDBConnection();

$userId = getCurrentUserId();
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(array('success' => false, 'message' => '用户不存在'));
    $stmt->close();
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();

echo json_encode(array(
    'success' => true,
    'data' => $user
));

$stmt->close();
$conn->close();
?>
