<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn()) {
    echo json_encode(array('success' => false, 'message' => '未登录'));
    exit;
}

$userId = getCurrentUserId();
$quizId = isset($_POST['quiz_id']) ? $_POST['quiz_id'] : 0;
$userAnswer = isset($_POST['user_answer']) ? $_POST['user_answer'] : '';
$isLast = isset($_POST['is_last']) ? $_POST['is_last'] : 0; // 是否是最后一题

if (empty($quizId) || empty($userAnswer)) {
    echo json_encode(array('success' => false, 'message' => '缺少参数'));
    exit;
}

$conn = getDBConnection();

// 首先，从数据库中获取题目的正确答案
$quizQuery = $conn->query("SELECT correct_answer, explanation FROM quizzes WHERE id = " . intval($quizId));
if ($quizQuery->num_rows === 0) {
    echo json_encode(array('success' => false, 'message' => '题目不存在'));
    $conn->close();
    exit;
}
$quiz = $quizQuery->fetch_assoc();
$correctAnswer = $quiz['correct_answer'];
$explanation = isset($quiz['explanation']) ? $quiz['explanation'] : '';

// 后端判断答案是否正确
$userAnswer = strtoupper(trim($userAnswer));
$correctAnswer = strtoupper(trim($correctAnswer));
$isCorrect = ($userAnswer === $correctAnswer) ? 1 : 0;

// 记录答题结果
$sql = "INSERT INTO quiz_records (user_id, quiz_id, user_answer, is_correct, answered_at) VALUES (" . 
       intval($userId) . ", " . 
       intval($quizId) . ", '" . 
       $conn->real_escape_string($userAnswer) . "', " . 
       intval($isCorrect) . ", NOW())";

$response = array('success' => false);

if ($conn->query($sql) === TRUE) {
    // 答对加分
    if ($isCorrect) {
        // 检查用户积分记录是否存在
        $pointResult = $conn->query("SELECT * FROM user_points WHERE user_id = " . $userId);
        if ($pointResult->num_rows > 0) {
            $conn->query("UPDATE user_points SET points = points + 1, updated_at = NOW() WHERE user_id = " . $userId);
        } else {
            $conn->query("INSERT INTO user_points (user_id, points) VALUES (" . $userId . ", 1)");
        }
    }
    
    // 如果是最后一题，标记今日答题完成
    if ($isLast) {
        $today = date('Y-m-d');
        // 先检查是否存在记录
        $checkSql = "SELECT * FROM daily_quiz_limit WHERE user_id = " . $userId . " AND quiz_date = '" . $today . "'";
        $checkResult = $conn->query($checkSql);
        if ($checkResult->num_rows > 0) {
            $conn->query("UPDATE daily_quiz_limit SET completed = 1 WHERE user_id = " . $userId . " AND quiz_date = '" . $today . "'");
        } else {
            $conn->query("INSERT INTO daily_quiz_limit (user_id, quiz_date, completed) VALUES (" . $userId . ", '" . $today . "', 1)");
        }
    }
    
    $response = array(
        'success' => true, 
        'message' => '提交成功',
        'is_correct' => $isCorrect,
        'correct_answer' => $correctAnswer,
        'explanation' => $explanation
    );
} else {
    $response['message'] = '提交失败: ' . $conn->error;
}

$conn->close();
echo json_encode($response);
?>