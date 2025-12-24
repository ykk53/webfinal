<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../config/session.php';

if (!isAdmin()) {
    echo json_encode(array('success' => false, 'message' => '无权访问'));
    exit;
}

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$name = isset($_POST['name']) ? $_POST['name'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';
$dynasty = isset($_POST['dynasty']) ? $_POST['dynasty'] : '';
$location = isset($_POST['location']) ? $_POST['location'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';

if (empty($id) || empty($name) || empty($type) || empty($dynasty) || empty($location)) {
    echo json_encode(array('success' => false, 'message' => '缺少必填字段'));
    exit;
}

// 处理图片上传（如果有）
$imagePath = '';
$updateImage = false;

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
        $updateImage = true;
        
        // 删除旧图片
        $conn = getDBConnection();
        $sql = "SELECT image FROM architectures WHERE id = " . intval($id);
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $oldImage = $row['image'];
            if (!empty($oldImage) && file_exists('../../' . $oldImage)) {
                unlink('../../' . $oldImage);
            }
        }
        $conn->close();
    } else {
        echo json_encode(array('success' => false, 'message' => '图片上传失败'));
        exit;
    }
}

$conn = getDBConnection();

// 构建更新SQL
$sql = "UPDATE architectures SET 
        name = '" . $conn->real_escape_string($name) . "',
        type = '" . $conn->real_escape_string($type) . "',
        dynasty = '" . $conn->real_escape_string($dynasty) . "',
        location = '" . $conn->real_escape_string($location) . "',
        description = '" . $conn->real_escape_string($description) . "'";

// 如果有新图片则更新图片路径
if ($updateImage) {
    $sql .= ", image = '" . $conn->real_escape_string($imagePath) . "'";
}

$sql .= " WHERE id = " . intval($id);

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true, 'message' => '更新成功'));
} else {
    echo json_encode(array('success' => false, 'message' => '更新失败: ' . $conn->error));
}

$conn->close();
?>