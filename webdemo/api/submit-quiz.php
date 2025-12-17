<?php
header('Content-Type: application/json');
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'message' => '未登录'));
    exit;
}

$userId = $_SESSION['user_id'];
$quizId = $_POST['quiz_id'];
$userAnswer = $_POST['user_answer'];
$isCorrect = $_POST['is_correct'];

if (empty($quizId) || empty($userAnswer)) {
    echo json_encode(array('success' => false, 'message' => '缺少参数'));
    exit;
}

$conn = getDBConnection();
$sql = "INSERT INTO quiz_records (user_id, quiz_id, user_answer, is_correct, answered_at) VALUES (" . 
       intval($userId) . ", " . 
       intval($quizId) . ", '" . 
       $conn->real_escape_string($userAnswer) . "', " . 
       intval($isCorrect) . ", NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '提交成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '提交失败: ' . $conn->error));
}

$conn->close();
?>
