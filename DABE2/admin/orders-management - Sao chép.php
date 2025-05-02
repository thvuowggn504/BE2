<?php
require_once '../includes/database.php';
$conn = Database::getConnection();

$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';

// POST: chỉ xử lý update status hoặc add order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'update' && !empty($_POST['OrderID'])) {
        $oid    = intval($_POST['OrderID']);
        $status = $_POST['Status'];
        $stmt = $conn->prepare("UPDATE Orders SET Status=? WHERE OrderID=?");
        $stmt->bind_param("si", $status, $oid);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'add') {
        $uid = intval($_POST['UserID']);
        $total = floatval($_POST['TotalPrice']);
        $status = $_POST['Status'];
        $stmt = $conn->prepare(
          "INSERT INTO Orders (UserID, TotalPrice, Status) VALUES (?,?,?)"
        );
        $stmt->bind_param("ids", $uid, $total, $status);
        $stmt->execute();
    }
    header("Location: orders-management.php");
    exit;
}

// DELETE
if ($action === 'delete' && !empty($id)) {
    $oid = intval($id);
    $stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    header("Location: orders-management.php");
    exit;
}

// EDIT MODE: lấy thông tin order
$editMode = false;
$orderRow = ['OrderID'=>'','UserID'=>'','TotalPrice'=>'','Status'=>''];
if ($action === 'edit' && !empty($id)) {
    $oid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($r = $res->fetch_assoc()) {
        $editMode = true;
        $orderRow = $r;
    }
}

// Danh sách users cho <select>
$users = $conn->query("SELECT UserID, FullName FROM Users");

// LẤY DANH SÁCH ĐƠN HÀNG
$stmt = $conn->prepare(
  "SELECT o.*, u.FullName 
   FROM Orders o
   JOIN Users u ON o.UserID=u.UserID
   WHERE u.FullName LIKE ?"
);
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
    <title>Quản lý đơn hàng</title>
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
        .price {
            font-weight: bold;
            color: #e74c3c;
        }
        .status {
            font-weight: bold;
        }
        .status-pending {
            color: #f39c12;
        }
        .status-completed {
            color: #27ae60;
        }
        .status-cancelled {
            color: #e74c3c;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Quản lý đơn hàng</h2>
    
    <!-- Tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Tìm theo tên khách hàng..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách đơn hàng -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Danh sách đơn hàng</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['OrderID'] ?></td>
                            <td><?= htmlspecialchars($row['FullName']) ?></td>
                            <td class="price"><?= number_format($row['TotalPrice'], 0, ',', '.') ?> đ</td>
                            <td>
                                <?php 
                                $statusClass = '';
                                if ($row['Status'] === 'Pending') {
                                    $statusClass = 'status-pending';
                                    $badgeClass = 'bg-warning';
                                } elseif ($row['Status'] === 'Completed') {
                                    $statusClass = 'status-completed';
                                    $badgeClass = 'bg-success';
                                } elseif ($row['Status'] === 'Cancelled') {
                                    $statusClass = 'status-cancelled';
                                    $badgeClass = 'bg-danger';
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?> status <?= $statusClass ?>"><?= $row['Status'] ?></span>
                            </td>
                            <td><?= $row['CreatedAt'] ?></td>
                            <td class="actions">
                                <a href="?action=edit&id=<?= $row['OrderID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="?action=delete&id=<?= $row['OrderID'] ?>" 
                                   onclick="return confirm('Xác nhận xóa đơn hàng?')" 
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
    
    <!-- Form thêm/sửa đơn hàng -->
    <div class="card">
        <div class="card-header bg-<?= $editMode ? 'warning' : 'success' ?> text-white">
            <h5 class="mb-0"><?= $editMode ? 'Cập nhật đơn hàng' : 'Tạo đơn hàng mới' ?></h5>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editMode ? 'update' : 'add' ?>">
                <?php if ($editMode): ?>
                    <input type="hidden" name="OrderID" value="<?= $orderRow['OrderID'] ?>">
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="customer" class="form-label">Khách hàng</label>
                        <select class="form-select" id="customer" name="UserID" required <?= $editMode ? 'disabled' : '' ?>>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php 
                            // Reset con trỏ của $users để bắt đầu lại từ đầu
                            if ($users) {
                                $users->data_seek(0);
                                while($u = $users->fetch_assoc()): 
                            ?>
                                <option value="<?= $u['UserID'] ?>"
                                    <?= $orderRow['UserID']==$u['UserID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($u['FullName']) ?>
                                </option>
                            <?php 
                                endwhile;
                            }
                            ?>
                        </select>
                        <?php if ($editMode): ?>
                            <input type="hidden" name="UserID" value="<?= $orderRow['UserID'] ?>">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="totalprice" class="form-label">Tổng tiền</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="totalprice" name="TotalPrice" step="0.01" required
                                   value="<?= htmlspecialchars($orderRow['TotalPrice']) ?>">
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="Status" required>
                            <?php
                            $states = ['Pending','Completed','Cancelled'];
                            foreach($states as $st): ?>
                                <option value="<?= $st ?>"
                                    <?= $orderRow['Status']==$st ? 'selected' : '' ?>>
                                    <?= $st ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-<?= $editMode ? 'warning' : 'success' ?>">
                        <?= $editMode ? 'Cập nhật' : 'Tạo mới' ?>
                    </button>
                    <?php if ($editMode): ?>
                        <a href="orders-management.php" class="btn btn-secondary">Hủy</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>