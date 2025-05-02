<?php
// api/categories/update.php
require_once '../../includes/database.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = intval($data['id'] ?? 0);
$value = trim($data['value'] ?? '');
$field = $data['field'] ?? '';

if ($id <= 0 || empty($field)) {
    http_response_code(400);
    echo json_encode(["message" => "Dữ liệu không hợp lệ."]);
    exit;
}

if ($field == 'name') {
    $stmt = $conn->prepare("UPDATE Categories SET CategoryName = ? WHERE CategoryID = ?");
} elseif ($field == 'desc') {
    $stmt = $conn->prepare("UPDATE Categories SET Description = ? WHERE CategoryID = ?");
} else {
    http_response_code(400);
    echo json_encode(["message" => "Trường cập nhật không hợp lệ."]);
    exit;
}

$stmt->bind_param('si', $value, $id);
$stmt->execute();

echo json_encode(["message" => "Cập nhật thành công!"]);
?>
