<?php
// api/categories.php
require_once '../includes/database.php'; // Kết nối CSDL
$conn = Database::getConnection();

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'list':
        listCategories();
        break;
    case 'add':
        addCategory();
        break;
    case 'edit':
        editCategory();
        break;
    case 'delete':
        deleteCategory();
        break;
    default:
        // echo "Hành động không hợp lệ.";
        listCategories();
        break;
}

function listCategories() {
    global $conn;
    $search = $_GET['search'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM Categories WHERE CategoryName LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode($categories);
}

function addCategory() {
    global $conn;
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';

    if ($name === '') {
        echo "Tên danh mục không được để trống.";
        return;
    }

    $stmt = $conn->prepare("INSERT INTO Categories (CategoryName, Description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $desc);
    if ($stmt->execute()) {
        echo "Thêm danh mục thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}

function editCategory() {
    global $conn;
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';

    if ($id === '' || $name === '') {
        echo "Dữ liệu không hợp lệ.";
        return;
    }

    $stmt = $conn->prepare("UPDATE Categories SET CategoryName = ?, Description = ? WHERE CategoryID = ?");
    $stmt->bind_param("ssi", $name, $desc, $id);
    if ($stmt->execute()) {
        echo "Cập nhật danh mục thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}

function deleteCategory() {
    global $conn;
    $id = $_POST['id'] ?? '';

    if ($id === '') {
        echo "ID không hợp lệ.";
        return;
    }

    $stmt = $conn->prepare("DELETE FROM Categories WHERE CategoryID = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Xóa danh mục thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>
