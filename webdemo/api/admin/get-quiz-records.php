<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$conn = getDBConnection();
$sql = "SELECT qr.id, u.username, q.question, qr.user_answer, qr.is_correct, qr.answered_at as created_at 
        FROM quiz_records qr 
        LEFT JOIN users u ON qr.user_id = u.id 
        LEFT JOIN quizzes q ON qr.quiz_id = q.id 
        ORDER BY qr.id DESC";
$result = $conn->query($sql);

$records = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

echo json_encode(array('success' => true, 'data' => $records));
$conn->close();
?>
