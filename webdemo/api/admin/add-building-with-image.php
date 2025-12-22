<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$name = $_POST['name'];
$type = $_POST['type'];
$dynasty = $_POST['dynasty'];
$location = $_POST['location'];
$description = $_POST['description'];

if (empty($name) || empty($type) || empty($dynasty) || empty($location)) {
    echo json_encode(array('success' => false, 'message' => '缺少必填字段'));
    exit;
}

$imagePath = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = '../../images/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(array('success' => false, 'message' => '只允许上传 JPG, PNG, GIF 格式的图片'));
        exit;
    }
    
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        echo json_encode(array('success' => false, 'message' => '图片大小不能超过5MB'));
        exit;
    }
    
    $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
    $uploadFile = $uploadDir . $newFileName;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        $imagePath = 'images/' . $newFileName;
    } else {
        echo json_encode(array('success' => false, 'message' => '图片上传失败'));
        exit;
    }
}

$conn = getDBConnection();
$sql = "INSERT INTO architectures (name, type, dynasty, location, description, image) VALUES ('" . 
       $conn->real_escape_string($name) . "', '" . 
       $conn->real_escape_string($type) . "', '" . 
       $conn->real_escape_string($dynasty) . "', '" . 
       $conn->real_escape_string($location) . "', '" . 
       $conn->real_escape_string($description) . "', '" . 
       $conn->real_escape_string($imagePath) . "')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '添加成功', 'id' => $conn->insert_id));
} else {
    echo json_encode(array('success' => false, 'message' => '添加失败: ' . $conn->error));
}

$conn->close();
?>
