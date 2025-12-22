<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : 1;

if (empty($id)) {
    echo json_encode(array('success' => false, 'message' => '缺少用户ID'));
    exit;
}

$conn = getDBConnection();
$sql = "UPDATE users SET status = " . intval($status) . " WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '状态更新成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '更新失败: ' . $conn->error));
}

$conn->close();
?>