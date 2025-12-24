<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

if (empty($id)) {
    echo json_encode(array('success' => false, 'message' => '缺少问题ID'));
    exit;
}

$conn = getDBConnection();
$sql = "SELECT * FROM quizzes WHERE id = " . intval($id);
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $quiz = $result->fetch_assoc();
    echo json_encode(array('success' => true, 'data' => $quiz));
} else {
    echo json_encode(array('success' => false, 'message' => '未找到问题信息'));
}

$conn->close();
?>