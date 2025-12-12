<?php
header('Content-Type: application/json');
require_once '../config/session.php';

session_destroy();
echo json_encode(array('success' => true, 'message' => '退出成功'));
?>
