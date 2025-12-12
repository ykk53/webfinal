<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$id = intval($_GET['id']);
$conn = getDBConnection();
$result = $conn->query("SELECT * FROM architectures WHERE id = $id");
$data = $result->fetch_assoc();

echo json_encode(array('success' => true, 'data' => $data));
$conn->close();
?>
