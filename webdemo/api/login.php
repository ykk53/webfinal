<?php
header('Content-Type: application/json');
require_once '../config/database.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$conn = getDBConnection();
$sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(array('success' => false, 'message' => '用户不存在'));
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    echo json_encode(array('success' => false, 'message' => '密码错误'));
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

echo json_encode(array('success' => true, 'message' => '登录成功'));
$conn->close();
?>
