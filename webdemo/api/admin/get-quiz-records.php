<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$conn = getDBConnection();

// 修复SQL查询，确保正确关联表并获取正确答案
$sql = "SELECT 
            qr.id, 
            u.username, 
            q.question, 
            qr.user_answer, 
            q.correct_answer,  -- 添加正确答案字段
            qr.is_correct, 
            qr.answered_at as created_at 
        FROM quiz_records qr 
        LEFT JOIN users u ON qr.user_id = u.id 
        LEFT JOIN quizzes q ON qr.quiz_id = q.id 
        ORDER BY qr.id DESC";

$result = $conn->query($sql);

$records = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // 确保is_correct字段正确反映答题结果
        $userAnswer = strtoupper(trim($row['user_answer']));
        $correctAnswer = strtoupper(trim($row['correct_answer']));
        
        // 重新验证答案是否正确（双重检查）
        $isCorrect = ($userAnswer === $correctAnswer) ? 1 : 0;
        
        // 如果数据库中的is_correct字段不正确，进行修正
        if ($row['is_correct'] != $isCorrect) {
            // 更新数据库中的记录
            $updateSql = "UPDATE quiz_records SET is_correct = $isCorrect WHERE id = " . $row['id'];
            $conn->query($updateSql);
        }
        
        $records[] = array(
            'id' => $row['id'],
            'username' => $row['username'],
            'question' => $row['question'],
            'user_answer' => $row['user_answer'],
            'is_correct' => $isCorrect,  // 使用重新计算的结果
            'created_at' => $row['created_at']
        );
    }
}

echo json_encode(array('success' => true, 'data' => $records));
$conn->close();
?>