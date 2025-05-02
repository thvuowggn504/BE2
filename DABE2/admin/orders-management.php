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
    elseif ($_POST['action'] === 'delete' && !empty($_POST['OrderID'])) {
        $oid = intval($_POST['OrderID']);
        $stmt = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
        $stmt->bind_param("i", $oid);
        $stmt->execute();
    }
    header("Location: orders-management.php");
    exit;
}

// DELETE (legacy support)
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
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            <div class="header-actions">
                <h5 class="mb-0">Danh sách đơn hàng</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                    <i class="bi bi-plus-circle"></i> Thêm đơn hàng
                </button>
            </div>
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
                                <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['OrderID'] ?>"
                                        data-userid="<?= $row['UserID'] ?>"
                                        data-username="<?= htmlspecialchars($row['FullName']) ?>"
                                        data-price="<?= $row['TotalPrice'] ?>"
                                        data-status="<?= $row['Status'] ?>">
                                    <i class="bi bi-pencil"></i> Sửa
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?= $row['OrderID'] ?>"
                                        data-username="<?= htmlspecialchars($row['FullName']) ?>">
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

<!-- Modal Thêm Đơn Hàng -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addOrderModalLabel">Thêm đơn hàng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add-userid" class="form-label">Khách hàng</label>
                        <select class="form-select" id="add-userid" name="UserID" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php 
                            // Reset con trỏ của $users để bắt đầu lại từ đầu
                            if ($users) {
                                $users->data_seek(0);
                                while($u = $users->fetch_assoc()): 
                            ?>
                                <option value="<?= $u['UserID'] ?>">
                                    <?= htmlspecialchars($u['FullName']) ?>
                                </option>
                            <?php 
                                endwhile;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-price" class="form-label">Tổng tiền</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="add-price" name="TotalPrice" step="0.01" required>
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="add-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="add-status" name="Status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
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

<!-- Modal Sửa Đơn Hàng -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editOrderModalLabel">Cập nhật đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="OrderID" id="edit-orderid">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Khách hàng</label>
                        <input type="text" class="form-control" id="edit-username" disabled>
                        <input type="hidden" name="UserID" id="edit-userid">
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Tổng tiền</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="edit-price" name="TotalPrice" step="0.01" required>
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="edit-status" name="Status" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
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

<!-- Modal Xóa Đơn Hàng -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteOrderModalLabel">Xác nhận xóa đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa đơn hàng của khách hàng <strong id="delete-username"></strong>?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="OrderID" id="delete-orderid">
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
            const userid = this.getAttribute('data-userid');
            const username = this.getAttribute('data-username');
            const price = this.getAttribute('data-price');
            const status = this.getAttribute('data-status');
            
            // Điền dữ liệu vào form sửa
            document.getElementById('edit-orderid').value = id;
            document.getElementById('edit-userid').value = userid;
            document.getElementById('edit-username').value = username;
            document.getElementById('edit-price').value = price;
            document.getElementById('edit-status').value = status;
            
            // Hiển thị modal sửa
            const editModal = new bootstrap.Modal(document.getElementById('editOrderModal'));
            editModal.show();
        });
    });
    
    // Xử lý khi nhấn nút xóa
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            
            // Điền dữ liệu vào modal xác nhận xóa
            document.getElementById('delete-orderid').value = id;
            document.getElementById('delete-username').textContent = username;
            
            // Hiển thị modal xóa
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));
            deleteModal.show();
        });
    });
});
</script>
</body>
</html>