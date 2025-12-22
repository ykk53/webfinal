<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$id = isset($_POST['id']) ? $_POST['id'] : 0;

if (empty($id)) {
    echo json_encode(array('success' => false, 'message' => '缺少用户ID'));
    exit;
}

$conn = getDBConnection();

// 先删除用户的积分记录
$conn->query("DELETE FROM user_points WHERE user_id = " . intval($id));
// 删除用户的答题记录
$conn->query("DELETE FROM quiz_records WHERE user_id = " . intval($id));
// 删除用户的每日答题限制
$conn->query("DELETE FROM daily_quiz_limit WHERE user_id = " . intval($id));

// 最后删除用户
$sql = "DELETE FROM users WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '删除成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '删除失败: ' . $conn->error));
}

$conn->close();
?>