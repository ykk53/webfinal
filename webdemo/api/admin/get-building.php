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
    echo json_encode(array('success' => false, 'message' => '缺少建筑ID'));
    exit;
}

$conn = getDBConnection();
$sql = "SELECT id, name, location, dynasty, type, description, image, created_at 
        FROM architectures 
        WHERE id = " . intval($id);

$result = $conn->query($sql);
$building = null;

if ($result->num_rows > 0) {
    $building = $result->fetch_assoc();
}

if ($building) {
    echo json_encode(array('success' => true, 'data' => $building));
} else {
    echo json_encode(array('success' => false, 'message' => '未找到建筑信息'));
}

$conn->close();
?>