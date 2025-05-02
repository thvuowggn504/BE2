<?php
require_once '../includes/database.php';
$conn = Database::getConnection();

$action = $_GET['action'] ?? '';
$id     = $_GET['id'] ?? '';
$search = $_GET['search'] ?? '';

// POST: add / edit / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['FullName'] ?? '');
    $email    = trim($_POST['Email'] ?? '');
    $phone    = trim($_POST['Phone'] ?? '');
    $utype    = $_POST['UserType'] ?? '';
    $password = $_POST['Password'] ?? '';

    if ($_POST['action'] === 'add') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare(
          "INSERT INTO Users (FullName, Email, PasswordHash, Phone, UserType)
           VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param("sssss", $name, $email, $hash, $phone, $utype);
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'edit' && !empty($_POST['UserID'])) {
        $uid  = intval($_POST['UserID']);
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
              "UPDATE Users SET FullName=?, Email=?, PasswordHash=?, Phone=?, UserType=? WHERE UserID=?"
            );
            $stmt->bind_param("sssssi", $name, $email, $hash, $phone, $utype, $uid);
        } else {
            $stmt = $conn->prepare(
              "UPDATE Users SET FullName=?, Email=?, Phone=?, UserType=? WHERE UserID=?"
            );
            $stmt->bind_param("ssssi", $name, $email, $phone, $utype, $uid);
        }
        $stmt->execute();
    }
    elseif ($_POST['action'] === 'delete' && !empty($_POST['UserID'])) {
        $uid = intval($_POST['UserID']);
        $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
    }
    header("Location: users-management.php");
    exit;
}

// DELETE (giữ lại cho tương thích ngược)
if ($action === 'delete' && !empty($id)) {
    $uid = intval($id);
    $stmt = $conn->prepare("DELETE FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    header("Location: users-management.php");
    exit;
}

// EDIT MODE (giữ lại phần này để xem chi tiết khi cần)
$editRow = ['UserID'=>'','FullName'=>'','Email'=>'','Phone'=>'','UserType'=>''];
if ($action === 'edit' && !empty($id)) {
    $uid = intval($id);
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($r = $res->fetch_assoc()) {
        $editRow = $r;
    }
}

// TRUY VẤN LIST
$stmt = $conn->prepare(
  "SELECT * FROM Users WHERE FullName LIKE ? OR Email LIKE ?"
);
$like = "%$search%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
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
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="page-title">Quản lý người dùng</h2>
    
    <!-- Tìm kiếm -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Tìm tên hoặc email..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách người dùng -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="header-actions">
                <h5 class="mb-0">Danh sách người dùng</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle"></i> Thêm người dùng
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Loại</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['UserID'] ?></td>
                            <td><?= htmlspecialchars($row['FullName']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['Phone']) ?></td>
                            <td>
                                <span class="badge <?= $row['UserType'] === 'Admin' ? 'bg-danger' : ($row['UserType'] === 'VIP' ? 'bg-success' : 'bg-secondary') ?>">
                                    <?= $row['UserType'] ?>
                                </span>
                            </td>
                            <td class="actions">
                                <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                        data-id="<?= $row['UserID'] ?>"
                                        data-name="<?= htmlspecialchars($row['FullName']) ?>"
                                        data-email="<?= htmlspecialchars($row['Email']) ?>"
                                        data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                                        data-type="<?= $row['UserType'] ?>">
                                    <i class="bi bi-pencil"></i> Sửa
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?= $row['UserID'] ?>"
                                        data-name="<?= htmlspecialchars($row['FullName']) ?>">
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

<!-- Modal Thêm Người dùng -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addUserModalLabel">Thêm người dùng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add-fullname" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="add-fullname" name="FullName" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add-email" name="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="add-phone" name="Phone">
                    </div>
                    <div class="mb-3">
                        <label for="add-usertype" class="form-label">Loại tài khoản</label>
                        <select class="form-select" id="add-usertype" name="UserType">
                            <?php foreach(['Regular','VIP','Admin'] as $t): ?>
                                <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="add-password" name="Password" required>
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

<!-- Modal Sửa Người dùng -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editUserModalLabel">Cập nhật người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="UserID" id="edit-userid">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="edit-fullname" name="FullName" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-phone" class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" id="edit-phone" name="Phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit-usertype" class="form-label">Loại tài khoản</label>
                        <select class="form-select" id="edit-usertype" name="UserType">
                            <?php foreach(['Regular','VIP','Admin'] as $t): ?>
                                <option value="<?= $t ?>"><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="edit-password" name="Password" 
                               placeholder="Để trống nếu không đổi">
                        <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu</div>
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

<!-- Modal Xóa Người dùng -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa người dùng <strong id="delete-user-name"></strong>?</p>
                <p class="text-danger">Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="UserID" id="delete-userid">
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
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const type = this.getAttribute('data-type');
            
            // Điền dữ liệu vào form sửa
            document.getElementById('edit-userid').value = id;
            document.getElementById('edit-fullname').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-usertype').value = type;
            document.getElementById('edit-password').value = '';
            
            // Hiển thị modal sửa
            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
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
            document.getElementById('delete-userid').value = id;
            document.getElementById('delete-user-name').textContent = name;
            
            // Hiển thị modal xóa
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            deleteModal.show();
        });
    });
});
</script>
</body>
</html>