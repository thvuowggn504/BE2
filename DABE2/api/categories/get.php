<?php
// api/categories/get.php
require_once '../../includes/database.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM Categories";
if (!empty($keyword)) {
    $sql .= " WHERE CategoryName LIKE ?";
}

$stmt = $conn->prepare($sql);
if (!empty($keyword)) {
    $searchTerm = "%$keyword%";
    $stmt->bind_param('s', $searchTerm);
}
$stmt->execute();

$result = $stmt->get_result();
$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);
?>
