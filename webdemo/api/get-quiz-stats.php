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

// 获取总答题数
$stmt = $conn->prepare("SELECT COUNT(*) as total, SUM(is_correct) as correct FROM quiz_records WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

$total = intval($stats['total']);
$correct = intval($stats['correct']);
$accuracy = $total > 0 ? round(($correct / $total) * 100, 1) : 0;

echo json_encode(array(
    'success' => true,
    'data' => array(
        'total' => $total,
        'correct' => $correct,
        'accuracy' => $accuracy
    )
));

$stmt->close();
$conn->close();
?>
