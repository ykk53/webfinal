<?php
header('Content-Type: application/json');
require_once '../config/session.php';

if (isLoggedIn()) {
    echo json_encode(array(
        'success' => true,
        'logged_in' => true,
        'username' => getCurrentUsername(),
        'is_admin' => isAdmin()
    ));
} else {
    echo json_encode(array(
        'success' => true,
        'logged_in' => false
    ));
}
?>
