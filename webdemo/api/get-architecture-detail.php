<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$id = intval($_GET['id']);
$conn = getDBConnection();
$result = $conn->query("SELECT * FROM architectures WHERE id = $id");
$data = $result->fetch_assoc();

// 简化处理：只做基本HTML转义，前端使用Markdown解析器
if (!empty($data['description'])) {
    // 基本安全过滤，防止XSS
    $data['description'] = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');
}

echo json_encode(array('success' => true, 'data' => $data));
$conn->close();
?>