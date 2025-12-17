<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$conn = getDBConnection();
$sql = "SELECT id, username, email, created_at, '正常' as status FROM users ORDER BY id DESC";
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
