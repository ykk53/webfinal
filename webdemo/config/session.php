<?php
// 会话管理
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 检查用户是否登录
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// 检查是否为管理员
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// 获取当前用户ID
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// 获取当前用户名
function getCurrentUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}
?>
