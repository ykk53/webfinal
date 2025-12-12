<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$conn = getDBConnection();
$result = $conn->query("SELECT * FROM quizzes ORDER BY RAND() LIMIT 1");
$quiz = $result->fetch_assoc();

echo json_encode(array('success' => true, 'data' => $quiz));
$conn->close();
?>
