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

// 修复统计查询，确保基于正确答案统计
$sql = "SELECT 
            COUNT(*) as total, 
            SUM(CASE WHEN qr.is_correct = 1 THEN 1 ELSE 0 END) as correct 
        FROM quiz_records qr 
        LEFT JOIN quizzes q ON qr.quiz_id = q.id 
        WHERE qr.user_id = ?";

$stmt = $conn->prepare($sql);
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