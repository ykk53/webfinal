<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$id = $_POST['id'];

if (empty($id)) {
    echo json_encode(array('success' => false, 'message' => '缺少用户ID'));
    exit;
}

$conn = getDBConnection();
$sql = "DELETE FROM users WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '删除成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '删除失败: ' . $conn->error));
}

$conn->close();
?>
