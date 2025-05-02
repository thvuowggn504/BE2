<?php
// api/categories/delete.php
require_once '../../includes/database.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = intval($data['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(["message" => "ID không hợp lệ."]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM Categories WHERE CategoryID = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

echo json_encode(["message" => "Xóa danh mục thành công!"]);
?>
