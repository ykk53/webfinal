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
    echo json_encode(array('success' => false, 'message' => '缺少记录ID'));
    exit;
}

$conn = getDBConnection();

// 首先获取记录信息，用于更新用户积分
$recordSql = "SELECT user_id, is_correct FROM quiz_records WHERE id = " . intval($id);
$recordResult = $conn->query($recordSql);

if ($recordResult->num_rows > 0) {
    $record = $recordResult->fetch_assoc();
    
    // 如果该记录是答对的，需要从用户积分中扣除
    if ($record['is_correct'] == 1) {
        $updatePointsSql = "UPDATE user_points SET points = GREATEST(0, points - 1) WHERE user_id = " . $record['user_id'];
        $conn->query($updatePointsSql);
    }
}

// 删除记录
$sql = "DELETE FROM quiz_records WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '删除成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '删除失败: ' . $conn->error));
}

$conn->close();
?>