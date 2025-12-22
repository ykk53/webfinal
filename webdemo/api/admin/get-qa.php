<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$conn = getDBConnection();
$sql = "SELECT id, question, option_a, option_b, option_c, option_d, correct_answer, explanation, created_at 
        FROM quizzes 
        ORDER BY id DESC";
$result = $conn->query($sql);

$qa = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $qa[] = $row;
    }
}

echo json_encode(array('success' => true, 'data' => $qa));
$conn->close();
?>