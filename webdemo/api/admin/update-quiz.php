<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$$id = isset($_POST['id']) ? $_POST['id'] : 0;
$$question = isset($_POST['question']) ? $_POST['question'] : '';
$$option_a = isset($_POST['option_a']) ? $_POST['option_a'] : '';
$$option_b = isset($_POST['option_b']) ? $_POST['option_b'] : '';
$$option_c = isset($_POST['option_c']) ? $_POST['option_c'] : '';
$$option_d = isset($_POST['option_d']) ? $_POST['option_d'] : '';
$$correct_answer = isset($_POST['correct_answer']) ? $_POST['correct_answer'] : '';
$$explanation = isset($_POST['explanation']) ? $_POST['explanation'] : '';

if (empty($id) || empty($question) || empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_answer)) {
    echo json_encode(array('success' => false, 'message' => '缺少必填字段'));
    exit;
}

if (!in_array($correct_answer, ['A', 'B', 'C', 'D'])) {
    echo json_encode(array('success' => false, 'message' => '正确答案必须是A、B、C或D'));
    exit;
}

$conn = getDBConnection();
$sql = "UPDATE quizzes SET 
        question = '" . $conn->real_escape_string($question) . "',
        option_a = '" . $conn->real_escape_string($option_a) . "',
        option_b = '" . $conn->real_escape_string($option_b) . "',
        option_c = '" . $conn->real_escape_string($option_c) . "',
        option_d = '" . $conn->real_escape_string($option_d) . "',
        correct_answer = '" . $conn->real_escape_string($correct_answer) . "',
        explanation = '" . $conn->real_escape_string($explanation) . "'
        WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '更新成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '更新失败: ' . $conn->error));
}

$conn->close();
?>