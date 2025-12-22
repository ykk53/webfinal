<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$conn = getDBConnection();

// 查询时排除管理员账号，并处理用户可能不存在的情况
$sql = "SELECT up.user_id, COALESCE(u.username, '已注销用户') as username, up.points 
        FROM user_points up
        LEFT JOIN users u ON up.user_id = u.id
        WHERE (u.is_admin = 0 OR u.id IS NULL)
        ORDER BY up.points DESC, up.updated_at ASC
        LIMIT 10";

$result = $conn->query($sql);

$rankings = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

echo json_encode(array('success' => true, 'data' => $rankings));
$conn->close();
?>
