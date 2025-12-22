<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn()) {
    echo json_encode(array('success' => false, 'message' => '请先登录'));
    exit;
}

$userId = getCurrentUserId();
$today = date('Y-m-d');

// 检查今日是否已完成答题
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT * FROM daily_quiz_limit WHERE user_id = ? AND quiz_date = ?");
$stmt->bind_param("is", $userId, $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $limitData = $result->fetch_assoc();
    if ($limitData['completed'] == 1) {
        echo json_encode(array('success' => false, 'message' => '今日已完成答题，明天再来吧'));
        $conn->close();
        exit;
    }
}

// 随机获取7道题目，包含正确答案用于后端验证
$quizResult = $conn->query("SELECT id, question, option_a, option_b, option_c, option_d, correct_answer, explanation FROM quizzes ORDER BY RAND() LIMIT 7");
$quizzes = array();

if ($quizResult->num_rows > 0) {
    while($row = $quizResult->fetch_assoc()) {
        $quizzes[] = $row;
    }
}

// 记录今日开始答题
if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO daily_quiz_limit (user_id, quiz_date) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $today);
    $stmt->execute();
}

echo json_encode(array('success' => true, 'data' => $quizzes));
$conn->close();
?>