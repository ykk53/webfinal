<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$id = $_POST['id'];

if (empty($id)) {
    echo json_encode(array('success' => false, 'message' => '缺少问题ID'));
    exit;
}

$conn = getDBConnection();

// 先检查是否存在相关的答题记录
$checkSql = "SELECT COUNT(*) as count FROM quiz_records WHERE quiz_id = " . intval($id);
$result = $conn->query($checkSql);
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    // 如果存在答题记录，先删除相关记录
    $deleteRecordsSql = "DELETE FROM quiz_records WHERE quiz_id = " . intval($id);
    if (!$conn->query($deleteRecordsSql)) {
        echo json_encode(array('success' => false, 'message' => '删除相关答题记录失败: ' . $conn->error));
        $conn->close();
        exit;
    }
}

// 删除问题
$sql = "DELETE FROM quizzes WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '删除成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '删除失败: ' . $conn->error));
}

$conn->close();
?>