<?php
require_once '../includes/database.php';
$conn = Database::getConnection();

// Lấy action, id, search
$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';

// 1) XỬ LÝ POST (ADD / EDIT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['CategoryName']);
    $desc = trim($_POST['Description']);

    if ($_POST['action'] === 'add') {
        $stmt = $conn->prepare("INSERT INTO Categories (CategoryName, Description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $desc);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'edit' && !empty($_POST['CategoryID'])) {
        $cid = intval($_POST['CategoryID']);
        $stmt = $conn->prepare("UPDATE Categories SET CategoryName=?, Description=? WHERE CategoryID=?");
        $stmt->bind_param("ssi", $name, $desc, $cid);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'delete' && !empty($_POST['CategoryID'])) {
        $cid = intval($_POST['CategoryID']);
        $stmt = $conn->prepare("DELETE FROM Categories WHERE CategoryID = ?");
        $stmt->bind_param("i", $cid);
        $stmt->execute();
    }
    header("Location: categories-management.php");
    exit;
}

// 2) XỬ LÝ DELETE (Giữ lại cho tương thích ngược)
if ($action === 'delete' && !empty($id)) {
    $cid = intval($id);
    $stmt = $conn->prepare("DELETE FROM Categories WHERE CategoryID = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    header("Location: categories-management.php");
    exit;
}

// 3) LẤY DỮ LIỆU CHO EDIT
$editRow = ['CategoryID'=>'','CategoryName'=>'','Description'=>''];
if ($action === 'edit' && !empty($id)) {
    $cid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Categories WHERE CategoryID = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $editRow = $row;
    }
}

// 4) TRUY VẤN LIST & SEARCH
$stmt = $conn->prepare("SELECT * FROM Categories WHERE CategoryName LIKE ?");
$like = "%$search%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f8f9fa;
        }
        .page-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .table-responsive {
            margin-bottom: 20px;
        }
        .actions {
            white-space: nowrap;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .category-desc {
            max-width: 500px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Quản lý danh mục</h2>
    
    <!-- Tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm danh mục..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách danh mục -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="header-actions">
                <h5 class="mb-0">Danh sách danh mục</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i> Thêm danh mục
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['CategoryID'] ?></td>
                            <td><strong><?= htmlspecialchars($row['CategoryName']) ?></strong></td>
                            <td class="category-desc"><?= htmlspecialchars($row['Description']) ?></td>
                            <td class="actions">
                                <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['CategoryID'] ?>"
                                        data-name="<?= htmlspecialchars($row['CategoryName']) ?>"
                                        data-desc="<?= htmlspecialchars($row['Description']) ?>">
                                    <i class="bi bi-pencil"></i> Sửa
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?= $row['CategoryID'] ?>"
                                        data-name="<?= htmlspecialchars($row['CategoryName']) ?>">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm Danh Mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add-categoryname" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="add-categoryname" name="CategoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="add-description" name="Description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Danh Mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editCategoryModalLabel">Cập nhật danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="CategoryID" id="edit-categoryid">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-categoryname" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="edit-categoryname" name="CategoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit-description" name="Description" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xóa Danh Mục -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Xác nhận xóa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="delete-category-name"></strong>?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="CategoryID" id="delete-categoryid">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý khi nhấn nút sửa
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const desc = this.getAttribute('data-desc');
            
            // Điền dữ liệu vào form sửa
            document.getElementById('edit-categoryid').value = id;
            document.getElementById('edit-categoryname').value = name;
            document.getElementById('edit-description').value = desc;
            
            // Hiển thị modal sửa
            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        });
    });
    
    // Xử lý khi nhấn nút xóa
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            // Điền dữ liệu vào modal xác nhận xóa
            document.getElementById('delete-categoryid').value = id;
            document.getElementById('delete-category-name').textContent = name;
            
            // Hiển thị modal xóa
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
            deleteModal.show();
        });
    });
});
</script>
</body>
</html>