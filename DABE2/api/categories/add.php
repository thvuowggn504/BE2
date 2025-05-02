<?php
// api/categories/add.php
require_once '../../includes/database.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$desc = trim($data['desc'] ?? '');

if (empty($name)) {
    http_response_code(400);
    echo json_encode(["message" => "Tên danh mục không được để trống."]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Categories (CategoryName, Description) VALUES (?, ?)");
$stmt->bind_param('ss', $name, $desc);
$stmt->execute();

echo json_encode(["message" => "Thêm danh mục thành công!"]);
?>
