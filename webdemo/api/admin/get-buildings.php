<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
session_start();

$conn = getDBConnection();
$sql = "SELECT id, name, location, dynasty as year, type, description, image, NOW() as created_at FROM architectures ORDER BY id DESC";
$result = $conn->query($sql);

$buildings = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $buildings[] = $row;
    }
}

echo json_encode(array('success' => true, 'data' => $buildings));
$conn->close();
?>
