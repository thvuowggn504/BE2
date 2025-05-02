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
    header("Location: categories-management.php");
    exit;
}

// 2) XỬ LÝ DELETE
if ($action === 'delete' && !empty($id)) {
    $cid = intval($id);
    $stmt = $conn->prepare("DELETE FROM Categories WHERE CategoryID = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    header("Location: categories-management.php");
    exit;
}

// 3) LẤY DỮ LIỆU CHO EDIT
$editMode = false;
$editRow  = ['CategoryID'=>'','CategoryName'=>'','Description'=>''];
if ($action === 'edit' && !empty($id)) {
    $cid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Categories WHERE CategoryID = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $editMode = true;
        $editRow  = $row;
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
            <h5 class="mb-0">Danh sách danh mục</h5>
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
                                <a href="?action=edit&id=<?= $row['CategoryID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="?action=delete&id=<?= $row['CategoryID'] ?>" 
                                   onclick="return confirm('Xác nhận xóa danh mục này?')" 
                                   class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Form thêm/sửa danh mục -->
    <div class="card">
        <div class="card-header bg-<?= $editMode ? 'warning' : 'success' ?> text-white">
            <h5 class="mb-0"><?= $editMode ? 'Cập nhật danh mục' : 'Thêm danh mục mới' ?></h5>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editMode ? 'edit' : 'add' ?>">
                <?php if ($editMode): ?>
                    <input type="hidden" name="CategoryID" value="<?= $editRow['CategoryID'] ?>">
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="categoryname" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="categoryname" name="CategoryName" required
                               value="<?= htmlspecialchars($editRow['CategoryName']) ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="Description" rows="4"><?= htmlspecialchars($editRow['Description']) ?></textarea>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= $editMode ? 'warning' : 'success' ?>">
                        <?= $editMode ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <?php if ($editMode): ?>
                        <a href="categories-management.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>