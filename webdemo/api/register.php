<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$conn = getDBConnection();

// 检查用户名
$check = $conn->query("SELECT id FROM users WHERE username = '" . $conn->real_escape_string($username) . "'");
if ($check->num_rows > 0) {
    echo json_encode(array('success' => false, 'message' => '用户名已存在'));
    exit;
}

// 插入用户
$sql = "INSERT INTO users (username, email, password) VALUES ('" . 
       $conn->real_escape_string($username) . "', '" . 
       $conn->real_escape_string($email) . "', '" . 
       $conn->real_escape_string($password) . "')";

if ($conn->query($sql)) {
    echo json_encode(array('success' => true, 'message' => '注册成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '注册失败'));
}

$conn->close();
?>
