<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$conn = getDBConnection();
$sql = "SELECT q.id, q.question, NOW() as created_at, '正常' as status, '系统' as username, 
        0 as answer_count 
        FROM quizzes q 
        ORDER BY q.id DESC";
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
