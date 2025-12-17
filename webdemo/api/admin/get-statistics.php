<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$conn = getDBConnection();

$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$buildingCount = $conn->query("SELECT COUNT(*) as count FROM architectures")->fetch_assoc()['count'];
$qaCount = $conn->query("SELECT COUNT(*) as count FROM quizzes")->fetch_assoc()['count'];

$stats = array(
    'users' => $userCount,
    'buildings' => $buildingCount,
    'qa' => $qaCount
);

echo json_encode(array('success' => true, 'data' => $stats));
$conn->close();
?>
