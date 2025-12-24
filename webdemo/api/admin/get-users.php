<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$conn = getDBConnection();
// 修改查询语句，包含status字段
$sql = "SELECT id, username, email, created_at, status FROM users WHERE is_admin = 0 ORDER BY id DESC";
$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode(array('success' => true, 'data' => $users));
$conn->close();
?>