<?php
header('Content-Type: application/json');
require_once '../config/database.php';  /* 避免重复定义 */

$conn = getDBConnection();

$type = isset($_GET['type']) ? $_GET['type'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 0;

$sql = "SELECT id, name, type, dynasty, location, image, description FROM architectures";

if (!empty($type)) {
    $sql .= " WHERE type = '" . $conn->real_escape_string($type) . "'"; /* 转义 */
}

$sql .= " ORDER BY id DESC";

if ($limit > 0) {
    $sql .= " LIMIT " . $limit;
}

$result = $conn->query($sql);  /* 执行sql语句返回json数据，存在result */

$architectures = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $architectures[] = $row;  /* 存入每一行关联数组 */
    }
}

echo json_encode(array(
    'success' => true,
    'data' => $architectures
));

$conn->close();
?>
